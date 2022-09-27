<?php

namespace app\admin\model;

use think\Model;

/**
 * Order
 * @controllerUrl 'order'
 */
class Order extends Model
{
    // 表名
    protected $name = 'order';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';



    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }
}