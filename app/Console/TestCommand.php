<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/7/5
 * Time: 14:51
 */

namespace App\Console;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class TestCommand extends Command
{
    protected $name = 'test:putcache';

    protected $description = 'test controller';

    public function handle() {
        $data = [
            'name' => 'shining'
        ];

        Redis::publish('test-channel', json_encode($data));
        Log::info('任务调度' . time());
    }
}