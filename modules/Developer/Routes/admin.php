<?php
use Illuminate\Routing\Router;

// Developer 模块后台路由
$router->group(['prefix' =>'developer','module'=>'developer'], function (Router $router) {
    
    // 首页
    $router->get('index', 'IndexController@index')->name('developer.index')->middleware('allow:developer.index');

    // module 开发
    $router->group(['prefix' =>'/module','middleware'=>'allow:developer.module'], function (Router $router) {
        $router->get('index','ModuleController@index')->name('developer.module.index');
        $router->get('create','ModuleController@create')->name('developer.module.create');
        $router->post('store','ModuleController@store')->name('developer.module.store');
        $router->get('show/{name}','ModuleController@show')->name('developer.module.show');
        $router->post('update/{name}/{field}','ModuleController@update')->name('developer.module.update');
    });

    // module/controller 开发
    $router->group(['prefix' =>'module/controller','middleware'=>'allow:developer.module.controller'], function (Router $router) {
        $router->get('index/{name}/{type}','ControllerController@index')->name('developer.module.controller');
        $router->any('create/{name}/{type}','ControllerController@create')->name('developer.module.controller.create');
        $router->any('tempate/{name}/{type}/{controller}','ControllerController@template')->name('developer.module.controller.template');
        $router->any('route/{name}/{type}/{controller}','ControllerController@route')->name('developer.module.controller.route');
    });

    // command group example
    $router->group(['prefix' =>'module/command'], function (Router $router) {
        $router->get('index/{module}','CommandController@index')->name('developer.command.index')->middleware('allow:developer.command.index');
        $router->any('create/{module}','CommandController@create')->name('developer.command.create')->middleware('allow:developer.command.create');
    });
    
});
