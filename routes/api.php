<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix'=>'user'], function () {
   /*
    * 登录
    */
    Route::post('/login', \App\Http\Controllers\Api\LoginController::class . '@login');

    /*
     * 登出
     */
    Route::post('/logout', \App\Http\Controllers\Api\LoginController::class . '@logout')->middleware(['auth:api']);


    /*
     * 验证
     */
    Route::get('/check-login', \App\Http\Controllers\Api\LoginController::class . '@checkLogin')->middleware(['auth:api']);


    Route::get('/config', \App\Http\Controllers\Api\ConfigurationController::class . '@menu')->middleware(['auth:api']);


    /**
     * 检测页面是否访问
     */
    Route::post('/check-page', \App\Http\Controllers\Api\LoginController::class . '@checkInPage')->middleware(['auth:api']);

    /*
     * 角色管理初始值
     */
    Route::get('/set-role', \App\Http\Controllers\Api\ConfigurationController::class . '@roleDefault');

    /*
     * 用户管理初始值
     */
    Route::get('/choose-role', \App\Http\Controllers\Api\ConfigurationController::class . '@userRoles');


    /**
     * 数据表信息
     */
    Route::get('/tables', \App\Http\Controllers\Api\ConfigurationController::class . '@showTable');

    /**
     * 给用户和角色设置关系
     */
    Route::post('/user-role', \App\Http\Controllers\Api\ConfigurationController::class . '@setUserRole')->middleware(['node', 'auth:api']);
    /**
     * 角色编辑
     */
    Route::post('/edit-role', \App\Http\Controllers\Api\ConfigurationController::class . '@editUserRole')->middleware(['node', 'auth:api']);

    /*
     * todoList
     */
    Route::resource('/doto-list', \App\Http\Controllers\Api\TodoListController::class);

});






/*
 * 系统配置
 */
Route::group(['prefix'=>'system'], function(){
    /**
     * 测试路由
     */
    Route::get('/test', \App\Http\Controllers\Api\TestController::class . '@test');

    /**
     * 菜单管理
     */
    Route::resource('/menu', \App\Http\Controllers\Api\SystemConfig\ResourceController::class);

    /**
     * 角色管理
     */
    Route::resource('/role', \App\Http\Controllers\Api\SystemConfig\RoleManagementController::class)->middleware(['node', 'auth:api']);

    /**
     * 用户管理
     */
    Route::resource('/user', \App\Http\Controllers\Api\SystemConfig\UserController::class)->middleware(['node', 'auth:api']);


    Route::get('/default-menu', \App\Http\Controllers\Api\SystemConfig\RoleManagement::class . '@menu');

  });

/**
 * 上传
 */
Route::group(['prefix'=>'upload', 'middleware' => ['node', 'auth:api']], function () {
   Route::post('/dist', \App\Http\Controllers\Api\UploadController::class . '@dist');
});

/**
 * dist
 */
Route::group(['prefix'=>'dist', 'middleware' => ['node', 'auth:api']], function () {
    Route::get('/list', \App\Http\Controllers\Api\UploadController::class . '@index');

    /**
     * 回滚
     */
    Route::post('/back', \App\Http\Controllers\Api\UploadController::class . '@back');
    Route::post('/delete', \App\Http\Controllers\Api\UploadController::class . '@delete');
});

/**
 * 日志
 */
Route::group(['prefix'=>'log', 'middleware' => ['node', 'auth:api']], function () {
    Route::get('/user', \App\Http\Controllers\Api\LogController::class . '@user');
    Route::get('/operation', \App\Http\Controllers\Api\LogController::class . '@operation');
});



Route::group(['prefix'=>'re'], function () {
//    , 'middleware' => 'auth:api'
    Route::resource('/menu', \App\Http\Controllers\Api\Re\ResourceController::class)->middleware(['node', 'auth:api']);
    Route::resource('/node', \App\Http\Controllers\Api\Re\ResourceNodeController::class)->middleware(['node', 'auth:api']);
    Route::get('menu-default', \App\Http\Controllers\Api\ConfigurationController::class . '@menuDefault');
//    Route::get('menu-default', \App\Http\Controllers\Api\Re\ResourceController::class . 'menuDefault');
});

/**
 * node 接口
 */
Route::group(['prefix'=>'node'], function () {
    Route::get('/user', \App\Http\Controllers\Node\LoginController::class . '@login');
    Route::get('/user-list', \App\Http\Controllers\Node\LoginController::class . '@userList');
    Route::post('/regist', \App\Http\Controllers\Node\LoginController::class . '@regist');
    Route::post('/login', \App\Http\Controllers\Node\LoginController::class . '@login');

    /**
     *  弹幕
     */
    Route::get('/barrage-list', \App\Http\Controllers\Node\BarrageController::class . '@index');
});




Route::group(['prefix'=>'book'], function () {
    Route::resource('/user', \App\Http\Controllers\Book\BookController::class)->middleware(['auth:api']);


    Route::post('flow', \App\Http\Controllers\Book\BookFlowController::class . '@createFlow');
    Route::get('flow', \App\Http\Controllers\Book\BookFlowController::class . '@listFlow');

    Route::post('flow-node', \App\Http\Controllers\Book\BookFlowController::class . '@createFlowNode');
    Route::get('flow-node', \App\Http\Controllers\Book\BookFlowController::class . '@listFlowNode');
});

Route::get('/test', \App\Http\Controllers\Api\TestController::class . '@test');







