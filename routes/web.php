<?php

    Route::get('/', 'HomeController@index')->name('index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/enderecos/{listId}', 'HomeController@enderecos')->name('enderecos');
    Route::get('/exportar', 'HomeController@exportar')->name('exportar');
