<?php

namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * 自定义用Passport授权登录：用户名+密码
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        return self::where('name', $username)->first();
    }

    public function getUserName($id) {
        return self::find($id);
    }


    public function getUserList() {
        return $this->queryUser()->paginate();
    }

    /**
     * 条件查询
     * @return mixed
     */
    public function queryUser() {
        $query = $this->orderBy('created_at', 'desc');
        /**
         * 资源名查询
         */
//        if (request()->get('resource_name'))
//            $query->where(function ($query) {
//                $query->where('resource_name', 'like', '%' . request()->get('resource_name') . '%');
//            });
        return $query;
    }

    /**
     * 创建用户
     * @param $request
     * @return mixed
     */
    public function createUser($request) {
        return $this->insertGetId($request);
    }

    /**
     * 用户详情
     * @param $id
     * @return mixed
     */
    public function detailUser($id) {
        return $this->where('id', $id)->first();
    }

    public function editPasswordUser($request, $id) {
        return $this->where('id', $id)->update($request);

    }
}
