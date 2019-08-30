<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/28 0027
 * 用户
 * Time: 15:20
 */
namespace app\admin\controller;

use app\data\model\User as UserAll;
use think\Controller;
use think\Request;
use think\Validate;

class User extends Controller
{

    //显示用户
    public function user_list(Request $request)
    {
        $limit = $request -> get('size', 10);
        $start = $request -> get('page', 1);
        $type = $request  -> get('type', 0);
        $user = new UserAll();

        $userAll = $user->where('type',$type)->paginate($limit, false, ['page' => $start])->toArray();

        foreach ($userAll['data'] as $k=>$v){
            if($v['status'] === 1){
                $userAll['data'][$k]['status'] = true;
            }else{
                $userAll['data'][$k]['status'] = false;
            }
        }


        $res = $userAll ?['code'=>1,'msg'=>'获取成功','data'=>$userAll] : ['code'=>0,'msg'=>'获取失败'];

        return json($res);exit;
    }


    //编辑用户
    public function user_edit()
    {
        $request = Request::instance();
        $param = $request->param();
        $rules = [
            'user_id' => 'require',
            'id' => 'require',
        ];
        $message = [
            'user_id.require'=>'后台Id不能为空',
            'id.require'=>'当前编辑Id不能为空',
        ];
        $validate = new Validate($rules,$message);
        if(!$validate->check($param)){
            return json(['code' => 0,'msg' => $validate->getError()]);
        }
        $user = new UserAll();
        $data = $user  -> where('id',$param['user_id']) -> field('power')->find();

        if($data['power'] === 0){
            return json(['code' => 0,'msg' => '您没有此权限']);
        }
        $id = $param['id'];
        $userAll = $user->user_edit($id);

        $res = $userAll ?['code'=>1,'msg'=>'获取成功','data'=>$userAll] : ['code'=>0,'msg'=>'获取失败','data'=>''];

        return json($res);exit;
    }

    //修改前台用户
    public function user_api_edit()
    {
        $request = Request::instance();
        $param = $request->param();
        $rules = [
            'user_id' => 'require',
            'id' => 'require',
            'phone' => 'require|regex:\d{11}|unique:user',
            'username' => 'require',
            'password'=>'require|alphaNum|confirm|length:6,16',
            'status'=>'require',
        ];
        $message = [
            'id.require'=>'当前编辑Id不能为空',
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

        $user = new UserAll();
        $data = $user  -> where('id',$param['user_id']) -> field('power')->find();

        if($data['power'] === 0){
            return json(['code' => 0,'msg' => '您没有此权限']);
        }
        $salt = substr(uniqid(rand()),-6);
        $password = md5(md5($param['password']).$salt);
        $id = $param['id'];
        $userAll = $user->user_api_edit($param['username'],$param['phone'],$password, $salt,$param['status'],1,$id);

        $res = $userAll ?['code'=>1,'msg'=>'修改成功','data'=>$userAll] : ['code'=>0,'msg'=>'修改失败','data'=>''];

        return json($res);exit;
    }

    //编辑后台用户
    public function user_admin_edit()
    {
        $request = Request::instance();
        $param = $request->param();
        $rules = [
            'user_id' => 'require',
            'id' => 'require',
            'nickname' => 'require|unique:user',
            'username' => 'require',
            'password'=>'require|alphaNum|confirm|length:6,16',
            'phone' => 'require|regex:\d{11}|unique:user',
            'status'=>'require',
            'power' => 'require',
        ];
        $message = [
            'id.require'=>'当前编辑Id不能为空',
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
        $user = new UserAll();
        $data = $user  -> where('id',$param['user_id']) -> field('power')->find();

        if($data['power'] === 0){
            return json(['code' => 0,'msg' => '您没有此权限']);
        }

        $salt = substr(uniqid(rand()),-6);
        $password = md5(md5($param['password']).$salt);
        $id = $param['id'];
        // 储存

        $result = $user->user_admin_edit($param['nickname'],$param['username'],$param['phone'],$password,$salt,'','',$param['status'],0,'','',$param['power'],$id);

        $res = $result ? ['code' => 1,'msg' => '修改成功'] : ['code' => 0,'msg' => '修改失败'];

        return json($res);exit;
    }
    //用户状态变更
    public function user_status()
    {
        $request = Request::instance();
        $param = $request->param();
        $rules = [
            'user_id' => 'require',
            'status' => 'require',
            'id' => 'require',
        ];
        $message = [
            'user_id.require'=>'后台id不能为空',
            'status.require'=>'状态值不能为空',
            'id.require'=>'当前编辑Id不能为空',
        ];
        $validate = new Validate($rules,$message);
        if(!$validate->check($param)){
            return json(['code' => 0,'msg' => $validate->getError()]);
        }
        $user = new UserAll();
        $data = $user  -> where('id',$param['user_id']) -> field('power')->find();

        if($data['power'] === 0){
            return json(['code' => 0,'msg' => '您没有此权限']);
        }
        $userAll = $user->user_status($param['id'],$param['status']);

        $res = $userAll ?['code'=>1,'msg'=>'修改成功'] : ['code'=>0,'msg'=>'修改失败'];

        return json($res);exit;
    }

    //删除用户
    public function user_del()
    {
        $request = Request::instance();
        $param = $request->param();
        $rules = [
            'user_id' => 'require',
            'id' => 'require',
        ];
        $message = [
            'user_id.require'=>'后台Id不能为空',
            'id.require'=>'当前编辑Id不能为空',
        ];
        $validate = new Validate($rules,$message);
        if(!$validate->check($param)){
            return json(['code' => 0,'msg' => $validate->getError()]);
        }
        $user = new UserAll();
        $data = $user  -> where('id',$param['user_id']) -> field('power')->find();

        if($data['power'] === 0){
            return json(['code' => 0,'msg' => '您没有此权限']);
        }
        $userAll = $user->user_del($param['id']);

        $res = $userAll ?['code'=>1,'msg'=>'删除成功'] : ['code'=>0,'msg'=>'删除失败'];

        return json($res);exit;
    }
}