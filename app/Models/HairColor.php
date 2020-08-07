<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HairColor extends Model
{
    protected $table = 'hairColor';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'hairId'      => 0,
        'colorName'   => ''
    ];

    protected $fillable = [
        'colorName'
    ];

    /**
     * Relationship: HairColor has many characters
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function character()
    {
        return $this->hasMany(Character::class);
    }
}
