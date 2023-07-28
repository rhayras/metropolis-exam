<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Logs;
use DataTables;
use Validator;

class LogsController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $dateFilter = $request->dateFilter;

            $data = Logs::when($dateFilter, function ($query, $dateFilter) {
                return $query->where('created_at', 'LIKE', $dateFilter . '%');
            })->get();

            return Datatables::of($data)->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('mobile_no', function ($row) {
                    return $row->mobile_no;
                })
                ->addColumn('is_walk_in', function ($row) {
                    if ($row->is_walk_in == 1) {
                        return "Walk In";
                    } else {
                        return $row->vehicle_no;
                    }
                })
                ->addColumn('purpose', function ($row) {
                    return $row->purpose;
                })
                ->addColumn('check_in', function ($row) {
                    return date('M d, Y h:i A', strtotime($row->check_in));
                })
                ->addColumn('check_out', function ($row) {
                    if ($row->check_out == NULL) {
                        return "<button class='btn btn-success btn-sm btn-checkout' data-id='" . $row->id . "'>Check-out</button>";
                    } else {
                        return date('M d, Y h:i A', strtotime($row->check_out));
                    }
                })
                ->rawColumns(['name', 'mobile_no', 'is_walk_in', 'purpose', 'check_in', 'check_out', 'action'])
                ->make(true);
        }
    }

    public function saveLog(Request $request)
    {
        $input = $request->all();
        $date = date('Y-m-d');
        $checkIn = date('Y-m-d H:i:s');

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile_no' => 'required|numeric|digits:8',
            'purpose' => 'required'
        ]);

        if ($validator->passes()) {
            $log = new Logs;
            $condition = [
                'name' => $input['name'],
                'mobile_no' => $input['mobile_no']
            ];
            $checkIfExist = Logs::where($condition)
                ->where('created_at', 'LIKE', $date . "%")
                ->first();
            if (!empty($checkIfExist)) {
                $result['success'] = false;
                $result['msg'] = "Visitor already exist.";
            } else {
                $log->name = $input['name'];
                $log->mobile_no = $input['mobile_no'];
                $log->purpose = $input['purpose'];
                $log->is_walk_in = $input['is_walk_in'];
                $log->vehicle_no = ($input['is_walk_in'] == 0) ? $input['vehicle_no'] : NULL;
                $log->check_in = $checkIn;

                if ($log->save()) {
                    $result['success'] = true;
                } else {
                    $result['success'] = false;
                    $result['msg'] = "Something went wrong. Please try again";
                }
            }
            return response()->json($result);
        }

        return response()->json(['error' => $validator->errors()->all()]);
    }

    public function saveCheckout(Request $request)
    {
        $result = array();
        $id = $request->id;

        $update = Logs::where('id', $id)
            ->update(['check_out' => date('Y-m-d H:i:s')]);
        if ($update) {
            $result['success'] = true;
        } else {
            $result['success'] = false;
            $result['msg'] = "Something went wrong. Please try again";
        }

        return response()->json($result);
    }
}
