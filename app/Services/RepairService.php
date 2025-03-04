<?php

namespace App\Services;

use App\Models\Customers;
use App\Models\Products;
use App\Models\Repairs;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class RepairService
 * @package App\Services
 */
class RepairService extends BaseService
{
    public function createRepair($request){
        try {
            DB::beginTransaction();
            $customer = Customers::create([
                "name" => $request->name_customer,
                "email" => $request->email,
                "phone" => $request->phone,
                "address" => $request->address,
                "type" => 1,
            ]);

            $repair = Repairs::create([
                "customer_id" => $customer->id,
                "repair_content" => $request->content,
                "status" => 0,
                "start_guarantee" => $request->start_guarantee,
                "end_guarantee" => $request->end_guarantee,
            ]);
            DB::commit();
            return $repair;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
    }

    public function searchRepair($request)
    {
        try {
            $params = $request->only('keyword', 'service_search');
            $data = Repairs::all();

            foreach ($data as $value) {
                if($value->end_guarantee < Carbon::now()){
                    $value->status = 1;
                    $value->save();
                }
            }

            $query = Repairs::leftJoin('customers', 'customers.id', '=', 'repairs.customer_id')
                            ->select('repairs.*', 'customers.name as customer_name', 'customers.phone', 'customers.address', 'customers.email', 'customers.type');

            if (isset($params['keyword'])) {
                $keyword = $params['keyword'];
                $query->where(function ($query) use ($keyword) {
                    $query->where('customers.name', 'LIKE', "%{$keyword}%")
                        ->orWhere('customers.phone', 'LIKE', "%{$keyword}%");
                });
            }
            return $query->paginate(10);
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }


    public function updateRepair($request){
        try {
            DB::beginTransaction();
            Repairs::find($request->id_repair)->update([
                'repair_content' => $request->content,
                'start_guarantee' => $request->start_guarantee,
                'end_guarantee' => $request->end_guarantee,
            ]);

            Customers::find($request->id_customer)->update([
                'name' => $request->name_customer,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'type' => $request->type,
            ]);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    public function deleteRepair($id){
        try {
            $repair = Repairs::find($id);
            Customers::find($repair->customer_id)->delete();
            return $repair->delete();
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}
