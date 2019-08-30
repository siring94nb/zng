<?php
namespace app\data\model;

use think\Model;

class Product extends Model
{
    protected $table = "ca_product";
    protected $resultSetType = 'collection';

    //展示
    public function user_list($type,$limit,$start)
    {


        return self::all(['type'=>$type])

            ->toArray();

    }
    //新增
    public function add($title,$num,$img,$describe)
    {
        return $this->save([
            'title' => $title,
            'num' => $num,
            'img' => $img,
            'describe' => $describe,
            'create_time' => time(),

        ]);
    }



    //修改页面
    public function product_edit($id)
    {
        return self::get(['id'=>$id]);
    }

    //修改前台用户
    public function edit($title,$num,$img,$describe,$id)
    {
        return $this->save([
            'title' => $title,
            'num' => $num,
            'img' => $img,
            'describe' => $describe,
            'update_time' => time(),

        ],['id' => $id]);
    }


    //删除
    public function user_del($uid)
    {

        return self::destroy($uid);

    }

}