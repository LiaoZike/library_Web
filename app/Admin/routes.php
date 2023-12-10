<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('/librarymap', 'LibraryMapController@index')->name('librarymap');
    //樓層編輯
    $router->get('/librarymap/floor', 'LibraryMapController@floor')->name('librarymap.floor');
    $router->post('/librarymap/floor', 'LibraryMapController@floorSave')->name('librarymap.floorSave');

    ///樓層平面圖編輯
    $router->get('/librarymap/map', 'LibraryMapController@map')->name('librarymap.map');
    $router->get('/librarymap/map/{floorid}', 'LibraryMapController@editmap')->name('librarymap.editmap');
    $router->post('/librarymap/map/{floorid}', 'LibraryMapController@editmapsave')->name('librarymap.editmap');


});
