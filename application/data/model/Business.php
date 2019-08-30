<?php
namespace app\data\model;

use think\Model;

class Business extends Model
{
    protected $table = "ca_business";
    protected $resultSetType = 'collection';

    //展示
    public function business_index()
    {

        return self::get(1);

    }


    public function business_edit($con)
    {

        return $this->save([
            'con' => $con,
        ],['id' => 1]);

    }
}