<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/19
 * Time: 17:48
 */

namespace App\Http\Controllers\Api;
use App\Components\Classes\Helper;
use App\Http\Controllers\Controller;
use App\Models\BaseResourceMenu;
use App\Models\BaseRole;
use App\Models\Re\BaseResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


/**
 * 配置
 * Class ConfigurationController
 * @package App\Http\Controllers\Api
 */
class ConfigurationController extends Controller
{
    public function menu()
    {
        $list = (new BaseResourceMenu())->listResource();
        $tree = Helper::menuTree($list);
        $this->setResponse($tree);
        return $this->response();
    }

    /**
     * 菜单、筛选时的数据
     * @return string
     */
    public function menuDefault()
    {
        $menu = DB::table('base_resource')->where('resource_parent_id', 0)->select('id', 'resource_name', 'level')->get();
        $node = DB::table('base_resource')->select('id', 'resource_name', 'level')->get();
        foreach ($node as $key => $val) {
            $node[$key]->resource_name = Helper::addFixed($val->level - 1) . $val->resource_name;
        }
        $this->setResponse([
            'menu' => $menu,
            'node' => $node
        ]);
        return $this->response();
    }


    public function roleDefault()
    {
        $resources = (new BaseResource())->resourceList();
        $res = [];
        foreach ($resources as $key => $val) {
            $res[$val->id]['resource_name'] = $val->resource_name;
            $res[$val->id]['resource_url'] = $val->resource_url;
            $res[$val->id]['resource_parent_id'] = $val->resource_parent_id;
            $res[$val->id]['resource_order'] = $val->resource_order;
            $res[$val->id]['icon'] = $val->icon;
            $res[$val->id]['id'] = $val->id;
            $res[$val->id]['remark'] = $val->remark;
            $res[$val->id]['status'] = $val->status;
            $res[$val->id]['type'] = $val->type;
            $res[$val->id]['level'] = $val->level;
            if($val->node_name) {
                if($val->resource_parent_id == 0 ) {
                    $res[$val->id]['node'][$key]['resource_name'] =  $val->node_name;
                    $res[$val->id]['node'][$key]['level'] = 2;
                }else {
                    $res[$val->id]['node'][$key]['resource_name'] =  $val->node_name;
                    $res[$val->id]['node'][$key]['level'] = 3;
                }
                $res[$val->id]['node'][$key]['id'] = $val->node_id;
                $res[$val->id]['node'][$key]['type'] = $val->node_type;
                $res[$val->id]['node'][$key]['resource_url'] = $val->node_url;
                $res[$val->id]['node'][$key]['remark'] = $val->node_remark;
                $res[$val->id]['node'][$key]['status'] = $val->node_status;
                $res[$val->id]['node'][$key]['resource_order'] = $val->node_order;
                $res[$val->id]['node'][$key]['icon'] = null;
                $res[$val->id]['node'][$key]['resource_parent_id'] = $val->menu_id;
                $res[$val->id]['node'][$key]['node'] = [];
            }else {
                $res[$val->id]['node'] = [];
            }
            $res[$val->id]['node'] = $res[$val->id]['node'];
        }

        $return = [];
        foreach ($res as $key => $val) {
            if($val['resource_parent_id'] == 0) {
                $return[$key]['resource_name'] = $val['resource_name'];
                $return[$key]['resource_url'] = $val['resource_url'];
                $return[$key]['resource_parent_id'] = $val['resource_parent_id'];
                $return[$key]['resource_order'] = $val['resource_order'];
                $return[$key]['icon'] = $val['icon'];
                $return[$key]['id'] = $val['id'];
                $return[$key]['remark'] = $val['remark'];
                $return[$key]['status'] = $val['status'];
                $return[$key]['type'] = $val['type'];
                $return[$key]['level'] = $val['level'];
                if(!$val['node']) {
                    $return[$key]['node'] = collect($res)->where('resource_parent_id', $val['id'])->values()->toArray();
                    foreach ($return[$key]['node'] as $keykey => $valval) {
                        $return[$key]['node'][$keykey]['resource_url'] = $val['resource_url'] . $valval['resource_url'];
                        $return[$key]['node'][$keykey]['resource_name'] =  '|-- |--' . $valval['resource_name'];
                    }
                }else {
                    $return[$key]['node'] = $val['node'];
                }
            }
        }
        $mul = self::multiArrayToOne($return);
        $this->setResponse($mul);
        return $this->response();
    }

    /**
     * 用户添加 角色筛选初始值
     */
    public function userRoles() {
        $list = (new BaseRole())->defaultRole();
        $this->setResponse($list);
        return $this->response();
    }

    /**
     * 给用户设置角色
     * @param $request
     * @return string
     */
    public function setUserRole(Request $request) {
        $roles = $request->input('roles');
        $id = $request->input('id');
        $arr = explode(",", $roles);
        $save = [];
        foreach ($arr as $key => $val) {
            $save[$key]['role_id'] = $val;
            $save[$key]['user_id'] = $id;
        }
        DB::table('base_user_role')->insert($save);
        return $this->response();
    }

    public function editUserRole(Request $request) {
        $roles = $request->input('roles');
        $id = $request->input('id');
        $arr = explode(",", $roles);
        $save = [];
        foreach ($arr as $key => $val) {
            $save[$key]['role_id'] = $val;
            $save[$key]['user_id'] = $id;
        }
        DB::table('base_user_role')->where('user_id', $id)->delete();
        DB::table('base_user_role')->insert($save);
        return $this->response();
    }

    public function showTable() {
        $tables = DB::select("select * from information_schema.TABLES where information_schema.TABLES.TABLE_SCHEMA = 'base'");
        foreach ($tables as $k => $v) {
            $v->tables = DB::select("show full fields from " . $v->TABLE_NAME);
        }
        $this->setResponse($tables);
        return $this->response();
    }



    public static function multiArrayToOne($multi, &$out = []) {
        if(empty($multi)) {
            return;
        }
        $returns = [];
        foreach ($multi as $val) {
            if($val['type'] == 1) {
                $returns['resource_name'] = $val['resource_name'];
                $returns['resource_url'] = $val['resource_url'];
                $returns['id'] = $val['id'];
                $returns['resource_order'] = $val['resource_order'];
                $returns['type'] = $val['type'];
                $returns['status'] = $val['status'];
                $returns['level'] = $val['level'];
                $returns['icon'] = $val['icon'];
                $returns['count'] = count(collect($val['node'])->where('type', '1')->values()->toArray());
                $returns['resource_parent_id'] = $val['resource_parent_id'];
                $returns['node'] = collect($val['node'])->where('type', '2')->values()->toArray();
                $out[] = $returns;
                if($val['level'] <= 1) {
                    self::multiArrayToOne($val['node'], $out);
                }
            }
        }
        return collect($out)->values()->toArray();
    }

}