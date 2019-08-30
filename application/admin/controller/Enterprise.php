<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/27 0027
 * 订单
 * Time: 15:20
 */
namespace app\admin\controller;

use app\data\model\Business;
use think\Controller;
use think\Request;
use think\Validate;

class Enterprise extends Controller
{
    public function enterprise_index()
    {
        $list = new Business();
        $data = $list->business_index();

        $res = $data ?['code'=>1,'msg'=>'获取成功','data'=>$data] : ['code'=>0,'msg'=>'没有内容'];

        return json($res);exit;
    }

    public function enterprise_edit()
    {
        $request = Request::instance();
        $param = $request->param();
        $rules = [
            'con' => 'require',
        ];
        $message = [
            'con.require' => '请输入内容',
        ];
        $validate = new Validate($rules,$message);
        if(!$validate->check($param)){
            return json(['code' => 0,'msg' => $validate->getError()]);
        }
        $list = new Business();

        $data = $list->business_edit($param['con']);

        $res = $data ?['code'=>1,'msg'=>'成功'] : ['code'=>0,'msg'=>'失败'];

        return json($res);exit;
    }


}