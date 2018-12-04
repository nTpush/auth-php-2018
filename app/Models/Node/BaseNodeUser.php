<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/23
 * Time: 8:51
 */
namespace App\Models\Node;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class BaseNodeUser extends Model
{
    protected $table = 'base_node_user';

    protected $primaryKey = 'id';

    protected $guarded = ['password_confirm'];

    protected $hidden = ['password'];

    public $timestamps = true;

    /**
     * 生成 uuid
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $data = Uuid::uuid1();
            $str = $data->getHex();
            $model->uuid = $str;
        });
    }
    /**
     * 创建
     */
    public function NodeUserCreate($request) {
        return $this->create($request);
    }

    /**
     * 获取用户信息
     */
    public function NodeUserInfo($user) {
        return $this->where('user', $user)->first()->makeVisible('password');
    }

    public function NodeUserList() {
        return $this->getNodeUserListQuery()->paginate();
    }

    /**
     * 条件查询
     * @return mixed
     */
    public function getNodeUserListQuery()
    {
        $query = $this->orderBy('created_at', 'desc');

        /**
         * spu/sku查询
         */
//        if (request()->get('sku_spu'))
//            $query->where(function ($query) {
//                $query->where('sku', 'like', '%' . request()->get('sku_spu') . '%')
//                    ->orWhere('sku_spu', 'like', '%' . request()->get('sku_spu') . '%');
//            });

        return $query;
    }
}