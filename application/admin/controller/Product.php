<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/8/28 0027
 * 产品
 * Time: 15:20
 */
namespace app\admin\controller;
use app\data\model\User;
use app\data\model\Product as ProductAll;
use think\Controller;
use think\Request;
use think\Validate;

class Product extends Controller
{

    //主页
    public function product_list(Request $request)
    {
        $limit = $request -> get('size', 10);
        $start = $request -> get('page', 1);
        $user = new ProductAll();

        $productAll = $user->where('delete_time',null)->paginate($limit, false, ['page' => $start])->toArray();



        $res = $productAll ?['code'=>1,'msg'=>'获取成功','data'=>$productAll] : ['code'=>0,'msg'=>'获取失败'];

        return json($res);exit;
    }
    //新增
    public function product_add()
    {
        $request = Request::instance();
        $param = $request->param();
        $rules = [
            'user_id' => 'require',
            'title' => 'require',
            'num' => 'require',
            'img'=>'require',
            'describe'=>'require',
        ];
        $message = [
            'title.require'=>'标题不能为空',
            'num.require'=>'编号不能为空',
            'img.require'=>'图片不能为空',
            'describe.require'=>'描述不能为空',
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

        $product = new ProductAll();

        $productAll = $product->add($param['title'],$param['num'],$param['img'],$param['describe']);

        $res = $productAll ?['code'=>1,'msg'=>'新增成功'] : ['code'=>0,'msg'=>'新增失败'];

        return json($res);exit;
    }
    //修改
    public function product_edit()
    {
        $request = Request::instance();
        $param = $request->param();
        $rules = [
            'user_id' => 'require',
            'id' => 'require',
            'title' => 'require',
            'num' => 'require',
            'img'=>'require',
            'describe'=>'require',
        ];
        $message = [
            'id.require'=>'当前编辑Id不能为空',
            'title.require'=>'标题不能为空',
            'num.require'=>'编号不能为空',
            'img.require'=>'图片不能为空',
            'describe.require'=>'描述不能为空',
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

        $id = $param['id'];
        $product = new ProductAll();

        $productAll = $product->edit($param['title'],$param['num'],$param['img'],$param['describe'],$id);

        $res = $productAll ?['code'=>1,'msg'=>'修改成功'] : ['code'=>0,'msg'=>'修改失败'];

        return json($res);exit;
    }

    //修改展示
    public function product_edit_index()
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
        $user = new User();
        $data = $user  -> where('id',$param['user_id']) -> field('power')->find();

        if($data['power'] === 0){
            return json(['code' => 0,'msg' => '您没有此权限']);
        }
        $id = $param['id'];
        $product = new ProductAll();
        $productAll = $product->product_edit($id);

        $res = $productAll ?['code'=>1,'msg'=>'获取成功','data'=>$productAll] : ['code'=>0,'msg'=>'获取失败','data'=>''];

        return json($res);exit;
    }

}