<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\GameEngine as Game;

class GameController extends Controller
{
    public function home()
    {
        $game = new Game();

        if ( ! $game->inProgress()) {
            $game->reset();
        }

        if ($game->gameOver()) {
            return redirect()->route('gameOver');
        }

        // @TODO - refactor how grid data is passed to and rendered on front end (make use of a Strategy pattern on the grid object, for example).
        return view('home')->with('grid', $game->getGrid());
    }

    public function gameOver()
    {
        $game = new Game();

        /**
         * Redirect back to home page if game is still in play (prevents user dirctly accessing the gamover page).
         * @TODO - use a custom middleware to intercept this automatically.
         */
        if ( ! $game->gameOver()) {
            return redirect()->route('home');
        }

        return view('gameover')->with('shotsFired', $game->countShots());
    }

    public function reset()
    {
        $game = new Game();
        $game->reset();
        return redirect()->route('home');
    }

    public function command(Request $request)
    {
        $game = new Game();
        $command = $request->input('command');

        if (strtolower(trim($command)) === 'show') {
            $game->toggleShowVessels();
        }

        // @TODO - refactor this!
        if (preg_match('/^[a-j]{1}([1-9]{1}|10)$/i', $command)) {
            $game->takeShot($command);
        }

        return redirect()->route('home');
    }
}
