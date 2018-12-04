<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseResourceMenu extends Model
{
    //

    protected $table = 'base_resource';

    protected $fillable = [];

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $guarded = [];   //黑名单


    /**
     * 创建
     * @param $request
     * @return mixed
     */
    public function createResource($request) {


        if($request['resource_parent_id']) {
            $le = $this->find($request['resource_parent_id'], ['level'])->level;
            $request['level'] = $le + 1;
        }

        return $this->create($request);
    }

    /**
     * 列表查询
     * @return mixed
     */
    public function listResource() {
        return $this->queryResource()->get();
    }

    public function detailResource($id) {
        return $this->find($id);
    }


    /**
     * 编辑
     * @param $request
     */
    public function editResource($request, $id) {

        if($request['resource_parent_id']) {
            $le = $this->find($request['resource_parent_id'], ['level'])->level;
            $request['level'] = $le + 1;
        }

        if($request['resource_parent_id'] == 0) {
            $request['level'] = 1;
        }

        return $this->where([
            $this->primaryKey => $id
        ])->update($request);
    }

    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function deleteResource($id) {
        return $this->where([
            $this->primaryKey => $id
        ])->delete();
    }

    /**
     * 条件查询
     * @return mixed
     */
    public function queryResource() {
        $query = $this->orderBy('resource_order', 'desc');
        /**
         * 资源名查询
         */
        if (request()->get('resource_name'))
            $query->where(function ($query) {
                $query->where('resource_name', 'like', '%' . request()->get('resource_name') . '%');
            });
        return $query;
    }
}
