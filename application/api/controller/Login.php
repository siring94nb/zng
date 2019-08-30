<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/28
 * 登录注册
 * Time: 15:20
 */
namespace app\api\controller;

use app\data\model\User;
use think\Controller;
use think\Request;
use think\Validate;
use think\captcha\Captcha;


class Login extends Controller
{
    /**
     * 登录
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
            'phone' => 'require|regex:\d{11}',
            'password' => 'alphaNum|require|length:6,16',
//            'captcha|验证码'=>'require|captcha'
        ];
        $msg = [
            'phone.require' => '请输入手机号',
            'phone.regex' => '手机号格式不正确',
            'password.require' => '密码不能为空',
            'password.length' => '密码长度必须在6~16位之间'
        ];
        $valid = new Validate($rules, $msg);
        if (!$valid->check($data)) {
            return json(['code' => 0,'msg' => $valid->getError()]);
        }
        // 查询
        $user = db('user') ->where('phone',$data['phone']) ->find();
        //pp($user);die;
        if (!$user) {
            // 手机号不存在
            return json(['code'=>0,'msg'=>'账号或手机号不存在, 请先注册']);
        }else {
            // 判断密码是否正确
            if ($user['password'] == md5(md5($data['password']).$user['salt'])) {
                return json(['code'=>1,'msg'=>'登录成功','user_id'=>$user['id']]);
            }else {
                return json(['code'=>0,'msg'=>'密码错误']);
            }
        }
    }
    /**
     * 注册
     * @return array
     * @throws \think\exception\DbException
     * @author fyk
     */
    public function register(){
        $request = Request::instance();
        $param = $request->param();

        $rules = [
            'phone' => 'require|regex:\d{11}|unique:user',
            'password'=>'require|alphaNum|confirm|length:6,16',
            'problem'=>'require',
            'answer'=>'require',
        ];
        $message = [
            'phone.require' => '请输入手机号',
            'phone.regex' => '手机号格式不正确',
            'phone.unique' => '手机号已注册',
            'password.require'=>'密码不能为空',
            'password.length' => '密码长度必须在6~16位之间',
            'password.confirm' => '两次密码输入不一致',
            'problem.require'=>'问题不能为空',
            'answer.require'=>'答案不能为空',
        ];
        $validate = new Validate($rules,$message);
        if(!$validate->check($param)){
            return json(['code' => 0,'msg' => $validate->getError()]);
        }
        $salt = substr(uniqid(rand()),-6);
        $password = md5(md5($param['password']).$salt);
//        pp($param);die;
        // 储存
        $user = new User();

        $result = $user->add('','',$param['phone'],$password,$salt,$param['problem'],$param['answer'],1,'','','','');

        $res = $result ? ['code' => 1,'msg' => '注册成功'] : ['code' => 0,'msg' => '注册失败'];

        return json($res);
    }

    /**
     * 忘记密码
     * @return array
     * @throws \think\exception\DbException
     * @author fyk
     */
    public function forget()
    {
        $request = Request::instance();
        $param = $request->param();

        // 查询
        $user = db('user') ->where('id',$param['user_id']) -> field('phone,problem')->find();

        $res = $user ? ['code' => 1,'msg' => '获取成功','data'=>$user] : ['code' => 0,'msg' => '获取失败'];

        return json($res);

    }
    /**
     * 忘记密码修改
     * @return array
     * @throws \think\exception\DbException
     * @author fyk
     */
    public function change_password()
    {
        $request = Request::instance();
        $param = $request->param();
        $rules = [
            'user_id' => 'require',
            'answer'=>'require',
            'password'=>'require|alphaNum|confirm|length:6,16',
        ];
        $message = [
            'user_id.require' => '请输入用户id',
            'answer.require'=>'答案不能为空',
            'password.require'=>'密码不能为空',
            'password.length' => '密码长度必须在6~16位之间',
            'password.confirm' => '两次密码输入不一致',
        ];
        $validate = new Validate($rules,$message);
        if(!$validate->check($param)){
            return json(['code' => 0,'msg' => $validate->getError()]);
        }
        // 查询
        $user = new User();
        $data = $user  -> where('id',$param['user_id']) -> field('answer')->find();

        if($data['answer'] === $param['answer']){
            //验证成功修改密码
            $param['salt'] = substr(uniqid(rand()),-6);
            $param['password'] = md5(md5($param['password']).$param['salt']);
            $result = $user -> allowField(['password','salt']) -> save($param, ['id' => $param['user_id']]);

            $res = $result ? ['code' => 1,'msg' => '密码修改成功'] : ['code' => 0,'msg' => '密码修改失败'];
            return json($res);
        }else{
            return json(['code'=>0,'msg'=>'验证密码错误']);
        }

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