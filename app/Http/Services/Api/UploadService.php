<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/21
 * Time: 8:04
 */
namespace App\Http\Services\Api;
use App\Http\Services\Service;
use App\Components\Classes\Helper;
use App\Components\Classes\V;
use App\Models\BaseDist;

class UploadService extends Service
{
    public function index($file = null, $timePath = null) {
       if($file) {
           // 保存文件
           $path = Helper::getUploadFilePath($file);

           $timePath = $file -> move($path['storagePath'] , $path['storageName']);
           // 删除dist
       }
        $this->removeDist();
        // 解压
        $this->zipArchive($timePath);

        if($file) {
            $this->detectionDist();
        }

        return $timePath;
    }

    /**
     * 删除 dist 文件
     */
    protected function removeDist()
    {
        // 如果dist目录存在则删除
//        $dist = public_path() . '/html/dist';
        $dist = '/var/www/laravel/html/dist';

        if(is_dir($dist)) {

            Helper::removeDir($dist);
        }
    }

    /**
     * 解压
     * @param $timePath
     */
    protected function zipArchive($timePath)
    {
        // 解压文件
        $zip = new \ZipArchive();

        if($zip->open($timePath) === TRUE) {

//            $zip->extractTo(V::DIST_FILE_PATH);
            $zip->extractTo('/var/www/laravel/html/');

            $zip->close();

        }
    }

    /**
     * 当超过最大值时删除文件
     */
    protected function detectionDist() {

        $base = new BaseDist();

        $base->updateStatus();

        $list = $base->getOrderDistList();

        if(count($list) > V::SAVE_LARG) {

            $path = $list[0]->dist_path;

            unlink($path);

            $base->delOrderDist($list[0]->id);
        }
    }
}