<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/31
 * Time: 10:38
 */

namespace App\Http\Services\Node;


use App\Http\Services\Service;
use App\Models\Node\BaseBarrage;

class BarrageService extends Service
{
    protected $barrage;

    public function __construct()
    {
        $this->barrage = new BaseBarrage();
    }

    public function brarrageList() {
        return $this->barrage->barrageList();
    }

    public function barrageCreate($request) {
        return $this->barrage->barrageCreate($request);
    }
}