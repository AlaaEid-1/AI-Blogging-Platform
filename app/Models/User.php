<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Override;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = [
    'name',
    'email',
    'username', 
    'password',
    'status',
];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id')
            ->with([
                'id',
                'created_at'
            ]);
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id',  'user_id')
            ->with([
                'id',
                'created_at'
            ]);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function avatarUrl(): Attribute
    {
        return new Attribute(
            get: fn() => $this->avatar ? Storage::disk('public')->url($this->avatar) : asset('images/avatars/blank.png')
        );
    }

    public function routeNotificationForMail($notification = null)
    {
        return $this->email;
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.User.' . $this->id;
    }

    public function hasAbility(string $ability): bool
    {
        foreach ($this->roles as $role) {
            if (in_array($ability, $role->abilities)) {
                return true;
            }
        }
        return false;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id', 'id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'user_id', 'id');
    }

    public function bookmarkedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'bookmarks', 'user_id', 'post_id')->withTimestamps();
    }
}
