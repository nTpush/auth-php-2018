<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/16
 * Time: 14:03
 */

namespace App\Http\Services\Re;


use App\Http\Services\Service;
use App\Models\Re\BaseResourceNode;

class ResourceNodeService extends Service
{
    public $nodeModel;

    public function __construct()
    {
        $this->nodeModel = new BaseResourceNode();
    }

    public function delNodeService($id) {
        $this->nodeModel->nodeDel($id);
    }
}