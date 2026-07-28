<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'status',
        'bio',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Use username as the route model binding key.
     */
    public function getRouteKeyName(): string
    {
        return 'username';
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    /**
     * Users who follow this user.
     * pivot: followers.user_id = this user, followers.follower_id = the follower
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id')
            ->withPivot('id', 'created_at');
    }

    /**
     * Users this user is following.
     * pivot: followers.follower_id = this user, followers.user_id = the followed user
     */
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id')
            ->withPivot('id', 'created_at');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'user_id', 'id');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class, 'user_id', 'id');
    }

    public function bookmarkedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'bookmarks', 'user_id', 'post_id')->withTimestamps();
    }

    // -------------------------------------------------------------------------
    // Computed Attributes
    // -------------------------------------------------------------------------

    /** @return Attribute<string, never> */
    public function avatarUrl(): Attribute
    {
        return new Attribute(
            get: fn () => $this->avatar
                ? Storage::disk('public')->url($this->avatar)
                : asset('images/avatars/blank.png')
        );
    }

    // -------------------------------------------------------------------------
    // Helper Methods (with in-request caching)
    // -------------------------------------------------------------------------

    protected ?array $favoritesCache = null;

    public function hasFavorited(int $postId): bool
    {
        if ($this->favoritesCache === null) {
            $this->favoritesCache = $this->favorites()->pluck('post_id')->toArray();
        }

        return in_array($postId, $this->favoritesCache);
    }

    protected ?array $bookmarksCache = null;

    public function hasBookmarked(int $postId): bool
    {
        if ($this->bookmarksCache === null) {
            $this->bookmarksCache = $this->bookmarks()->pluck('post_id')->toArray();
        }

        return in_array($postId, $this->bookmarksCache);
    }

    protected ?array $followingsCache = null;

    public function isFollowing(int $userId): bool
    {
        if ($this->followingsCache === null) {
            $this->followingsCache = $this->followings()->pluck('users.id')->toArray();
        }

        return in_array($userId, $this->followingsCache);
    }

    public function hasAbility(string $ability): bool
    {
        if ($this->type === 'super-admin' || $this->type === 'admin') {
            return true;
        }

        if (! $this->relationLoaded('roles')) {
            $this->load('roles');
        }

        foreach ($this->roles as $role) {
            if (is_array($role->abilities) && in_array($ability, $role->abilities)) {
                return true;
            }
        }

        return false;
    }

    // -------------------------------------------------------------------------
    // Broadcast / Notifications
    // -------------------------------------------------------------------------

    public function routeNotificationForMail($notification = null)
    {
        return $this->email;
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'App.Models.User.'.$this->id;
    }
}
