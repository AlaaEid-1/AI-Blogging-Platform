<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    // use SoftDeletes;

    //
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

    protected static function booted()
    {
        // static::deleted(function (Category $category) {
        //     $category->posts()->delete();
        // });

        // static::restored(function (Category $category) {
        //     $category->posts()->update([
        //         'deleted_at' => null,
        //     ]);
        // });
    }
}
