<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/31
 * Time: 10:41
 */

namespace App\Http\Controllers\Node;


use App\Http\Controllers\Controller;
use App\Http\Services\Node\BarrageService;

class BarrageController extends Controller
{
    protected $barrage;

    public function __construct()
    {
        $this->barrage = new BarrageService();
    }

    public function index() {
        $data = $this->barrage->brarrageList();
        $collection = collect($data);
        $this->setResponse($data);
        return $this->response();
    }
}