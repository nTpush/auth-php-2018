<?php

namespace App\Models;

use App\Components\Classes\V;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseTodolist extends Model
{
    //

    protected $table = 'base_doto';

    protected $primaryKey = 'id';

    protected $fillable = [];

    public $timestamps = true;

    protected $guarded = [];   //黑名单


    /**
     * 创建
     * @param $request
     * @return mixed
     */
    public function createTodoList($request) {
        return $this->create($request);
    }


    /**
     * 列表 按时间归档
     * @return mixed
     */
    public function listTodoList($status = null) {
        return $this->queryTodoList($status)->get();

    }

    /**
     * 更新
     * @param $request
     * @param $id
     * @return mixed
     */
    public function listUpdate($request, $id) {
        return $this->where([
            $this->primaryKey => $id
        ])->update($request);
    }

    /**
     * 列表详情
     * @param $id
     * @return mixed
     */
    public function listDetail($id) {
        return $this->find($id);
    }

    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function listDelete($id) {
        return $this->where([
            $this->primaryKey => $id
        ])->delete();
    }


    public function data() {
        return $this->queryResource()->paginate();
    }

    /**
     * 条件查询
     * @return mixed
     */
    public function queryTodoList($status = null) {
        $status == 1 ?
            $query = $this->orderBy('created_at' , 'desc')
            :
            $query = $this->orderBy('computed_at' , 'desc');

        if($status) {
            $query->where('status', $status);
        }
        /**
         * 资源名查询
         */
//        if (request()->get('resource_name'))
//            $query->where(function ($query) {
//                $query->where('resource_name', 'like', '%' . request()->get('resource_name') . '%');
//            });
        return $query;
    }

}
