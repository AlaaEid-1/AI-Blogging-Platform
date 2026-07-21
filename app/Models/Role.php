<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'abilities',
    ];

    protected function casts(): array
    {
        return [
            'abilities' => 'json',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
