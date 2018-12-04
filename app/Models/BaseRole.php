<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/28
 * Time: 17:43
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseRole extends Model
{
    //
    protected $table = 'base_role';

    protected $fillable = [];

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $guarded = [];   //黑名单

    public function roleCreate($request) {
        return $this->create($request);
    }

    public function defaultRole() {
        return $this->select('id', 'role_name')->get();
    }



    public function updateRole($request, $id) {
        return $this->where([
            $this->primaryKey => $id
        ])->update($request);
    }



//->leftJoin('base_resource_node', 'base_resource.id', '=', 'base_resource_node.menu_id')
    public function roleList() {
        return DB::table('base_role_resource')
            ->leftJoin('base_role', 'base_role.id', '=', 'base_role_resource.role_id')
            ->select('base_role_resource.*', 'base_role.role_name', 'base_role.remark', 'base_role.status')
//            ->leftJoin('base_resource', 'base_role_resource.resource_id', '=', 'base_resource.id', function ($join) {
//                $join->where('base_resource', '=', '1');
//            })
//            ->leftJoin('base_resource_node', 'base_role_resource.resource_id', '=', 'base_resource_node.node_id', function ($join) {
//                $join->where('base_resource', '=', '2');
//            })
            ->get();
//            ->select('base_role.*', 'base_role_resource.resource_id', 'base_role_resource.type', 'base_role_resource.role_id')
//            ->leftJoin('base_role_resource', 'base_role.id', '=', 'base_role_resource.role_id')->get();
    }


    public function delRole($id) {
            DB::beginTransaction();
            try {
                DB::table('base_role_resource')->where('role_id', $id)->delete();

                DB::table('base_user_role')->where('role_id', $id)->delete();

                $this->where([
                    $this->primaryKey => $id
                ])->delete();
                return DB::commit();
            }catch (Exception $e) {
                DB::rollBack();
            }
    }
}