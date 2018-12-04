<?php
/**
 * Created by PhpStorm.
 * User: shining
 * Date: 2018/6/26
 * Time: 7:52
 */

namespace App\Models\Re;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class BaseResource extends Model
{
    protected $table = 'base_resource';

    protected $fillable = [];

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $guarded = [];   //黑名单

    public function resourceList()
    {
        return DB::table('base_resource')
            ->orderBy('resource_order', 'asc')
            ->leftJoin('base_resource_node', 'base_resource.id', '=', 'base_resource_node.menu_id')
            ->get();
    }

    public function resourceCreate($request)
    {
        return $this->create($request);
    }


    public function resourceEdit($request, $id)
    {
        return $this->where([
            $this->primaryKey => $id
        ])->update($request);
    }


    public function countChild($id) {
        $data = DB::table('base_resource')->where('resource_parent_id', $id)->first();
        if($data) {
            return false;
        }else {
            $dataNode = DB::table('base_resource_node')->where('menu_id', $id)->first();
            if($dataNode) {
                return false;
            }
            return true;
        }
    }


    public function delMenu($id) {
        DB::table('base_role_resource')->where('resource_id', $id)->delete();
        return $this->where([
            $this->primaryKey =>$id
        ])->delete();
    }

    public function resourceDel($id)
    {

//        DB::beginTransaction();
//            try {
//                $this->where([
//                    $this->primaryKey => $id
//                ])->delete();
//                DB::table('base_resource_node')->where('menu_id', $id)->delete();
//                return DB::commit();
//            }catch (Exception $e) {
//                DB::rollBack();
//            }
    }

}