<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/31
 * Time: 10:38
 */

namespace App\Http\Services\Book;


use App\Http\Services\Service;
use App\Models\Book\BookFlow;
use App\Models\Book\BookFlowNode;
use Zend\Diactoros\Request;

class BookFlowService extends Service
{
    protected $flow;

    public function __construct()
    {
        $this->flow = new BookFlow();
        $this->flowNode = new BookFlowNode();
    }

    /**
     * 创建流程
     * @return mixed
     */
    public function createFlow()
    {
        $flow_no_info = $this->flow->orderBy('flow_no','desc')->first();
        $flow_no = $flow_no_info ? $flow_no_info->flow_no : 0;
        $data = request()->all();
        $data['flow_no'] = $flow_no + rand(1,99);
        return $this->flow->insert($data);
    }
    public function listFlow()
    {
        return $this->flow->get();
    }

    /**
     * 创建流程节点
     */
    public function createFlowNode()
    {
        $data = request()->all();
        return $this->flowNode->insert($data);
    }

    public function listFlowNode()
    {
        $flow_no = request()->input('flow_no');
        $res = $this->flowNode->where('tbl_flow_node.flow_no', '=', $flow_no)
            ->leftJoin('tbl_flow', 'tbl_flow.flow_no', '=', 'tbl_flow_node.flow_no')
            ->get();

        $return = [];
        foreach ($res as $key => $val) {
            $return['flow_name'] = $val->flow_name;
            $return['children'][$key]['flow_node_name'] = $val->flow_node_name;
            $return['children'][$key]['flow_node_role'] = $val->flow_node_role;
            $return['children'][$key]['flow_node_id'] = $val->flow_node_id;
        }
        return $return;
    }
}