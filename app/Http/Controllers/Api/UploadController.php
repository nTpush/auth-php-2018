<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/19
 * Time: 17:48
 */

namespace App\Http\Controllers\Api;

use App\Components\Classes\V;
use App\Http\Controllers\Controller;
use App\Http\Services\Api\UploadService;
use App\Models\BaseDist;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * 配置
 * Class ConfigurationController
 * @package App\Http\Controllers\Api
 */
class UploadController extends Controller
{
    public function dist()
    {
        $isfile = request()->hasFile('file');

        if(!$isfile) {
            $this->setResponse([], false, '请上传文件！');
            return $this->response();
        }

        $file = request()->file('file');
        $ext = $file->getClientOriginalExtension();     // 扩展名
        if(!in_array($ext, V::DIST_EXE)) {
            $this->setResponse([], false, '上传格式不正确！');
            return $this->response();
        }

        $timePath = (new UploadService())->index($file);

        // 获取用户信息
        $user = Auth::user();

        $arr = [
            'operator' => $user->id,
            'dist_path' => $timePath
        ];

        $base = new BaseDist();
        $base->createResource($arr);

        $this->setResponse($timePath);
        return $this->response();
    }

    public function index()
    {
        $data = (new BaseDist())->listResource();
        foreach ($data as $key => $val) {
//            $data[$key]['operator'] = 22;
            $data[$key]['operator'] = (new User())->getUserName($val['operator'])->name;
        }
        $this->setResponse($data);
        return $this->response();
    }

    /**
     * 回滚
     * @param Request $request
     * @return string
     */
    public function back(Request $request) {
        $id = $request->input('id');
        $dist = new BaseDist();
        $detail = $dist->detailDist($id);
        $path = $detail->dist_path;
        $distService = new UploadService();
        $save = $distService->index(null, $path);
        if($save) {   // 更新成在修改数据库状态
            $dist->updateStatus();
            $dist->currentOrderDist($id);
        }
        $this->setResponse($path);
        return $this->response();
    }
}