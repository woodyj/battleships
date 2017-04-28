<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\GameFacade as Game;
use App\Classes\GridAsNumericArrayStrategy;
use App\Classes\GridCoordinate;

class GameController extends Controller
{
    public function home(string $damageReport = '')
    {
        if ( ! Game::inProgress()) {
            Game::reset();
        }

        if (Game::over()) {
            return redirect()->route('gameOver');
        }

        $gridStatuses = GridAsNumericArrayStrategy::get(Game::getGrid());
        return view('home')->with('gridStatuses', $gridStatuses)->with('damageReport', $damageReport);
    }

    public function gameOver()
    {
        /**
         * Redirect back to home page if game is still in play (prevents user dirctly accessing the gamover page).
         * @todo - using a custom middleware to intercept this automatically might be nice, although would be harder to spot for maintenance teams.
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

    /**
     * @todo - refactor to use custom Validator classes (or at least base the regular expression on dynamic grid dimensions).
     */
    public function command(Request $request)
    {
        $command = $request->input('command');
        $damageReport = '';

        if (strtolower(trim($command)) === 'show') {
            Game::toggleShowVessels();
        }

        /**
         * @todo - refactor this!
        */
        if (preg_match('/^[a-j]{1}([1-9]{1}|10)$/i', $command)) {
            list($x, $y) = explode(':', GridCoordinate::translateAlphaGridCoordinate($command));
            $damageReport = Game::takeShot($x, $y);
            
            if ($damageReport) {
                return redirect()->route('damage', ['damage' => $damageReport]);
            }
        }

        return redirect()->route('home');
    }
}
