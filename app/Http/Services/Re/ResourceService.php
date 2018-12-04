<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/26
 * Time: 8:14
 */

namespace App\Http\Services\Re;

use App\Http\Services\Service;
use App\Models\Re\BaseResource;
use Illuminate\Support\Facades\DB;

class ResourceService extends Service
{
    protected $resource;

    public function __construct()
    {
        $this->resource = new BaseResource();
    }

    public function resourceList()
    {
        $resources = $this->resource->resourceList();
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
                    $res[$val->id]['node'][$key]['resource_name'] = $this->addFixed(2) . $val->node_name;
                    $res[$val->id]['node'][$key]['level'] = 2;
                }else {
                    $res[$val->id]['node'][$key]['resource_name'] = $this->addFixed(3) . $val->node_name;
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
                       $return[$key]['node'][$keykey]['resource_name'] = $this->addFixed(2) . $valval['resource_name'];
                   }
               }else {
                   $return[$key]['node'] = $val['node'];
               }
           }
        }
        $mul = self::multiArrayToOne($return);
        return $mul;
    }
    public static function multiArrayToOne($multi, &$out = []) {
        if(empty($multi)) {
            return;
        }
        $returns = [];
        foreach ($multi as $val) {
            $returns['resource_name'] = $val['resource_name'];
            $returns['resource_url'] = $val['resource_url'];
            $returns['id'] = $val['id'];
            $returns['resource_order'] = $val['resource_order'];
            $returns['type'] = $val['type'];
            $returns['status'] = $val['status'];
            $returns['level'] = $val['level'];
            $returns['icon'] = $val['icon'];
            $returns['resource_parent_id'] = $val['resource_parent_id'];
            $out[] = $returns;
            self::multiArrayToOne($val['node'], $out);
        }
        return collect($out)->values()->toArray();
    }

    public function resourceSearchDetail($id, $type)
    {
        if($type == 2)
           return DB::table('base_resource_node')
                ->where('node_id', $id)->first();
        if($type == 1)
            return DB::table('base_resource')
                ->where('node_id', $id)->first();
    }

    public function resourceCreate($request)
    {
        return $this->resource->resourceCreate($request);
    }

    public function resourceEdit($request, $id)
    {
        return $this->resource->resourceEdit($request, $id);
    }

    /**
     * 删除资源
     * @param $id
     * @return bool
     */
    public function resourceDel($id)
    {
        // 删除
        if($this->resource->countChild($id)) {
            return $this->resource->delMenu($id);



        }else {
            return false;
        }

//        return $this->resource->resourceDel($id);
    }


    protected function addFixed($num) {
        $fix = '';
        for ($i=1; $i<=$num; $i++) {
            $fix .= '|-- ';
        }
        return $fix;
    }



}