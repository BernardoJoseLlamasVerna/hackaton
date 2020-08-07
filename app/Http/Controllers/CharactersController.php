<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharactersController extends Controller
{
    /**
     * @return mixed
     */
    public function getAllCharacters()
    {
        $numberOfCharacters = json_decode(file_get_contents('https://swapi.dev/api/people/'), true)['count'];
        $pageNumber = ceil($numberOfCharacters/10);

        $humans = Character::getAllHumanCharacters($pageNumber);

        print_r($humans);
        die();

        return $humans;
    }

    /**
     * @param $randomString
     * @return Character[]|\Illuminate\Database\Eloquent\Collection
     */
    public function searchCharactersByName()
    {
        $randomString = 'Darth Vader';

        $characters = Character::all();

        return $characters = Character::searchingCharacters($characters, $randomString);
    }
}
