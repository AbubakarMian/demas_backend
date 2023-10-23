<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\SaleAgent;
use App\Models\Slot;
use App\Models\Transport;
use App\Models\TransportPrices;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use App\Models\Users;
use Illuminate\Http\Request;

class OrderHandler
{
    use Common, APIResponse;

    public function gernerate_pdf_order($order,$order_details){
        
        $pdf = PDF::loadView('pdf.invoice', [
            'order' => $order,
            'order_details' =>  $order_details,
        ]);
    
        // Set the paper size to A4 and the orientation to portrait
        $pdf->setPaper('a4', 'portrait');
    
        $pdfPath = public_path('invoice/'.$order->order_id.'pdf');
    
        // Save the PDF to the public/invoice directory
        $pdf->save($pdfPath);
    
        // Return a response with a link to the saved PDF
        return[
            'stream'=>$pdf->stream($pdfPath),
            'path'=>$pdfPath
        ] ;
        // return $pdf->stream('admin_invoice.pdf');
    }

}
