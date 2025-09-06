<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Unit extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    protected static function booted()
    {
        static::creating(function ($unit) {
            $unit->slug = Str::slug($unit->name);
        });
    }
}
