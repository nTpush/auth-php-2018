<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/28
 * Time: 18:23
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaseRoleResource extends Model
{
    protected $table = 'base_role_resource';

    protected $fillable = [];

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $guarded = [];   //黑名单

    public function roleResourceCreate($request) {
        return $this->create($request);
    }

//->leftJoin('base_resource_node', 'base_resource.id', '=', 'base_resource_node.menu_id')
    public function roleResourceDetail($request) {
        return $this->where('role_id', $request)
                    ->select('base_role_resource.*', 'base_role.role_name', 'base_role.remark', 'base_resource.resource_parent_id',  'base_resource_node.menu_id')
                    ->leftJoin('base_role', 'base_role.id', '=', 'base_role_resource.role_id')
                    ->leftJoin('base_resource', 'base_resource.id', '=', 'base_role_resource.resource_id')
                    ->leftJoin('base_resource_node', 'base_resource_node.node_id', '=', 'base_role_resource.resource_id')
            ->get();
    }

    public function roleResourceDel($id) {
        return $this->where('role_id', $id)->delete();
    }
}