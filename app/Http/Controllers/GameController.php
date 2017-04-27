<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\GameFacade as Game;

class GameController extends Controller
{
    public function home()
    {
        if ( ! Game::inProgress()) {
            $game->reset();
        }

        if (Game::over()) {
            return redirect()->route('gameOver');
        }

        // @TODO - refactor how grid data is passed to and rendered on the front end (make use of a Strategy pattern on the grid object, for example).
        return view('home')->with('grid', Game::getGrid());
    }

    public function gameOver()
    {
        /**
         * Redirect back to home page if game is still in play (prevents user dirctly accessing the gamover page).
         * @TODO - using a custom middleware to intercept this automatically might be nice, although would be harder to spot for maintenance teams.
         */
        if ( ! Game::over()) {
            return redirect()->route('home');
        }

        return view('gameover')->with('shotsFired', Game::countShots());
    }

    public function reset()
    {
        Game::reset();
        return redirect()->route('home');
    }

    public function command(Request $request)
    {
        $command = $request->input('command');

        if (strtolower(trim($command)) === 'show') {
            Game::toggleShowVessels();
        }

        // @TODO - refactor this!
        if (preg_match('/^[a-j]{1}([1-9]{1}|10)$/i', $command)) {
            Game::takeShot($command);
        }

        return redirect()->route('home');
    }
}
