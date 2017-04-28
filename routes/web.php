<?php

Route::get('/', 'GameController@home')->name('home');
Route::get('/damage/{damageReport}', 'GameController@home')->name('damage');
Route::get('/reset', 'GameController@reset')->name('reset');
Route::post('/command', 'GameController@command')->name('command');
Route::get('/gameover', 'GameController@gameOver')->name('gameOver');