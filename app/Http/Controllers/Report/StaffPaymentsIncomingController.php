<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\StaffPaymentsIncoming;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class StaffPaymentsIncomingController extends Controller
{


    public function index(Request $request)
    {
        return view('reports.staff_payments_incoming.index');
    }


    public function get_staff_payments_incoming(Request $request)
    {
        $staff_payments_incoming = StaffPaymentsIncoming::with('user_obj.role')->orderBy('created_at', 'DESC')->select('*')->get();
        $staff_payments_incomingData['data'] = $staff_payments_incoming;
        echo json_encode($staff_payments_incomingData);
    }


   
    public function create()
    {
        $control = 'create';
        $user = User::pluck('name','id');
        $payment_type = Config::get('constants.staff_payment_type');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('reports.staff_payments_incoming.create', compact(
        'control', 
        'user',
        'payment_type',
    ));
    }

    public function save(Request $request)
    {
        $staff_payments_incoming = new StaffPaymentsIncoming();
        return $this->add_or_update($request, $staff_payments_incoming);

        return redirect('reports/staff_payments_incoming');
    }
    public function edit($id)
    {
        $control = 'edit';
        $user = User::pluck('name','id');
        $payment_type = Config::get('constants.staff_payment_type');
        $staff_payments_incoming = StaffPaymentsIncoming::find($id);
        // dd($staff_payments_incoming->images);
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('reports.staff_payments_incoming.create', compact(
            'control',
            'staff_payments_incoming',
            'user',
            'payment_type',

        )
        );
    }

    public function update(Request $request, $id)
    {
        $staff_payments_incoming = StaffPaymentsIncoming::find($id);
        // StaffPaymentsIncoming::delete()
        return $this->add_or_update($request, $staff_payments_incoming);
        return Redirect('reports/staff_payments_incoming');
    }


    public function add_or_update(Request $request, $staff_payments_incoming)
    {
        // dd(json_encode($request->staff_payments_incoming_images_upload));
       
        $staff_payments_incoming->user_id = $request->user_id;
        $staff_payments_incoming->amount = $request->amount;
        $staff_payments_incoming->payment_type = $request->payment_type;
        $staff_payments_incoming->detail = $request->detail;
        $staff_payments_incoming->verification_status = 'pending';
        $staff_payments_incoming->save();
        return Redirect('reports/staff_payments_incoming');
    }

    public function destroy_undestroy($id)
    {
        $staff_payments_incoming = StaffPaymentsIncoming::find($id);
        if ($staff_payments_incoming) {
            StaffPaymentsIncoming::destroy($id);
            $new_value = 'Activate';
        } else {
            StaffPaymentsIncoming::withTrashed()->find($id)->restore();
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
