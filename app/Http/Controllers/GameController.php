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
        } else {
            print 'game in progress';
        }

        // var_dump($game->getGrid());

        return view('home')->with('grid', $game->getGrid());
    }

    public function reset()
    {
        $game = new Game();
        $game->reset();
        // return redirect()->route('home');
    }

    public function command(Request $request)
    {
        $game = new Game();

        // $this->validate($request, [
        //     'command' => 'required|'
        // ]);

        // var_dump($command);

        // validate the command!
        $command = $request->input('command');

        // @TODO - refactor this!
        if (preg_match('/^[a-j]{1}([1-9]{1}|10)$/i', $command)) {
            // print "<p>{$command}</p>";
            // var_dump($game->takeShot($command));
            $game->takeShot($command);
            // exit;
        }


        // process command form submission
        // do something based on submitted command
        return redirect()->route('home');
    }
}
