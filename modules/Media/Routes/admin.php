<?php
use Illuminate\Routing\Router;

// Media 模块后台路由
$router->group(['prefix'=>'media', 'module'=>'media'], function (Router $router) {
    
    // 首页
    $router->get('index/{folder_id?}/{type?}', 'MediaController@index')->name('media.index');
    $router->any('operate', 'MediaController@operate')->name('media.operate');
    $router->any('select/uploaded','MediaController@selectFromUploaded')->name('media.select.uploaded');
    $router->any('select/library/{folder?}','MediaController@selectFromLibrary')->name('media.select.library');
    $router->any('select/dir','MediaController@selectFromDir')->name('media.select.dir');

    // 文件夹
    $router->group(['prefix' =>'folder'], function (Router $router) {
        $router->any('create/{parent_id?}','FolderController@create')->name('media.folder.create')->middleware('allow:media.folder.create');
        $router->any('rename/{id}','FolderController@rename')->name('media.folder.rename')->middleware('allow:media.folder.rename');
        $router->any('delete/{id}','FolderController@delete')->name('media.folder.delete')->middleware('allow:media.folder.delete');
        $router->any('move/{id}','FolderController@move')->name('media.folder.move')->middleware('allow:media.folder.move');
        $router->any('select/{id?}','FolderController@select')->name('media.folder.select');
    });

    // 文件管理
    $router->group(['prefix' =>'file'], function (Router $router) {
        $router->any('move/{id}','FileController@move')->name('media.file.move')->middleware('allow:media.file.move');
        $router->any('delete/{id}','FileController@delete')->name('media.file.delete')->middleware('allow:media.file.delete');
        $router->any('rename/{id}','FileController@rename')->name('media.file.rename')->middleware('allow:media.file.rename');
        $router->any('move/{id}','FileController@move')->name('media.file.move')->middleware('allow:media.file.move');
        $router->any('upload/{type?}','FileController@upload')->name('media.file.upload')->middleware('allow:media.file.upload');
    });


});
