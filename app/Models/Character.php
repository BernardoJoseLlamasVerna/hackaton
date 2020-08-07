<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $table = 'character';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'characterId'        => 0,
        'skinId'             => 0,
        'hairId'             => 0,
        'characterName'      => '',
        'characterIdInSwapi' => 0
    ];

    protected $fillable = [
        'skinId',
        'hairId',
        'characterName',
        'characterIdInSwapi'
    ];

    /**
     * A character has one value of skinColor
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function skinColor()
    {
        return $this->hasOne(SkinColor::class);
    }

    /**
     * @param $pageNumber
     * @return array
     */
    public static function getAllHumanCharacters($pageNumber)
    {
        $humans = [];
        for($i = 1; $i<= $pageNumber; $i++)
        {
            $characters = json_decode(file_get_contents('https://swapi.dev/api/people/?page='.$i), true)['results'];

            foreach ($characters as $character) {
                if(empty($character['species'])) {
                    $humans[] = $character;
                }
            }
        }

        //llamamos a ver si hace falta insertar y checkeamos si existe en la tabla
        return Character::insertCharacters($humans);
        //llamamos a ver si hace falta insertar

        // return $humans;
    }

    /**
     * @param $humans
     * @return string
     */
    private static function insertCharacters($humans)
    {
        foreach ($humans as $human) {
            // sacar el id del usuario de la url
            $matches = [];
            preg_match('/\d+/', $human['url'], $matches);
            $humanSwapId = intval($matches[0]);
            // sacar el id del usuario de la url

            $humanSwapIdsFromTable = Character::all()->pluck('characterIdInSwapi')->toArray();

            // checkeamos skinColor
            $skinColors = SkinColor::all()->pluck('skinName')->toArray();
            if(!in_array($human['skin_color'], $skinColors)) {
                $skinColor = new SkinColor();
                $skinColor->skinName = $human['skin_color'];
                $skinColor->save();
            }
            $skinColorId = SkinColor::where('skinName', $human['skin_color'])->pluck('skinId')->first();
            // checkeamos skinColor

            // checkeamos hairColor
            $hairColors = HairColor::all()->pluck('colorName')->toArray();
            if(!in_array($human['hair_color'], $hairColors)) {
                $hairColor = new HairColor();
                $hairColor->colorName = $human['hair_color'];
                $hairColor->save();
            }
            $hairColorId = HairColor::where('colorName', $human['hair_color'])->pluck('hairId')->first();
            // checkeamos hairColor

            if(!in_array($humanSwapId, $humanSwapIdsFromTable)) {
                $character = new Character();
                $character->skinId = $skinColorId;
                $character->hairId = $hairColorId;
                $character->characterName = $human['name'];
                $character->characterIdInSwapi = $humanSwapId;
                $character->save();
            }
        }

        return $humans;
    }

    public static function searchingCharacters($characters, $word)
    {
        $characters->where('characterName', 'like', '%'.$word.'%');

        return $characters;
    }
}
