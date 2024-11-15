<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\CommissionHandler;
use App\Models\StaffPayments;
use App\Models\StaffPaymentsIncoming;
use App\Models\Users;
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
        $user = Users::pluck('name', 'id');
        $payment_type = Config::get('constants.staff_payment_type');
        return view('reports.staff_payments.create', compact(
            'control',
            'user',
            'payment_type',
        ));
    }

    public function save(Request $request)
    {
        $user_id =$request->user_id;
        $commision_handler = new CommissionHandler();
        $staff_payments = new StaffPayments();
        // dd($request->payment_type);
        if($request->payment_type == 'withdrawal'){ //cash widdrawl
            $commision_handler->withdrawal_agent_payment_from_wallet($user_id, $request->amount);
        }
        else{//agent_cash_deposit
            // $this->add_amount_to_agent_wallet( $request, $staff_payments->user_id);
            $commision_handler->add_agent_payment_to_wallet($user_id, $request->amount);
            $user = $commision_handler->charge_order_payments_from_agents($user_id);
            // return $this->sendResponse(200,$user);
            $user = $commision_handler->pay_commission_team($user_id, $request->amount);

        }
        $staff_payment = $this->add_or_update($request, $staff_payments);

        // $this->pay_team($request, $request->user_id);
        return $staff_payment;
        // return redirect('reports/staff_payments');
    }
    public function edit($id)
    {
        $control = 'edit';
        $user = Users::pluck('name', 'id');
        $payment_type = Config::get('constants.staff_payment_type');
        $staff_payments = StaffPayments::find($id);
        return view(
            'reports.staff_payments.create',
            compact(
                'control',
                'staff_payments',
                'user',
                'payment_type',
            )
        );
    }

    public function update(Request $request, $id)
    {
        $staff_payments = StaffPayments::find($id);
        return $this->add_or_update($request, $staff_payments);
        return Redirect('reports/staff_payments');
    }


    public function add_or_update(Request $request, $staff_payments)
    {
        $staff_payments->user_id = $request->user_id;
        $staff_payments->amount = $request->amount;
        $staff_payments->payment_type = $request->payment_type;
        $staff_payments->detail = $request->detail;
        $staff_payments->receipt_url = $request->recipt_url;

        $staff_payments->save();
        // dd($request->all());

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

    public function confirm_reject_payment_collection(Request $request, $staff_payment_verification_id)
    {
        $staff_payment = StaffPaymentsIncoming::find($staff_payment_verification_id);
        $user_id = $staff_payment->user_id;
        if($request->status == 'accepted'){

            $commision_handler = new CommissionHandler();
            $commision_handler->add_agent_payment_to_wallet($user_id, $request->amount);
            $user = $commision_handler->charge_order_payments_from_agents($user_id);
        }
        // dd($user);
        return $this->sendResponse(200,$user);
    }

    public function pay_team(Request $request, $user_id)
    {
        $commision_handler = new CommissionHandler();
        // $user = $commision_handler->pay_commission_team($user_id, $request->amount);
        $user = $commision_handler->pay_commission_team($user_id);
        // dd($user);
        return $this->sendResponse(200,$user);
    }
    // public function add_amount_to_agent_wallet(Request $request, $user_id)
    // {
    //     $commision_handler = new CommissionHandler();
    //     $commision_handler->add_agent_payment_to_wallet($user_id, $request->amount);
    //     $user = $commision_handler->charge_order_payments_from_agents($user_id);
    //     return $this->sendResponse(200,$user);
    // }
}
