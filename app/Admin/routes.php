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
    $router->get('/librarymap/floor', 'LibraryMapController@floor')->name('librarymap.floor'); //index
    $router->get('/librarymap/floor/edit', 'LibraryMapController@flooredit')->name('librarymap.flooredit');
    $router->post('/librarymap/floor/edit', 'LibraryMapController@flooreditsave')->name('librarymap.flooreditsave');

    ///樓層平面圖編輯
    $router->get('/librarymap/floormap/{floorid}', 'LibraryMapController@floormap')->name('librarymap.floormap');
    $router->get('/librarymap/floormap/edit/{floorid}', 'LibraryMapController@floormapedit')->name('librarymap.floormapedit');
    $router->post('/librarymap/floormap/edit/{floorid}', 'LibraryMapController@floormapeditsave')->name('librarymap.floormapeditsave');


});
