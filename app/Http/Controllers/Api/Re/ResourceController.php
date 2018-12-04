<?php

namespace App\Http\Controllers\Api\Re;

use App\Http\Services\Re\ResourceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResourceController extends Controller
{
     protected $resourService;

     public function __construct()
     {
         $this->resourService = new ResourceService();
     }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->resourService->resourceList();
        $this->setResponse($list);
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

        $this->resourService->resourceCreate($request->all());
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
        $arr_id = explode('@', $id);
        $id = $arr_id[0];
        $type = $arr_id[1];

        $list = $this->resourService->resourceSearchDetail($id, $type);

        $this->setResponse($list);
        return $this->response();
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
//        return $this->response();
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
        $this->resourService->resourceEdit($request->all(), $id);
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
        $res = $this->resourService->resourceDel($id);

        if($res) {
            $this->setResponse([]);
        }else {
            $this->setResponse([], false,'不允许直接删除父节点');
        }
        return $this->response();

    }

    public function menuDefault() {
        return 3;
    }
}
