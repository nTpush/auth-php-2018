<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/27
 * Time: 11:18
 */

namespace App\Http\Services\SystemConfig;

use App\Http\Services\Service;
use App\Models\BaseRole;
use App\Models\BaseRoleResource;
use Illuminate\Support\Facades\DB;

class RoleManagementService extends Service
{
    protected $roleModel;
    protected $roleResourceModel;

    public function __construct()
    {
        $this->roleModel = new BaseRole();
        $this->roleResourceModel = new BaseRoleResource();
    }

    public function createRole($request) {
        $data = [
            'role_name' => $request['role_name'],
            'remark' => $request['remark']
        ];
        $roleId = $this->roleModel->roleCreate($data)->id;
        $rules = $this->deaalRoles($request['rules'], $roleId);
        $last = DB::table('base_role_resource')->insert($rules);
        // todo 数据处理
        return $last;
    }

    public function listRole() {
        $list = $this->roleModel->roleList();
        $res = [];
        foreach ($list as $key => $val) {
            $res[$val->role_id]['role_name'] = $val->role_name;
            $res[$val->role_id]['remark'] = $val->remark;
            $res[$val->role_id]['id'] = $val->role_id;

            $res[$val->role_id]['status'] = $val->status;
            if($val->type === 1) {
                $res[$val->role_id]['menu'][$key] = DB::table('base_resource')->select('resource_name', 'id', 'resource_parent_id')->where('id', '=', $val->resource_id)->first();
            }
            if($val->type === 2) {
                $res[$val->role_id]['node'][$key] = DB::table('base_resource_node')->select('node_name', 'menu_id', 'node_remark')->where('node_id', '=', $val->resource_id)->first();
            }
        }

        $return = [];
        foreach ($res as $key => $val) {
            $return[$key]['role_name'] = $val['role_name'];
            $return[$key]['remark'] = $val['remark'];
            $return[$key]['status'] = $val['status'];
            $return[$key]['id'] = $val['id'];
            $roles = [];
            foreach ($val['menu'] as $keykey => $valval) {
                $roles[$keykey]['resource_name'] = $valval->resource_name;
                if($valval->resource_parent_id) {
                    $roles[$keykey]['resource_parent'] = DB::table('base_resource')->select('resource_name')->where('id', $valval->resource_parent_id)->first()->resource_name;
                }else {
                    $roles[$keykey]['resource_parent'] = null;
                }
                if(isset($val['node'])) {  // 判断键是否存在
                    $roles[$keykey]['menu'] = collect(collect($val['node'])->where('menu_id', $valval->id)->toArray())->values()->toArray();
                }else {
                    $roles[$keykey]['menu'] = [];
                }
            }

            $last = [];
            foreach ($roles as $Kkk => $vvv) {
                $last[$vvv['resource_parent']][$Kkk]['resource_parent'] = $vvv['resource_parent'];
                $last[$vvv['resource_parent']][$Kkk]['resource_name'] = $vvv['resource_name'];
                $last[$vvv['resource_parent']][$Kkk]['menu'] = $vvv['menu'];
            }
            $fix = [];
            foreach ($last as $k => $v) {
                $fix[$k]['title'] = $k;
                $fix[$k]['menu'] = collect($v)->values()->toArray();
            }
            $return[$key]['roles'] = collect($fix)->values()->toArray();
        }
        return collect($return)->values()->toArray();
    }


    public function detailRole($request) {
         $detail = $this->roleResourceModel->roleResourceDetail($request);
         $menu = [];
         $node = [];
         foreach ($detail as $k => $v) {
            if($v['type'] == 1) {
                $menu[$k] = $v['resource_id'] . '@' . $v['resource_parent_id'];
            }
             if($v['type'] == 2) {
                 $node[$k] = $v['resource_id'] . '@' . $v['menu_id'];
             }
        }
        $last = [
            'role_name' => $detail[0]->role_name,
            'remark' => $detail[0]->remark,
            'id' => $detail[0]->role_id,
            'menu' => collect($menu)->values()->toArray(),
            'node' => collect($node)->values()->toArray()
        ];
        return $last;
    }

    public function updateRole($request, $id) {
        $roleName = $request->input('role_name');
        $remark = $request->input('remark');
        $baseInfo = [
            'role_name' => $roleName,
            'remark' => $remark
        ];
        $this->roleModel->updateRole($baseInfo, $id);
        // 获取处理后的数据
        $roles = $this->deaalRoles($request['rules'], $id);
        // 先把关联表的数据删了
        $this->roleResourceModel->roleResourceDel($id);
        // 再插入数据
        $last = DB::table('base_role_resource')->insert($roles);

        return $last;

    }


    protected function deaalRoles($role, $roleId) {
        $rules = [];
        foreach (explode(',', $role) as $key => $val) {
            $arr = [];
            $arr['resource_id'] = explode('@', $val)[0];
            $arr['type'] = explode('@', $val)[1];
            $arr['role_id'] = $roleId;
            $rules[$key] = $arr;
        }

        return $rules;
    }

    public function delRoles($id) {
        return $this->roleModel->delRole($id);
    }
}