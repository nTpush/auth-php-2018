<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/25
 * Time: 14:20
 */

namespace App\Http\Controllers\Api\SystemConfig;


use App\Components\Classes\Helper;
use App\Components\Classes\V;
use App\Http\Controllers\Controller;
use App\Models\BaseResourceMenu;

class RoleManagement extends Controller
{
    public function menu() {

//        $menu = (new BaseResourceMenu())->listResource();
//        $res = Helper::menuTree($menu, true);
//        $return = self::multiArrayToOne($res);
//
//        $this->setResponse($return);
        return $this->response();
    }

    public function multiArrayToOne($multi, &$out = []) {
        if(empty($multi)) {
            return;
        }
        $returns = [];
        foreach ($multi as $val) {
            if($val['type'] == V::MENU_TYPE) {
                $returns['resource_name'] = $val['resource_name'];
                $returns['resource_url'] = $val['resource_url'];
                $returns['id'] = $val['id'];
                $returns['resource_order'] = $val['resource_order'];
                $returns['type'] = $val['type'];
                $returns['status'] = $val['status'];
                $returns['level'] = $val['level'];
                $returns['icon'] = $val['icon'];
                $returns['resource_parent_id'] = $val['resource_parent_id'];
                $returns['children'] = collect($val['children'])->where('type', V::NODE_Type);
                $out[] = $returns;
                if($returns['level'] < 2 ) {
                    self::multiArrayToOne($val['children'], $out);
                }
            }


        }
        return collect($out)->values()->toArray();
    }
}