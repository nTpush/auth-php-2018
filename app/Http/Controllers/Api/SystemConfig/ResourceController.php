<?php

namespace App\Http\Controllers\Api\SystemConfig;

use App\Components\Classes\Helper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\MenuPostRequest;
use App\Models\BaseResourceMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResourceController extends Controller
{

    const LEVEL_MAX = 3;

    protected $resouce;

    public function __construct()
    {
        $this->resouce = new BaseResourceMenu();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = $this->resouce->listResource();
        $tree = Helper::menuTree($list, true);
        $list = Helper::multiArrayToOne($tree);
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
     * @param MenuPostRequest $request
     * @return string
     *
     */
    public function store(MenuPostRequest $request)
    {

        //

        if($request['resource_parent_id']) {
            $level = $this->resouce->detailResource($request['resource_parent_id'])->level;
            if($level >= self::LEVEL_MAX) {
                $this->setResponse([], false, '目前仅支持3级，请重新选择归属菜单');
                return $this->response();
            }
        }

        $resuource = $this->resouce->createResource($request->all());

        if($resuource)
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
        $detail = $this->resouce->detailResource($id);
        $this->setResponse($detail);
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

        if($request['resource_parent_id']) {
            $level = $this->resouce->detailResource($request['resource_parent_id'])->level;
            if($level >= self::LEVEL_MAX) {
                $this->setResponse([], false, '目前仅支持3级，请重新选择父级菜单');
                return $this->response();
            }
        }

        $update = $this->resouce->editResource($request->all(), $id);
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
        $del = $this->resouce->deleteResource($id);
        return $this->response();
    }
}
