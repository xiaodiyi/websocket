<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class GeChatGroup extends Model
{
    //
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'group_details';
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    protected $guarded = ['updated_at','created_at'];
    public $timestamps = true;
}
