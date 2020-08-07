<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\HairColor;
use App\Models\SkinColor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CharactersController extends Controller
{
    /**
     * Get all characters from SWAPI and Refresh DB if there are new characters.
     * @return mixed
     */
    public function getAllCharactersAndRefresh()
    {
        $numberOfCharacters = json_decode(file_get_contents('https://swapi.dev/api/people/'), true)['count'];
        $pageNumber = ceil($numberOfCharacters/10);

        $humans = Character::getAllHumanCharacters($pageNumber);

        return $humans;
    }

    /**
     * Given a string, return character(s) with similar name.
     * @param Request $request
     * @return Character[]|\Illuminate\Database\Eloquent\Collection
     */
    public function searchCharacters(Request $request)
    {
        $characters = [];

        if($request->get('randomString')) {
            $characters = Character::searchingCharacters($request->get('randomString'));
        }

        $skins = SkinColor::all();
        $hairs = HairColor::all();

        if($request->get('skin') && $request->get('hair')) {
            $characters = Character::searchingCharactersBySkinHair($request->get('skin'), $request->get('hair'));
        }

        return view('welcome')->with([
            'characters' => $characters,
            'skins' => $skins,
            'hairs' => $hairs,
        ]);
    }
}
