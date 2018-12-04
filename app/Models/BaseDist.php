<?php

namespace App\Models;

use App\Components\Classes\V;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseDist extends Model
{
    //

    protected $table = 'base_dist';

    protected $fillable = [];

    public $timestamps = true;

    protected $guarded = [];   //黑名单


    /**
     * 创建
     * @param $request
     * @return mixed
     */
    public function createResource($request) {
        $this->updateStatus();
        return $this->create($request);
    }

    /**
     * 列表查询
     * @return mixed
     */
    public function listResource() {
        return $this->queryResource()->paginate();
    }


    /**
     * 条件查询
     * @return mixed
     */
    public function queryResource() {
        $query = $this->orderBy('created_at', 'desc');
        /**
         * 资源名查询
         */
//        if (request()->get('resource_name'))
//            $query->where(function ($query) {
//                $query->where('resource_name', 'like', '%' . request()->get('resource_name') . '%');
//            });
        return $query;
    }

    public function updateStatus() {
        return DB::table('base_dist')
            ->where('status', 1)
            ->update(array('status' => 0));
    }

    public function getOrderDistList() {
        return DB::table('base_dist')
                ->where('status', 0)
                ->orderBy('created_at', 'asc')->get();
    }

    public function delOrderDist($id) {
        return DB::table('base_dist')
            ->where('id', $id)
            ->update(array('status' => 2));
    }

    public function currentOrderDist($id) {
        return DB::table('base_dist')
            ->where('id', $id)
            ->update(array('status' => 1));
    }

    public function detailDist($id) {
        return $this->where('id', $id)->first();
    }
}
