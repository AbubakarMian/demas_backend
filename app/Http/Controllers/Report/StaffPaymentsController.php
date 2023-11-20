<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\StaffPayments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class StaffPaymentsController extends Controller
{

    public function index(Request $request)
    {
        return view('reports.staff_payments.index');
    }


    public function get_staff_payments(Request $request)
    {
        $staff_payments = StaffPayments::with('user_obj.role')->orderBy('created_at', 'DESC')->select('*')->get();
        $staff_paymentsData['data'] = $staff_payments;
        echo json_encode($staff_paymentsData);
    }


   
    public function create()
    {
        $control = 'create';
        $user = User::pluck('name','id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('reports.staff_payments.create', compact('control', 
        'user'
    ));
    }

    public function save(Request $request)
    {
        $staff_payments = new StaffPayments();
        return $this->add_or_update($request, $staff_payments);

        return redirect('reports/staff_payments');
    }
    public function edit($id)
    {
        $control = 'edit';
        $user = User::pluck('name','id');
        $staff_payments = StaffPayments::find($id);
        // dd($staff_payments->images);
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('reports.staff_payments.create', compact(
            'control',
            'staff_payments',
            'user',

        )
        );
    }

    public function update(Request $request, $id)
    {
        $staff_payments = StaffPayments::find($id);
        // StaffPayments::delete()
        return $this->add_or_update($request, $staff_payments);
        return Redirect('reports/staff_payments');
    }


    public function add_or_update(Request $request, $staff_payments)
    {
        // dd(json_encode($request->staff_payments_images_upload));
       
        $staff_payments->user_id = $request->user_id;
        $staff_payments->amount = $request->amount;
        $staff_payments->save();
        return Redirect('reports/staff_payments');
    }

    public function destroy_undestroy($id)
    {
        $staff_payments = StaffPayments::find($id);
        if ($staff_payments) {
            StaffPayments::destroy($id);
            $new_value = 'Activate';
        } else {
            StaffPayments::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }
}
