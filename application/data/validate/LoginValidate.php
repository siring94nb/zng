<?php

namespace app\data\validate;

use think\Validate;

class LoginValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'phone' => 'mobile|require|regex:\d{11}|unique:user',
        'code' => 'require|regex:\d{6}',
        'openid' => 'require|unique:user',
        'realname' => 'require|length:2,8|chsAlphaNum|unique:user'
        // 'password' => 'alphaNum|require|length:6,16|confirm:repassword'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'phone.mobile' => '手机号格式不正确',
        'phone.require' => '手机号不能为空',
        'phone.regex' => '手机号格式不正确',
        'phone.unique' => '手机号已注册',
        'code.require' => '验证码不能为空',
        'code.regex' => '验证码格式不正确',
        'openid.require' => 'openid为空',
        'openid.unique' => 'openid已注册',
        'realname.length' => '请输入2~8位名称',
        'realname.require' => '名称不能为空',
        'realname.chsAlphaNum' => '名称只能为中文或子母',
        'realname.unique' => '名称已注册',
    ];
}
