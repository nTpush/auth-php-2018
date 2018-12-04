<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/21
 * Time: 11:48
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\BaseLoginLog;
use App\Models\BaseOperationLog;

class LogController extends Controller
{
    public function user() {
        $list = (new BaseOperationLog())->data();
        $this->setResponse($list);
        return $this->response();

    }
    public function operation() {
        $list = (new BaseLoginLog())->data();
        $this->setResponse($list);
        return $this->response();
    }
}