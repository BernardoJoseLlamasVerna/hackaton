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
     * Relationship: A character has one value of skinColor
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function skinColor()
    {
        return $this->hasOne(SkinColor::class);
    }

    /**
     * Relationship: A character has one value of hairColor
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hairColor()
    {
        return $this->hasOne(HairColor::class);
    }

    /**
     * Function to get only humans characters.
     * @param $pageNumber
     * @return array
     */
    public static function getAllHumanCharacters($pageNumber)
    {
        //search all human characters on each page:
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

        //check if character already exists on DB and insert if not:
        return Character::insertCharacters($humans);
    }

    /**
     * Function that inserts characters on DB
     * @param $humans
     * @return string
     */
    private static function insertCharacters($humans)
    {
        foreach ($humans as $human) {
            //user id in Swapi:
            $matches = [];
            preg_match('/\d+/', $human['url'], $matches);
            $humanSwapId = intval($matches[0]);

            $humanSwapIdsFromTable = Character::all()->pluck('characterIdInSwapi')->toArray();

            // check skinColor
            $skinColors = SkinColor::all()->pluck('skinName')->toArray();
            if(!in_array($human['skin_color'], $skinColors)) {
                $skinColor = new SkinColor();
                $skinColor->skinName = $human['skin_color'];
                $skinColor->save();
            }
            $skinColorId = SkinColor::where('skinName', $human['skin_color'])->pluck('skinId')->first();

            // check hairColor
            $hairColors = HairColor::all()->pluck('colorName')->toArray();
            if(!in_array($human['hair_color'], $hairColors)) {
                $hairColor = new HairColor();
                $hairColor->colorName = $human['hair_color'];
                $hairColor->save();
            }
            $hairColorId = HairColor::where('colorName', $human['hair_color'])->pluck('hairId')->first();

            // if user doesnt exist, insert on DB:
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

    /**
     * Function to search characters with name similar to a given string.
     * @param $word
     * @return mixed
     */
    public static function searchingCharacters($word)
    {
        return Character::where('characterName', 'like', '%'.$word.'%')->get();
    }

    public static function searchingCharactersBySkinHair($skinId, $hairId)
    {
        return Character::where(['skinId' => $skinId, 'hairId' => $hairId])->get();
    }
}
