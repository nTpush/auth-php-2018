<?php

namespace App\Components\Classes;
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/19
 * Time: 8:50
 */

class Helper
{
    /**
     * 菜单分类递归
     * @param $datas
     * @param int $pid
     * @param string $level
     * @param string $path
     * @return array
     */
    public static function menuTree($datas, $isprefix = false, $pid = 0,  $level = V::MENU_PREFIX, $path = V::MENU_PATH) {
        if(empty($datas)) {
            return [];
        }
        $returns = [];
        foreach ($datas as $key => $value) {
            if($value['resource_parent_id'] == $pid) {
                $returns[$value['id']]['id'] = $value['id'];
                if($isprefix) {
                    $returns[$value['id']]['resource_name'] = $level . $value['resource_name'];
                }else{
                    $returns[$value['id']]['resource_name'] = $value['resource_name'];
                }
                if($value['type'] == 1 ) {
                    $returns[$value['id']]['resource_url'] = $path . $value['resource_url'];
                }else {
                    $returns[$value['id']]['resource_url'] = $value['resource_url'];
                }
                $returns[$value['id']]['resource_parent_id'] = $value['resource_parent_id'];
                $returns[$value['id']]['resource_order'] = $value['resource_order'];
                $returns[$value['id']]['type'] = $value['type'];
                $returns[$value['id']]['icon'] = $value['icon'];
                $returns[$value['id']]['status'] = $value['status'];
                $returns[$value['id']]['level'] = $value['level'];
                if(!$isprefix) {
                    $returns[$value['id']]['children'] = collect(self::menuTree(
                        $datas,
                        $isprefix,
                        $value['id'],
                        $level . V::MENU_PREFIX,
                        $path . $value['resource_url']
                    ))->where('type', V::MENU_TYPE);
                } else {
                    $returns[$value['id']]['children'] = self::menuTree(
                        $datas,
                        $isprefix,
                        $value['id'],
                        $level . V::MENU_PREFIX,
                        $path . $value['resource_url']
                    );
                }
            }
        }
        return collect($returns)->values()->toArray();
    }
    /**
     * 数组 分级
     * @param $multi
     * @param array $out
     * @return array|void
     */
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
            self::multiArrayToOne($val['children'], $out);
        }
        return collect($out)->values()->toArray();
    }

    /**
     * 获取本地路径
     * @param $file
     * @return array
     */
    public static function getUploadFilePath($file) {
        $localPath = V::DIST_FILE_PATH . '/' . 'time';
        $originalName = $file->getClientOriginalName();
        $originalExtension = $file->getClientOriginalExtension();
        $storageName =
            sha1(md5($originalName))
            . str_random(16)
            . '.'
            . $originalExtension;
        return [
            'storagePath'   =>  $localPath,
            'storageName'   =>  $storageName
        ];
    }

    /**
     * @param $dirName
     * @return bool
     */
    public static function removeDir($dirName) {
        if(! is_dir($dirName))
        {
            return false;
        }
        $handle = @opendir($dirName);
        while(($file = @readdir($handle)) !== false)
        {
            if($file != '.' && $file != '..')
            {
                $dir = $dirName . '/' . $file;
                is_dir($dir) ? Helper::removeDir($dir) : @unlink($dir);
            }
        }
        closedir($handle);
        return rmdir($dirName) ;
    }

    /**
     * 获取当前时间
     * @param string $dateformat
     * @param string $timestamp
     * @param int $timeoffset
     * @return false|string
     */
    public static function qgmdate($dateformat = 'Y-m-d H:i:s', $timestamp = '', $timeoffset = 8) {

        if(empty($timestamp)) {
            $timestamp = time();
        }
        $result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
        return $result;
    }


    public static function addFixed($num) {
        $fix = '';
        for ($i=1; $i<=$num; $i++) {
            $fix .= '|-- ';
        }
        return $fix;
    }
}