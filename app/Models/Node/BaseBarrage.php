<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/31
 * Time: 10:31
 */

namespace App\Models\Node;


use Illuminate\Database\Eloquent\Model;

class BaseBarrage extends Model
{
    protected $table = 'base_barrage';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $hidden = [];

    public $timestamps = true;

    /**
     * 列表
     * @return mixed
     */
    public function barrageList() {
        return $this->get();
    }

    /**
     * 创建
     * @param $request
     * @return mixed
     */
    public function barrageCreate($request) {
        return $this->create($request);
    }
}