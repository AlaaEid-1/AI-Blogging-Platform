<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Http\Resources\PostResource;
use App\Models\Scopes\OwnerScope;
use App\Observers\PostObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Appends(['read_time'])]
#[ScopedBy(OwnerScope::class)]
#[ObservedBy(PostObserver::class)]
#[UseResource(PostResource::class)]
class Post extends Model
{
    use SoftDeletes;
    use Prunable;

    protected $connection = 'mysql';
    protected $table = 'posts';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'category_id',
        'title',
        'content',
        'excerpt',
        'cover_image',
        'status',
        'views',
        'published_at',
        'meta',
    ];

    protected $hidden = [
        'status',
        'deleted_at',
    ];

    protected $appends = [
        'thumbnail_url',
        'publish_time',

    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'meta' => 'json',
            'status' => PostStatus::class,
        ];
    }

    protected static function booted()
    {
        //static::addGlobalScope('owner', new OwnerScope);

        // creating, created, updating, updated, deleting, deleted
        // restoring, restored, forceDeleting, forceDeleted
        // retrieved, saving, saved

        static::creating(function (Post $post) {});
        //static::observe(new PostObserver());
    }

    public function scopePublished(Builder $builder, string|\DateTime|null $time = null)
    {
        $builder
            //->withoutGlobalScope('owner')
            ->where('status', PostStatus::Published)
            ->where(function ($query) use ($time) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', $time ?? now());
            });
    }

    public function scopeStatus(Builder $builder, string|PostStatus $status)
    {
        $builder->where('status', $status);
    }

    public function scopeSlug(Builder $builder, string $slug)
    {
        $builder->where('slug', $slug);
    }

    // protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')
            ->withDefault([
                'name' => 'Uncategorized',
                'slug' => 'uncategorized',
            ]);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id')->latest();
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'post_id', 'id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'post_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function content(): Attribute
    {
        return new Attribute(
            set: fn($value) => strip_tags($value, '<h2><h3><h4><h5><h6><p><a><ul><ol><li><br><strong><em><img><video><audio>'),
        );
    }

    public function title(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value),
            set: fn($value) => strip_tags($value),
        );
    }

    // public function setTitleAttribute($value)
    // {
    //     $this->attributes['title'] = strip_tags($value);
    // }

    // $post->thumbnail_url
    public function thumbnailUrl(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->cover_image
                    ? Storage::disk('public')->url($this->cover_image)
                    : asset('images/default-thumbnail.jpg');
            }
        );
    }

    // $post->publish_time
    public function publishTime(): Attribute
    {
        return new Attribute(
            get: fn() => $this->published_at ?? $this->created_at,
        );
    }

    // $post->read_time
    // public function readTime(): Attribute
    // {
    //     return (new Attribute(
    //         get: fn() => \ceil($this->wordCount() / 200)
    //     ))->shouldCache();
    // }

    public function getReadTimeAttribute()
    {
        return  \ceil($this->wordCount() / 200);
    }

    public function wordCount(): int
    {
        return \str_word_count($this->content);
    }
}
