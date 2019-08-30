<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];

use think\Route;

//后台路由
Route::group("admin",[
    "login" => "admin/Login/login",//登录
    "register" => "admin/Login/register",//注册
//    "forget" => "admin/Login/forget",//忘记密码
//    "change_password" =>"admin/Login/change_password",//忘记密码修改
    "reception" =>"admin/Login/reception",//前台账号添加

    "user_list" =>"admin/User/user_list",//后台账号显示
    "user_edit" =>"admin/User/user_edit",//后台账号编辑
    "user_del" =>"admin/User/user_del",//后台账号删除
    "user_status" =>"admin/User/user_status",//账号状态修改
    "user_api_edit" =>"admin/User/user_api_edit",//前台用户修改
    "user_admin_edit" =>"admin/User/user_admin_edit",//后台用户修改

    "enterprise_index" =>"admin/Enterprise/enterprise_index",//后台企业介绍
    "enterprise_edit" =>"admin/Enterprise/enterprise_edit",//后台企业介绍

    "product_list" =>"admin/Product/product_list" ,//商品列表
    "product_add" =>"admin/Product/product_add" ,//商品新增
    "product_edit" =>"admin/Product/product_edit" ,//商品修改
    "product_edit_index" =>"admin/Product/product_edit_index" ,//商品编辑显示


    "file_img" =>"admin/File/image" ,//图片上传
    "file_video" =>"admin/File/video" ,//视频上传

]);

//前台路由
Route::group("api",[
    "login" => "api/Login/login",//登录
    "register" => "api/Login/register",//注册
    "forget" => "api/Login/forget",//忘记密码
    "change_password" =>"api/Login/change_password"//忘记密码修改

]);
