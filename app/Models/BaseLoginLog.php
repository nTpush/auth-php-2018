<?php

namespace App\Models;

use App\Components\Classes\V;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseLoginLog extends Model
{
    //

    protected $table = 'base_login_log';

    protected $fillable = [];

    public $timestamps = true;

    protected $guarded = [];   //黑名单

    public function data() {
        return $this->queryResource()->paginate();
    }

    /**
     * 条件查询
     * @return mixed
     */
    public function queryResource() {
        $query = $this->orderBy('login_time', 'desc');
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
