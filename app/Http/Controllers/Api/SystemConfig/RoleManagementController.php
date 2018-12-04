<?php

namespace App\Http\Controllers\Api\SystemConfig;

use App\Http\Services\SystemConfig\RoleManagementService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleManagementController extends Controller
{
    protected $roleService ;

    public function __construct()
    {
        $this->roleService = new RoleManagementService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list = $this->roleService->listRole();

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
        $info = $this->roleService->createRole($request->all());
        if($info) {
            return $this->response();
        }
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

        $data = $this->roleService->detailRole($id);
        $this->setResponse($data);
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
        //
        $data = $this->roleService->updateRole($request, $id);

        $this->setResponse($data);
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
        $this->roleService->delRoles($id);

        return $this->response();
    }
}
