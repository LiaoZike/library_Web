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
    //盤點結果

    $router->get('/inventory', 'InventoryController@default')->name('inventory.default');

    $router->get('/inventory/{timesname}', 'InventoryController@floor')->name('inventory.floor');
    $router->get('/inventory/{timesname}/{floorid}', 'InventoryController@floormap')->name('inventory.floormap');
    $router->get('/inventory/{timesname}/bookcase/{floormapid}', 'InventoryController@bookcase')->name('inventory.bookcase');
    $router->get('/inventory/end/{timesname}/{floor}/{floormap}/{caseno}/{gotopid}', 'InventoryController@end')->name('inventory.end');

    //書本
    $router->get('/inventory/small/{timesname}/{results_id}/{DBbooksID}', 'InventoryController@small')->name('inventory.small');
    $router->patch('/inventory/small/{results_id}/{DBbooksID}/{ishere}', 'InventoryController@smallPatch')->name('inventory.smallPatch');


//    $router->get('/librarymap', 'LibraryMapController@index')->name('librarymap');
    //樓層編輯
    $router->get('/librarymap/floor', 'LibraryMapController@floor')->name('librarymap.floor'); //index
    $router->get('/librarymap/floor/edit', 'LibraryMapController@flooredit')->name('librarymap.flooredit');
    $router->post('/librarymap/floor/edit', 'LibraryMapController@flooreditsave')->name('librarymap.flooreditsave');

    ///樓層平面圖編輯
    $router->get('/librarymap/floormap/{floorid}', 'LibraryMapController@floormap')->name('librarymap.floormap');
    $router->get('/librarymap/floormap/edit/{floorid}', 'LibraryMapController@floormapedit')->name('librarymap.floormapedit');
    $router->post('/librarymap/floormap/edit/{floorid}', 'LibraryMapController@floormapeditsave')->name('librarymap.floormapeditsave');

    ///書櫃詳細編輯
    $router->get('/librarymap/bookcase/{floormapid}', 'LibraryMapController@bookcaseedit')->name('librarymap.bookcaseedit');
    $router->post('/librarymap/bookcase/{floormapid}', 'LibraryMapController@bookcaseeditsave')->name('librarymap.bookcaseeditsave');


});
