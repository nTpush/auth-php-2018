<?php

namespace App\Http\Controllers\Api;

use App\Events\AccessTokenCreated;
use App\Events\News;
use App\Events\NodeSocketEvent;
use App\Events\PostSaved;

use App\Http\Services\Api\UploadService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;
use JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TestController extends Controller
{
    //

    public function index(Request $request) {
//        $info = DB::table('test')->get();
        $credentials = $request->only('user_email', 'user_password');
        try{
            $token = JWTAuth::attempt($credentials);

            if (!$token){
                return response()->json(['error'=>'invalid_credentials'],401);
            }
        } catch(JWTException $e){
            return response()->json(['error'=>'invalid_credentials'],500);
        }
        return response()->json(compact('token'));
    }



    // 递归查询子类id
    public function getAllUser($userId)
    {
        $userIds = [$userId];
        $users = DB::table('base_book_user_relat')->where('pid', $userId)->get();
        foreach ($users as $user) {
            $childUsers = DB::table('base_book_user_relat')->where('pid', $user->user_id)->get();
            if (count($childUsers) > 0)
                $userIds = array_merge($userIds, $this->getAllUser($user->user_id));
            else
                $userIds[] = $user->user_id;
        }
        return $userIds;
    }
    /**
     * 仅测试
     * @return int
     */
    public function test() {

//        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9xdjQ1cXkubmF0YXBwZnJlZS5jY1wvYXBpXC9sb2dpbiIsImlhdCI6MTUzNTUyODA2NywiZXhwIjoxNTM1NTMxNjY3LCJuYmYiOjE1MzU1MjgwNjcsImp0aSI6IlNhbWtOMFZjZnhCWkhGV0QiLCJzdWIiOjEsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.vXNAu_TBtcr9i4D9mYtheCEKdYae7rlKF20G1CIZ4jU"
//
//            $a = base64_decode($token);
//
//        print_r($a);
//        die;
        dd($this->getAllUser(13));
        /**
         * 小钟
         */
        $id = 1;
        dump('方法一');
        $allBooks = DB::table('auth_book')->whereIn('add_user_id', $this->getAllUser($id))->get()->toArray();
        dump($allBooks);
        dump('方法二');
        $books = DB::table('auth_book_person')->where('users_id', 'like', '%' . $id . '%')
                                              ->leftJoin('auth_book', 'auth_book.id', '=', 'auth_book_person.book_id')
                                              ->select('auth_book.id', 'auth_book.book_name', 'auth_book.book_info', 'auth_book.add_user_id')
                                              ->get();
        dump($books);

//        $id = 2;
//        $data = self::search($id);
//         $person = DB::table('auth_person')->get();

//         while ($isbool) {
//             $data = DB::table('auth_person')->where('pid',  '=' ,$id)->get();
//             count($data) === 0 ? $isbool = false : $isbool = true;
//             foreach ($data as $key => $val) {
//                 $arr[] = $val->id;
//                 $id = $val->id;
//             }
//         }



//        $data = [
//            'event' => 'UserSignedUp',
//            'data' => [
//                'username' => 'JohnDoe'
//            ]
//        ];
////        Event::fire(new NodeSocketEvent('shining'));
//
//        Redis::publish('test-channel', json_encode($data));
//        $this->setResponse([
//            'name' => $data
//        ]);
//        return $this->response();



//	$client = new Client();
//	$res = $client->request('GET', 'http://127.0.0.1:3001/socket', [




//]);

//
//
//
//
//

	
//        $upload = new UploadService();
//        $upload->index();

//        $res = DB::table('base_test_menu')
  //          ->leftJoin('base_test_node', 'base_test_menu.id', '=', 'base_test_node.menu_id')->get();

    //    return $res;
//        dd('10086');
      //  Event::fire(new AccessTokenCreated());
    }
}
