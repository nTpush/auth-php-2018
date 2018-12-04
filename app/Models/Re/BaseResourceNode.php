<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/27
 * Time: 9:27
 */

namespace App\Models\Re;


use Illuminate\Database\Eloquent\Model;

class BaseResourceNode extends Model
{
    protected $table = 'base_resource_node';

    protected $fillable = [];

    protected $primaryKey = 'node_id';

    public $timestamps = true;

    protected $guarded = [];   //黑名单


    public function nodeCreate($request)
    {
        return $this->create($request);
    }

    public function nodeEdit($request, $id)
    {
        return $this->where([
            $this->primaryKey => $id
        ])->update($request);
    }


    public function nodeDel($id) {
        DB::table('base_role_resource')->where('resource_id', $id)->delete();

        return $this->where([
            $this->primaryKey => $id
        ])->delete();
    }
}