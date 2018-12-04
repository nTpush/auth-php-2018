<?php

namespace App\Http\Controllers\Api;

use App\Components\Classes\Helper;
use App\Http\Controllers\Controller;
use App\Models\BaseTodolist;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    protected $todo;

    public function __construct()
    {
        $this->todo = new BaseTodolist();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 未完成
        $undones = $this->todo->listTodoList(1);

//----------
//        $res = [];
//        foreach ($undones as $key => $val) {
//            $y =  Helper::qgmdate('Y', strtotime($val->created_at));
//            $m =  Helper::qgmdate('m', strtotime($val->created_at));
//            $d =  Helper::qgmdate('d', strtotime($val->created_at));
//            $h =  Helper::qgmdate('H:i:s', strtotime($val->created_at));
//            $res[$y][$m][$d][$h] = $val;
//        }
//        $return = [];
//        foreach ($res as $key => $val) {
//            $return[$key]['year'] =  $key;
//            $return[$key]['monthList'] = $val;
//            foreach ($return[$key]['monthList'] as $monthKey => $monthItem) {
//                unset($return[$key]['monthList'][$monthKey]);
//                $return[$key]['monthList']['month'] = $monthKey;
//                $return[$key]['monthList']['dayList'] =$monthItem;
//                foreach ($return[$key]['monthList']['dayList'] as $dayKey => $dayItem) {
//                    unset($return[$key]['monthList']['dayList'][$dayKey]);
//                    $return[$key]['monthList']['dayList']['day'] = $dayKey;
//                    $return[$key]['monthList']['dayList']['timeList'] = collect($dayItem)->values()->toArray();
//                }
//            }
//        }
//        $return = collect($return)->values()->toArray();
//        $this->setResponse($return);
//        return $this->response();
//----------

//        $undonesres = [];
//        foreach ($undones as $key => $val) {
//            $res = new  \stdClass();
//            $res = $val;
//            $res->y = Helper::qgmdate('Y', strtotime($val->created_at));
//            $res->m = Helper::qgmdate('m', strtotime($val->created_at));
//            $res->d = Helper::qgmdate('d', strtotime($val->created_at));
//            $undonesres[$res->y][] = $res;
//        }
//        $res2 =[];
//        foreach ($undonesres as $y=>$item){
//            $groupbyList = collect($item)->groupBy('m');
//            $month=[];
//            foreach ($groupbyList as $m=>$item2){
//                $groupbyList2 = collect($item2)->groupBy('d');
//                $day=[];
//                foreach ($groupbyList2 as $d=>$item3){
//                    $day[]=[
//                        'day'=>$d,
//                        'day_list'=>$item3
//                    ];
//                }
//                $month[]=[
//                  'month'=>$m,
//                  'day_list'=>  $day
//                ];
//            }
//            $res2[]=[
//                'year'=>$y,
//                'month_list'=>$month,
//            ];
//        }
//
//        $this->setResponse([
//            'undone' => $res2
//        ]);
//        return $this->response();
//        print_r($res2);die;



        $unhold = [];
        foreach ($undones as $key => $val) {
            $d = Helper::qgmdate('Y-m-d', strtotime($val->created_at));
            $unhold[$d]['date'] = $d;
            $unhold[$d]['todo'][$key] = $val;
        }
        $undone = [];
        foreach ($unhold as $key => $val) {
            $undone[$key]['date'] = $val['date'];
            $undone[$key]['todo'] = collect($val['todo'])->values()->toArray();
        }
        // 已完成
        $dones = $this->todo->listTodoList(2);
        $hold = [];
        foreach ($dones as $key => $val) {
            $d = Helper::qgmdate('Y-m-d', strtotime($val->updated_at));
            $hold[$d]['date'] = $d;
            $hold[$d]['todo'][$key] = $val;
        }
        $done = [];
        foreach ($hold as $key => $val) {
            $done[$key]['date'] = $val['date'];
            $done[$key]['todo'] = collect($val['todo'])->values()->toArray();
        }


        $this->setResponse([
            'undone' => collect($undone)->values()->toArray(),
            'done' => collect($done)->values()->toArray()
        ]);
        return $this->response();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $this->todo->createTodoList($request->all());

        return $this->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $componentTime = Helper::qgmdate('Y-m-d H:i:s', time());

        $status = $this->todo->listDetail($id)->status;

        $update = [
            'computed_at' => $componentTime,
            'status' => $status == 1 ? 2: 1
        ];

        $this->todo->listUpdate($update, $id);

        return $this->response();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        $this->todo->listDelete($id);

        return $this->response();
    }
}
