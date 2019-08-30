<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27 0027
 * 订单
 * Time: 15:20
 */
namespace app\admin\controller;

use app\data\model\User;
use think\Controller;
use think\Request;
use think\Validate;
use think\captcha\Captcha;

class Login extends Controller
{
    /**
     * 后台登录
     * @return array
     * @throws \think\exception\DbException
     * @author fyk
     */
    public function login()
    {
        $request = Request::instance();
        $data = $request->param();
        // 验证
        $rules = [
            'nickname' => 'require',
            'password' => 'alphaNum|require|length:6,16',
//            'captcha|验证码'=>'require|captcha'
        ];
        $msg = [
            'nickname.require' => '请输入账号',
            'password.require' => '密码不能为空',
            'password.length' => '密码长度必须在6~16位之间'
        ];
        $valid = new Validate($rules, $msg);
        if (!$valid->check($data)) {
            return json(['code' => 0,'msg' => $valid->getError()]);
        }
        // 查询
        $user = db('user') ->where(['nickname'=>$data['nickname'],'type'=>0,'status'=>1]) ->find();
        //pp($user);die;
        if (!$user) {
            // 手机号不存在
            return json(['code'=>'0','msg'=>'账号不存在或已被禁用请联系管理员处理']);
        }else {
            // 判断密码是否正确
            if ($user['password'] == md5(md5($data['password']).$user['salt'])) {
                db('user') ->where('id',$user['id']) ->update(['update_time'=>time()]);
                $list['user_id'] = $user['id'];
                $list['nickname'] = $user['nickname'];
                return json(['code'=>1,'msg'=>'登录成功','data'=>$list]);
            }else {
                return json(['code'=>0,'msg'=>'密码错误']);
            }
        }
    }
    /**
     * 注册后台用户
     * @return array
     * @throws \think\exception\DbException
     * @author fyk
     */
    public function register(){
        $request = Request::instance();
        $param = $request->param();

        $rules = [
            'user_id' => 'require',
            'nickname' => 'require|unique:user',
            'username' => 'require',
            'password'=>'require|alphaNum|confirm|length:6,16',
            'phone' => 'require|regex:\d{11}|unique:user',
            'status'=>'require',
            'power' => 'require',
        ];
        $message = [
            'nickname.require'=>'账号不能为空',
            'nickname.unique' => '账号已存在',
            'username.require'=>'姓名不能为空',
            'password.require'=>'密码不能为空',
            'password.length' => '密码长度必须在6~16位之间',
            'password.confirm' => '两次密码输入不一致',
            'phone.require' => '请输入手机号',
            'phone.regex' => '手机号格式不正确',
            'phone.unique' => '手机号已存在',
            'status.require'=>'状态不能为空',
            'power.require'=>'角色不能为空',
        ];
        $validate = new Validate($rules,$message);
        if(!$validate->check($param)){
            return json(['code' => 0,'msg' => $validate->getError()]);
        }
        $user = new User();
        $data = $user  -> where('id',$param['user_id']) -> field('power')->find();

        if($data['power'] === 0){
            return json(['code' => 0,'msg' => '您没有此权限']);
        }

        $salt = substr(uniqid(rand()),-6);
        $password = md5(md5($param['password']).$salt);
        // 储存

        $result = $user->add($param['nickname'],$param['username'],$param['phone'],$password,$salt,'','',$param['status'],0,'','',$param['power']);

        $res = $result ? ['code' => 1,'msg' => '注册成功'] : ['code' => 0,'msg' => '注册失败'];

        return json($res);
    }

    /**
     * 注册前台用户
     * @return array
     * @throws \think\exception\DbException
     * @author fyk
     */
    public function reception(){
        $request = Request::instance();
        $param = $request->param();

        $rules = [
            'user_id' => 'require',
            'phone' => 'require|regex:\d{11}|unique:user',
            'username' => 'require',
            'password'=>'require|alphaNum|confirm|length:6,16',
            'status'=>'require',
        ];
        $message = [
            'phone.require' => '请输入手机号',
            'phone.regex' => '手机号格式不正确',
            'phone.unique' => '手机号已存在',
            'username.require'=>'姓名不能为空',
            'password.require'=>'密码不能为空',
            'password.length' => '密码长度必须在6~16位之间',
            'password.confirm' => '两次密码输入不一致',
            'status.require'=>'状态不能为空',
        ];
        $validate = new Validate($rules,$message);
        if(!$validate->check($param)){
            return json(['code' => 0,'msg' => $validate->getError()]);
        }
        $user = new User();
        $data = $user  -> where('id',$param['user_id']) -> field('power')->find();

        if($data['power'] === 0){
            return json(['code' => 0,'msg' => '您没有此权限']);
        }
        $salt = substr(uniqid(rand()),-6);
        $password = md5(md5($param['password']).$salt);
        // 储存

        $result = $user->add('',$param['username'],$param['phone'],$password,$salt,'','',$param['status'],1,'','',0);

        $res = $result ? ['code' => 1,'msg' => '注册成功'] : ['code' => 0,'msg' => '注册失败'];

        return json($res);
    }
    /**
     * 验证码
     * @return array
     * @throws \think\exception\DbException
     * @author fyk
     */
    public function code(){
        //引用
        $captcha = new Captcha();
        $captcha->fontSize = 30;
        $captcha->length   = 4;
        $captcha->useNoise = false;
        return $captcha->entry();
    }


}