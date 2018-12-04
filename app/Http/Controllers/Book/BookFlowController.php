<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/31
 * Time: 10:41
 */

namespace App\Http\Controllers\Book;


use App\Http\Controllers\Controller;
use App\Http\Services\Book\BookFlowService;

class BookFlowController extends Controller
{
    protected $flow;
    public function __construct()
    {
        $this->flow = new BookFlowService();
    }

    /**
     * 创建流程
     * @return string
     */
    public function createFlow() {
        $res = $this->flow->createFlow();
        if($res)
            return $this->response();
    }

    public function listFlow() {
        $res = $this->flow->listFlow();
        $this->setResponse($res);
        return $this->response();
    }

    /**
     * 创建流程节点
     * @return string
     */
    public function createFlowNode() {
        $res = $this->flow->createFlowNode();
        if($res)
            return $this->response();
    }

    public function listFlowNode() {
        $res = $this->flow->listFlowNode();
        $this->setResponse($res);
        return $this->response();
    }
}