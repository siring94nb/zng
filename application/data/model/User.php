<?php
namespace app\data\model;

use think\Model;

class User extends Model
{
    protected $table = "ca_user";
    protected $resultSetType = 'collection';
    protected $hidden = ['password','salt','img','problem','answer'];
    //新增
    public function add($nickname,$username,$phone,$password,$salt,$problem,$answer,$status,$type,$east,$western,$power)
    {
        return $this->save([
            'nickname' => $nickname,
            'username' => $username,
            'phone' => $phone,
            'password' => $password,
            'salt' => $salt,
            'problem' => $problem,
            'answer' => $answer,
            'status' => $status,
            'type' => $type,
            'east_longitude' => $east,
            'western_scriptures' =>$western,
            'power' =>$power,
            'create_time' => time(),

        ]);
    }

    //展示
    public function user_list($type,$limit,$start)
    {


        return self::all(['type'=>$type])

            ->toArray();

    }

    //修改
    public function user_edit($id)
    {
        return self::get(['id'=>$id]);
    }

    //修改前台用户
    public function user_api_edit($username,$phone,$password,$salt,$status,$type,$id)
    {
        return $this->save([
            'username' => $username,
            'phone' => $phone,
            'password' => $password,
            'salt' => $salt,
            'status' => $status,
            'type' => $type,
            'create_time' => time(),

        ],['id' => $id]);
    }

    //修改后台用户
    public function user_admin_edit($nickname,$username,$phone,$password,$salt,$problem,$answer,$status,$type,$east,$western,$power,$id)
    {
        return $this->save([
            'nickname' => $nickname,
            'username' => $username,
            'phone' => $phone,
            'password' => $password,
            'salt' => $salt,
            'problem' => $problem,
            'answer' => $answer,
            'status' => $status,
            'type' => $type,
            'east_longitude' => $east,
            'western_scriptures' =>$western,
            'power' =>$power,
            'create_time' => time(),

        ],['id' => $id]);
    }
    //修改状态
    public function user_status($id,$status)
    {
        return $this->save([
            'status' => $status,
        ],['id' => $id]);
    }

    //删除
    public function user_del($uid)
    {

        return self::destroy($uid);

    }

}