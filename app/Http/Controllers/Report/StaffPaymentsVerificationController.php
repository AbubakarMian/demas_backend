<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\StaffPaymentsIncoming;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class StaffPaymentsVerificationController extends Controller
{

    public function index(Request $request)
    {
        return view('reports.staff_payments_verification.index');
    }


    public function get_staff_payments_verification(Request $request)
    {
        $staff_payments_verification = StaffPaymentsIncoming::with('user_obj.role')->orderBy('created_at', 'DESC')->select('*')->get();
        $staff_payments_verificationData['data'] = $staff_payments_verification;
        echo json_encode($staff_payments_verificationData);
    }


   
    public function create()
    {
        $control = 'create';
        $user = User::pluck('name','id');
        $payment_type = Config::get('constants.staff_payment_type');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('reports.staff_payments_verification.create', compact(
        'control', 
        'user',
        'payment_type',
    ));
    }

    public function save(Request $request)
    {
        $staff_payments_verification = new StaffPaymentsIncoming();
        return $this->add_or_update($request, $staff_payments_verification);

        return redirect('reports/staff_payments_verification');
    }
    public function edit($id)
    {
        $control = 'edit';
        $user = User::pluck('name','id');
        $payment_type = Config::get('constants.staff_payment_type');
        $staff_payments_verification = StaffPaymentsIncoming::find($id);
        // dd($staff_payments_verification->images);
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('reports.staff_payments_verification.create', compact(
            'control',
            'staff_payments_verification',
            'user',
            'payment_type',

        )
        );
    }

    public function update(Request $request, $id)
    {
        $staff_payments_verification = StaffPaymentsIncoming::find($id);
        // StaffPaymentsIncoming::delete()
        return $this->add_or_update($request, $staff_payments_verification);
        return Redirect('reports/staff_payments_verification');
    }


    public function add_or_update(Request $request, $staff_payments_verification)
    {
        // dd(json_encode($request->staff_payments_verification_images_upload));
       
        $staff_payments_verification->user_id = $request->user_id;
        $staff_payments_verification->amount = $request->amount;
        $staff_payments_verification->payment_type = $request->payment_type;
        $staff_payments_verification->detail = $request->detail;
        $staff_payments_verification->verification_status = 'pending';
        $staff_payments_verification->save();
        return Redirect('reports/staff_payments_verification');
    }

    public function destroy_undestroy($id)
    {
        $staff_payments_verification = StaffPaymentsIncoming::find($id);
        if ($staff_payments_verification) {
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
    }}
