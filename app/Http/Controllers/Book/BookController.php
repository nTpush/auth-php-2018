<?php

namespace App\Http\Controllers\Book;


use App\Components\Classes\Helper;
use App\Models\BaseUser;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    private $userModel;
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
        $user = Auth::user();
        $id = $user->id;

        $data = DB::table('user_chidren_relation')->where('user_id', $id)
            ->leftJoin('users', 'users.id', '=', 'user_chidren_relation.cids')
            ->select('users.name', 'users.email')
            ->orderBy('created_at', 'desc')
            ->paginate();

        $this->setResponse($data);
       return $this->response();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $user = Auth::user();
        $id = $user->id;

        $data = DB::table('user_chidren_relation')->where('user_id', $id)->get();

        $this->setResponse(33);
        return $this->response();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        print_r(session()->getId());
        $user = Auth::user();
        $id = $user->id;
        $jobId = $user->job_id;

        $jId = null;
        switch ($jobId) {
            case 1:
                $jId = 2;
                break;
            case 2:
                $jId = 3;
                break;
            case 3:
                $jId = 4;
                break;
            default:
                $jId = 5;
                break;
        }

        $ids = $this->getParentId($id);

        //
        $user = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'created_at' => Helper::qgmdate(),
            'job_id' => $jId,
            'type' => 1
        ];
        // 向用户表插入数据
        $user_id = $this->userModel->createUser($user);
        $userRelat = [
            'user_id' => $user_id,
            'pid' => $id
        ];
        if($user_id) {
            // 建立用户的上下级关系
            DB::table('user_job_relation')->insert($userRelat);

            // 为父级添加用户id
            foreach ($ids as $key => $id) {
                $relation = [
                    'user_id' => $id,
                    'cids' => $user_id

                ];
                // 为父级添加用户id
                DB::table('user_chidren_relation')->insert($relation);
            }
        }
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

    // 递归查询子类id
    protected function getAllUser($userId)
    {
        $userIds = [$userId];
        $users = DB::table('user_job_relation')->where('pid', $userId)->get();
        foreach ($users as $user) {
            $childUsers = DB::table('user_job_relation')->where('pid', $user->user_id)->get();
            if (count($childUsers) > 0)
                $userIds = array_merge($userIds, $this->getAllUser($user->user_id));
            else
                $userIds[] = $user->user_id;
        }
        return $userIds;
    }

    // 递归获取父级id
    protected function getParentId($userId)
    {
        $pIds = [$userId];
        $user = DB::table('user_job_relation')->where('user_id', $userId)->first();

        if($user) {
            if($user->pid == 13)
                $pIds[] = $user->pid;
            else
                $pIds = array_merge($pIds, $this->getParentId($user->pid));
            return $pIds;
        }
        return $pIds;
    }
}
