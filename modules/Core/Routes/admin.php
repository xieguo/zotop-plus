<?php
use Illuminate\Routing\Router;

// Core 模块后台路由
$router->group(['prefix' =>'/', 'module'=>'core'], function (Router $router) {

    // 首页
    $router->get('/','IndexController@index')->name('admin.index');

    // 登录
    $router->get('login', 'AuthController@showLoginForm')->name('admin.login');

    // 登录POST
    $router->post('login', 'AuthController@login')->name('admin.login.post');

    // 登出
    $router->any('logout', 'AuthController@logout')->name('admin.logout');

});

// Core 模块后台路由
$router->group(['prefix' =>'core', 'module'=>'core'], function (Router $router) {

    // 个人管理
    $router->group(['prefix' =>'mine'], function (Router $router) {
       $router->get('edit','MineController@edit')->name('core.mine.edit')->middleware('allow:core.mine.edit');
       $router->put('update','MineController@update')->name('core.mine.update')->middleware('allow:core.mine.edit');
       $router->get('password','MineController@password')->name('core.mine.password')->middleware('allow:core.mine.password');
       $router->put('password_update','MineController@updatePassword')->name('core.mine.password.update')->middleware('allow:core.mine.password');
       $router->get('permission','MineController@permission')->name('core.mine.permission')->middleware('allow:core.mine.permission');
       $router->get('log','MineController@log')->name('core.mine.log')->middleware('allow:core.mine.log');
    });

    // 管理员管理
    $router->group(['prefix' =>'administrator'], function (Router $router) {
       $router->get('index','AdministratorController@index')->name('core.administrator.index')->middleware('allow:core.administrator.index');
       $router->get('create','AdministratorController@create')->name('core.administrator.create')->middleware('allow:core.administrator.create');
       $router->post('store','AdministratorController@store')->name('core.administrator.store')->middleware('allow:core.administrator.create');
       $router->get('edit/{id}','AdministratorController@edit')->name('core.administrator.edit')->middleware('allow:core.administrator.edit');
       $router->put('update/{id}','AdministratorController@update')->name('core.administrator.update')->middleware('allow:core.administrator.edit');
       $router->post('status/{id}','AdministratorController@status')->name('core.administrator.status')->middleware('allow:core.administrator.status');
       $router->delete('destroy/{id}','AdministratorController@destroy')->name('core.administrator.destroy')->middleware('allow:core.administrator.destroy');
    });

    // role
    $router->group(['prefix' =>'role'], function (Router $router) {
        $router->get('index','RoleController@index')->name('core.role.index')->middleware('allow:core.role.index');
        $router->get('create','RoleController@create')->name('core.role.create')->middleware('allow:core.role.create');
        $router->post('store','RoleController@store')->name('core.role.store')->middleware('allow:core.role.create');
        $router->get('edit/{id}','RoleController@edit')->name('core.role.edit')->middleware('allow:core.role.edit');
        $router->put('update/{id}','RoleController@update')->name('core.role.update')->middleware('allow:core.role.edit');
        $router->post('status/{id}','RoleController@status')->name('core.role.status')->middleware('allow:core.role.status');
        $router->delete('destroy/{id}','RoleController@destroy')->name('core.role.destroy')->middleware('allow:core.role.destroy');
    });

    // 系统设置
    $router->group(['prefix' =>'config'], function (Router $router) {
        $router->any('index','ConfigController@index')->name('core.config.index');
        $router->any('upload','ConfigController@upload')->name('core.config.upload')->middleware('allow:core.config.upload');
        $router->any('watermark/test','ConfigController@watermarktest')->name('core.config.watermarktest');
        $router->any('mail','ConfigController@mail')->name('core.config.mail')->middleware('allow:core.config.mail');
        $router->any('mail/test','ConfigController@mailtest')->name('core.config.mailtest')->middleware('allow:core.config.mail');
        $router->any('safe','ConfigController@safe')->name('core.config.safe')->middleware('allow:core.config.safe');
        $router->any('locale','ConfigController@locale')->name('core.config.locale')->middleware('allow:core.config.locale');
    });

    // 系统功能
    $router->group(['prefix' =>'system'], function (Router $router) {
        $router->any('reboot','SystemController@reboot')->name('core.system.reboot');
        $router->get('environment','SystemController@environment')->name('core.system.environment');
        $router->get('about','SystemController@about')->name('core.system.about');
    });

    // 模块管理
    $router->group(['prefix' =>'module'], function (Router $router) {
        $router->get('index','ModuleController@index')->name('core.module.index')->middleware('allow:core.module.index');
        $router->post('enable/{name}','ModuleController@enable')->name('core.module.enable')->middleware('allow:core.module.status');
        $router->post('disable/{name}','ModuleController@disable')->name('core.module.disable')->middleware('allow:core.module.status');
        $router->post('install/{name}','ModuleController@install')->name('core.module.install')->middleware('allow:core.module.install');
        $router->post('uninstall/{name}','ModuleController@uninstall')->name('core.module.uninstall')->middleware('allow:core.module.uninstall');
        $router->post('delete/{name}','ModuleController@delete')->name('core.module.delete')->middleware('allow:core.module.delete');
        $router->any('publish/{name?}','ModuleController@publish')->name('core.module.publish')->middleware('allow:core.module.publish');
        $router->any('upload','ModuleController@upload')->name('core.module.upload')->middleware('allow:core.module.upload');      
    });

    // 主题管理
    $router->group(['prefix' =>'theme'], function (Router $router) {
        $router->get('index/{type?}','ThemeController@index')->name('core.theme.index')->middleware('allow:core.theme.index');
        $router->get('files/{theme?}','ThemeController@files')->name('core.theme.files')->middleware('allow:core.theme.files');
        $router->any('publish/{theme?}','ThemeController@publish')->name('core.theme.publish')->middleware('allow:core.theme.publish');
        $router->any('upload','ThemeController@upload')->name('core.theme.upload')->middleware('allow:core.theme.upload');
    });

    // 文件管理
    $router->group(['prefix' =>'file'], function (Router $router) {
        $router->any('editor','FileController@editor')->name('core.file.editor')->middleware('allow:core.file.editor');
        $router->any('create','FileController@create')->name('core.file.create')->middleware('allow:core.file.create');
        $router->any('delete','FileController@delete')->name('core.file.delete')->middleware('allow:core.file.delete');
        $router->any('copy','FileController@copy')->name('core.file.copy')->middleware('allow:core.file.copy');
        $router->any('rename','FileController@rename')->name('core.file.rename')->middleware('allow:core.file.rename');
        $router->any('upload','FileController@upload')->name('core.file.upload')->middleware('allow:core.file.upload');
        $router->any('select','FileController@select')->name('core.file.select')->middleware('allow:core.file.select');
    });

    // 文件夹
    $router->group(['prefix' =>'folder'], function (Router $router) {
        $router->any('create','FolderController@create')->name('core.folder.create')->middleware('allow:core.folder.create');
        $router->any('delete','FolderController@delete')->name('core.folder.delete')->middleware('allow:core.folder.delete');
        $router->any('rename','FolderController@rename')->name('core.folder.rename')->middleware('allow:core.folder.rename');
    });

    // scheduling group example
    $router->group(['prefix' =>'scheduling'], function (Router $router) {
        $router->get('index','SchedulingController@index')->name('core.scheduling.index')->middleware('allow:core.scheduling.index');
        $router->any('run/{index}','SchedulingController@run')->name('core.scheduling.run')->middleware('allow:core.scheduling.run');
    });
});
