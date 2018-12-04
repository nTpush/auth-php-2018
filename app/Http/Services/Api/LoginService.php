<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/3
 * Time: 15:22
 */

namespace App\Http\Services\Api;


use App\Http\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class LoginService extends Service
{

    public function dealRoles($id) {
        $data = DB::table('base_user_role')->where('user_id', $id)->get();

        if(!count($data)) {
            return [
                'menu' => [],
                'node' => []

            ];
        }

        $arr = [];
        foreach ($data as $k => $v) {
            $arr[$k] = $v->role_id;
        }

        $menu = DB::table('base_role_resource')->whereIn('role_id', $arr)
            ->where('type', 1)
            ->get();

        $node = DB::table('base_role_resource')->whereIn('role_id', $arr)
            ->where('type', 2)
            ->get();


        $nodeIds = [];
        foreach ($node as $k => $v) {
            $nodeIds[$k] = $v->resource_id;
        }

        $uniqueNode = collect($nodeIds)->unique()->values()->all();

        $nodeDetail = DB::table('base_resource_node')->whereIn('node_id', $uniqueNode)->select('node_id')->get();

        $nodes = [];
        foreach ($nodeDetail as $k => $v) {
            $nodes[$k] = $v->node_id;
        }



        $menuIndex = [];
        foreach ($menu as $k => $v) {
            $menuIndex[$k] = $v->resource_id;
        }
        $uniqueIndex = collect($menuIndex)->unique()->values()->all();
        $detail = DB::table('base_resource')->whereIn('id', $uniqueIndex)->select('resource_name','id', 'resource_parent_id')->get();

        $menus = [];
        foreach ($detail as $k => $v) {
            $menus[$k] = $v->id;
        }

        $pids = [];
        foreach ($detail as $k => $v) {
            $pids[$k] = (int) $v->resource_parent_id;
        }
        $uniquePid = collect($pids)->unique()->values()->all();
//        $return = [];
//        foreach ($res as $k => $v) {
//
//            if($v['resource_parent_id']) {
//                $parent = DB::table('base_resource')->where('id', $k)->first();
//                $return[$k]['ml'] = $parent['resource_name'];
//            }
//
//        }


        return [
            'menu' => array_merge($menus, $uniquePid),
            'node' => $nodes
        ];
    }

    /**
     * @param $path
     * @param $uid
     * @return bool
     */
    public function checkPage($path, $uid) {
        $detail = DB::table('base_resource')->select('id')->where('resource_url', $path)->first();

        if(!$detail) {
            return false;
        }
        $id = $detail->id;
        $menu = json_decode(Redis::get($uid))->menu;

        if (in_array($id, $menu)) {
            return true;
        }
        return false;

    }
}