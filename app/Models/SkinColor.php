<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkinColor extends Model
{
    protected $table = 'skinColor';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'skinId'   => 0,
        'skinName' => '',
    ];

    protected $fillable = [
        'skinName'
    ];

    /**
     * Relationship: SkinColor has many characters
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function character()
    {
        return $this->hasMany(Character::class);
    }
}
