<?php
/**
 * Created by PhpStorm.
 * User: 10959
 * Date: 2018/9/5
 * Time: 15:57
 */

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;

class BookFlowNode extends Model
{
    protected $table = 'tbl_flow_node';

    protected $primaryKey = 'flow_node_id';
}