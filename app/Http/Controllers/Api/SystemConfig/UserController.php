<?php

namespace App\Http\Controllers\Api\SystemConfig;



use App\Components\Classes\Helper;
use App\Http\Requests\PasswordPostRequest;
use App\Http\Requests\UserPostRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = $this->userModel->getUserList();

        foreach ($data as $key => $value) {
            $value['roles'] = DB::table('base_user_role')->where('user_id', $value->id)
                                ->select('base_role.role_name', 'base_role.id')
                                ->leftJoin('base_role', 'base_role.id', '=', 'base_user_role.role_id')
                                ->get();
        }

        $this->setResponse($data);
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
     * @param UserPostRequest $request
     * @return string
     */
    public function store(UserPostRequest $request)
    {
        //
        $user = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'created_at' => Helper::qgmdate()
        ];

        $create = $this->userModel->createUser($user);
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
        $detail = $this->userModel->detailUser($id);



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
     * @param PasswordPostRequest $request
     * @param $id
     * @return string
     */
    public function update(PasswordPostRequest $request, $id)
    {
        //
        $password = [
            'password' => bcrypt($request->input('password'))
        ];
        $edit = $this->userModel->editPasswordUser($password, $id);
        if ($edit) {
            return $this->response();
        }
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
    }
}
