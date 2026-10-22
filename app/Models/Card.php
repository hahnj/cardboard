<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Card extends Model
{
    /** @use HasFactory<\Database\Factories\CardFactory> */
    use HasFactory;

    public function types(): HasMany
    {
        return $this->hasMany(Type::class);
    }

    public function supertypes(): HasMany
    {
        return $this->hasMany(Supertype::class);
    }

    public function sets(): HasMany
    {
        return $this->hasMany(Set::class);
    }

    public function artists(): HasMany
    {
        return $this->hasMany(Artist::class);
    }
}
