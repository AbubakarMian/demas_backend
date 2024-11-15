@extends('layouts.default_module')
@section('module_name')
    Orders
@stop

@section('add_btn')
    <div class="row">
        <form class="search_filter">
            <div class="col-md-3">
                {!! Form::select('journey_id', $journey_list, null, [
                    'class' => 'form-control',
                    'data-parsley-required' => 'true',
                    'data-parsley-trigger' => 'change',
                    'placeholder' => 'Select Journey',
                ]) !!}
            </div>
            <div class="col-md-3"> {!! Form::select('slot_id', $slot_list, null, [
                'class' => 'form-control',
                'data-parsley-required' => 'true',
                'data-parsley-trigger' => 'change',
                'placeholder' => 'Select Slot',
            ]) !!}</div>
            <div class="col-md-3">{!! Form::select('transport_type_id', $transport_type_list, null, [
                'class' => 'form-control',
                'data-parsley-required' => 'true',
                'data-parsley-trigger' => 'change',
                'placeholder' => 'Select Transport Type',
            ]) !!}</div>
            <div class="col-md-3">


                {!! Form::select('travel_agent_user_id', $travel_agent_list, null, [
                    'class' => 'form-control',
                    'data-parsley-required' => 'true',
                    'data-parsley-trigger' => 'change',
                    'placeholder' => 'Select Agent',
                ]) !!}
            </div>
        </form>
    </div>
    </div>
    <div class="search">
        {!! Form::button('Search', ['class' => 'btn btn-success pull-right', 'onclick' => 'fetchRecords()']) !!}

    </div>

@stop
@section('table-properties')
    width="400px" style="table-layout:fixed;"
@endsection



<style>
    td {
        white-space: nowrap;
        overflow: hidden;
        width: 30px;
        height: 30px;
        text-overflow: ellipsis;
    }

    .fhgyt th {
        border: 1px solid #e3e6f3 !important;
    }

    .fhgyt td {
        border: 1px solid #e3e6f3 !important;
        background: #f9f9f9
    }

    .modal_table th {
        padding: 10px;
    }

    .modal_table td {
        padding: 10px;
    }

    .btun {
        display: grid;
    }

    .modal-dialog {
        width: auto !important;
        padding: 10px !important;
        /* margin: 30px auto !important; */
        display: flex;
        justify-content: center;
    }
   

    /* Adjust the modal content for smaller screens */
    .modal-content {
        width: 100%;
    }

    /* Add any additional styling as needed */
    .modal-body {
        overflow-x: auto; /* Enable horizontal scrolling for small screens */
    }
    .cell_spc {
        width: 150px;
    }

    .icn_ar {
        color: #6ddf6d;
        font-size: 148px;
        width: auto;
    }

    .top_cross {
        text
    }

    .top_cross {
        text-align: right;
    }

    button.btn.btn-default.tp_crs {
        background: transparent;
        border: transparent;
        font-size: 18px;
        color: red;
    }
    .modal-dialog {
    /* width: 100%; */
    /* overflow: scroll; */
}

    .cus_btnsucc {
        background-color: #00afdd !important;
        color: white !important;
        padding: 2px !important;
        margin-bottom: 1px !important;
        border-radius: 5px !important;
    }

    .cus_btndang {
        background-color: red !important;
        color: white !important;
        padding: 2px !important;
        margin-bottom: 1px !important;
        border-radius: 5px !important;
    }
    .cus_btnprm {
        background-color: #00de89 !important;
        color: white !important;
        padding: 2px !important;
        margin-bottom: 1px !important;
        border-radius: 5px !important;
    }
#success_mdl2 {
    width: 500px;
    /* background-color: #fff; */
    border-radius: 10px;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    overflow: hidden !important;
}
#success_mdl {
    width: 500px;
    /* background-color: #fff; */
    border-radius: 10px;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    overflow: hidden !important;
}
.time_modal {
    width: 30%;
    border-radius: 10px;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(100%, 49px);
    overflow: hidden !important;
}
@media only screen and (max-width: 480px) {
    .time_modal {
    width: 100%;
        transform: translate(0, 187px);
    
}
#success_mdl2 {
    width: 100%;
 }

 }
</style>
@section('table')

    <table class="fhgyt" id="orderTableAppend" style="opacity: 0">
        <thead>
            <tr>
                <th>OrderId</th>
                <th>User</th>
                <th>Phone No</th>
                <th>Sale Agent</th>
                <th>Travel Agent</th>
                <th>Price</th>
                <th>Trip Type</th>
                <th>Order Status</th>
                <th>Detail</th>
                {{-- <th>Status </th> --}}
                <th>Invoice </th>
                {{-- <th>Time </th> --}}
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@stop
@section('app_jquery')

    <script>
        var order_list = [];
        var drivers_list = JSON.parse(`{!! json_encode($drivers_list) !!}`);
        var transport_list = JSON.parse(`{!! json_encode($transport_list) !!}`);;
        $(document).ready(function() {
            $('#orderTableAppend').DataTable({
                dom: '<"top_datatable"B>lftipr',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
            });
            fetchRecords();

            createModal({
                id: 'success_mdl',
                header: `<div class="top_cross">
                                <button type="button" class="btn btn-default tp_crs" data-dismiss="modal">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                        </div>`,
                body: `
                <center>
                <div class="icn_ar">
                    <i class="fa fa-check-circle-o" aria-hidden="true"></i>

                    </div>
                <div class="icn_ar_text">
                    <b>Success</b>

                    </div>
                    </center>
                `,
                footer: `
                
                `,
            });
        });

        function fetchRecords() {
            var formdata = $('.search_filter').serialize();
            $('#orderTableAppend').DataTable().destroy();
            $.ajax({
                url: '{!! asset('admin/order/get_order') !!}',
                type: 'post',
                data: formdata,
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                    $("#orderTableAppend").css("opacity", 1);
                    var len = response['data'].length;
                    console.log('response2 mian fetch');

                    $("#orderTableAppend tbody").html('');
                    order_list = response['data'];
                    for (var i = 0; i < len; i++) {
                        var id = response['data'][i].id;
                        var name = response['data'][i].user_obj?.name ?? '';
                        var user_phone = response['data'][i].user_obj?.phone_no ?? '';
                        var user_sale_agent_name = response['data'][i].sale_agent_user?.name ?? '';
                        var user_travel_agent_name = response['data'][i].travel_agent_user?.name ?? '';
                        var cash_collected_by = response['data'][i].cash_collected_by;
                        var cash_collected_by_user_id = response['data'][i].cash_collected_by_user_id;
                        var final_price = response['data'][i].final_price;
                        var type = response['data'][i].type;
                        var trip_type = response['data'][i].trip_type;
                        // var ispaid = response['data'][i].is_paid ? 'True' : 'False';
                        var ispaid = response['data'][i].is_paid ?
                            '<i class="fa fa-check" style="color:#38da38;" aria-hidden="true"></i>' :
                            '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
                        var status = response['data'][i].status;
                        var orderdetailsstatus = response['data'][i].orderdetailsstatus;
                        var payment_collected_type = response['data'][i].payment_collected_type;
                        var payment_collected_user_id = response['data'][i].payment_collected_user_id;
                        var payment_collected_price = response['data'][i].payment_collected_price;
                        var order_type = response['data'][i].order_type;
                        var payment_type = response['data'][i].payment_type;
                        var order_detail =
                            `<a class="btn btn-success" data-toggle="modal" data-target="#orderdetails"
                                onclick="get_details(` + id + `)">View</a>`;
                        var send_invoice =
                            '<a class="btn btn-info" onclick="sendInvoice(' + id + ');">Send Invoice</a>';

                        // var driver_info =
                        //     '<a class="btn btn-info" href="' + '{!! asset('reports/order/send_message') !!}/' + id +
                        //     '">Send</a>';

                        createModal({
                            // id: 'orderdetail_' + response['data'][i].id,
                            id: 'orderdetails',
                            class: 'responsive-modal-dialog',
                            header: '<h4>Order details</h4>',
                            body: `<center>
                                            <table class="modal_table fhgyt";>
                                                <thead>
                                                    <tr>
                                                        <th>Journey</th>
                                                        <th>PickUp</th>
                                                        <th>Customer Price</th>
                                                        <th>Price</th>
                                                        <th>Transport Type</th>
                                                        <th>Adult PAX</th>
                                                        <th>Infant PAX</th>
                                                        <th>Total PAX</th>
                                                        <th>Ticket</th>
                                                        <th>Driver</th>
                                                        <th>Transport</th>
                                                        <th></th>
                                                        <th>Status</th>
                                                        <th>E-PASS</th>
                                                        <th>Driver Info </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="orderdetails_list">
                                                </tbody>
                                            </table></center>`,
                            footer: `
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            `,
                        });
                        createModal({
                            id: 'order_' + response['data'][i].id,
                            header: '<h4>Confirm</h4>',
                            body: 'Do you want to continue ?',
                            footer: `
                                            <button class="btn btn-success" 
                                            onclick="change_status(` + response['data'][i].id + `,'confirm')"
                                            data-dismiss="modal">
                                                Confirm
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            `,
                        });
                        createModal({
                            id: 'reject_order_' + response['data'][i].id,
                            header: '<h4>Reject</h4>',
                            body: 'Do you want to continue ?',
                            footer: `
                                            <button class="btn btn-danger" 
                                            onclick="change_status(` + response['data'][i].id + `,'reject')"
                                            data-dismiss="modal">
                                                Reject
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            `,
                        });
                        var status = response['data'][i].status;
                        if (status == 'pending') {
                            var confirm_btn =
                                `<a class="btn btn-info" data-toggle="modal" data-target="#` +
                                'order_' + response['data'][i].id + `">Confirm</a>`;
                            var reject_btn =
                                `<a class="btn btn-danger" data-toggle="modal" data-target="#` +
                                'reject_order_' + response['data'][i].id + `">Reject</a>`;
                            status = confirm_btn + reject_btn;
                        } else {
                            var status = capitalize_first_letter(response['data'][i].status);
                        }
                        var tr_str = "<tr id='row_" + response['data'][i].id + "'>" +
                            "<td>" + response['data'][i].order_id + "</td>" +
                            "<td>" + name + "</td>" +
                            "<td>" + user_phone + "</td>" +
                            "<td>" + user_sale_agent_name + "</td>" +
                            "<td>" + user_travel_agent_name + "</td>" +
                            "<td>" + final_price + "</td>" +
                            "<td>" + capitalize_first_letter(trip_type) + "</td>" +
                            "<td>" + orderdetailsstatus + "</td>" +
                            "<td>" + order_detail + "</td>" +
                            // `<td id='td_status_` + response['data'][i].id + `'>` +
                            // status + `</td>` +
                            "<td>" + send_invoice + "</td>" +
                            // "<td>" + driver_info + "</td>" +
                            "</tr>";
                        $("#orderTableAppend tbody").append(tr_str);
                    }
                    $('#orderTableAppend').DataTable({
                        dom: '<"top_datatable"B>lftipr',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
                        order: [
                            [0, 'desc']
                        ],
                    });
                }
            });

        }

        function get_divers_transport_select(order_detail_item) {
            var order_detail_id = order_detail_item.id;
            console.log("hi i am her",order_detail_item)
            var selected = '';
            var drivers = `<select class="cell_spc select_driver_` + order_detail_item.id + `">
                                <option value="0">Select Driver</option>`;
            $.each(drivers_list, function(driver_index, driver_item) {
                selected = order_detail_item.driver_user_id == driver_item.driver_user_id ?
                    'selected' : '';
                drivers += `<option value="` + driver_item.driver_user_id + `" ` +
                    selected + `>` +
                    driver_item.user_obj_name + ` (` + driver_item.driver_category +
                    `) ` + `</option>`;
            })
            drivers += '</select>';
            var transport = `<select class="cell_spc select_transport_` + order_detail_id + `">
                                <option value="0">Select Transport</option>`;
            $.each(transport_list, function(transport_index, transport_item) {
                var selected_transport_id = order_detail_item.transport_id;
                selected = selected_transport_id == transport_item.id ?
                    'selected' : '';
                transport += `<option value="` + transport_item.id + `" ` +
                    selected + `>` +
                    transport_item.name + ` (` + transport_item.transport_type_name +
                    `) ` + `</option>`;
            })
            transport += '</select>';
            var update_btn = `
                <button class="btn cus_btnprm"
                onclick="update_order_transport_driver(` + order_detail_id + `,'.select_driver_` + order_detail_id +
                `','.select_transport_` + order_detail_id + `')">
                    Update</button>`;

            var voucher_btn = '<a class="btn cus_btnsucc" onclick="sendVoucher(' + order_detail_id + ');">Send</a>';

            // var voucher_btn = '<a class="btn btn-info" href="' + '{!! asset('reports/order/send_voucher') !!}/' + order_detail_id +
            //                 '">Send</a>';
            // <button class="btn btn-success"  href="' + '{!! asset('reports/order/send_voucher') !!}/' + id +
            //             '"
            // >
            //     Send</button>`;
        return {
            drivers,
            transport,
            voucher_btn,
            update_btn
        };
    }

    function get_details(order_id) {
        console.log('get_details order_id', order_id);
        $.ajax({
            url: "{!! asset('admin/order/details_list') !!}/" + order_id,
            type: 'GET',
            dataType: 'json',
            data: {
                _token: '{!! @csrf_token() !!}'
            },
            success: function(response) {
                $('.orderdetails_list').html('');
                console.log('response item get_details response ', response);
                if (response.status) {
                    var details_list = '';
                    $.each(response.response['order_details'], function(index, item) {
                        console.log('my time', format_date_time_from_timestamp(item
                            .pick_up_date_time)['time']);
                        console.log('mitem.is_pickup_time_set', item.is_pickup_time_set);
                        console.log('mitem.is_picsdad image kup_time_set', item.ticket_image);

                        var order_detail_ticket = item.ticket_image;
                                                console.log('mitem.is_picsdad image i am here',order_detail_ticket);


                        var time_btn =
                            `<a class="btn cus_btnsucc" data-toggle="modal" data-target="#driver_info_time_${item.id}">Send</a>`;
                        // var time_value = item.is_pickup_time_set ? format_date_time_from_timestamp(item.pick_up_date_time)['time'] :""; 
                        if(item.ticket_image){
                             var ticket_img = '<a class="btn cus_btnsucc" target="_blank" href="' + order_detail_ticket + '">View</a>';
                        }else{
                            var ticket_img = "";
                        }
                        

                        var time_value = item.is_pickup_time_set ? format_date_time_from_timestamp(
                            item.pick_up_date_time)['time'] : "";
                        createModal({
                            id: 'driver_info_time_' + item.id,
                                class: 'time_modal', // Add your custom class here
                            header: '<h4>Select PickUp Time</h4>',
                            body: `
                                                    <input type="time" class="form-control" id="manual_time_${item.id}" value="` +
                                time_value + `" name="manual_time">
                                                `,
                            footer: `
                                                <center>
                                                    <button type="button" class="btn btn-success" onclick="saveTime(${item.id})">Save & Send</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </center>
                                                `,
                        });

                        createModal({
                            id: 'confirm_order_detail_' + item.id,
                            header: '<h4>Confirm</h4>',
                            body: 'Do you want to continue ?',
                            class: 'time_modal',
                            footer: `
                                        <button class="btn btn-success" 
                                        onclick="change_order_detail_status(` + item.id + `,'confirm')"
                                        data-dismiss="modal">
                                            Confirm
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        `,
                        });
                        createModal({
                            id: 'cancel_order_detail_' + item.id,
                            header: '<h4>Cancel</h4>',
                            body: `<p>Do you want to cancel  ?</p> <p>
                                            <input type="text" placeholder="reason" id="reason_` + item.id + `">
                                        </p>`,
                                        class: 'time_modal',

                            footer: `
                                        <button class="btn btn-danger" 
                                        onclick="change_order_detail_status(` + item.id + `,'cancel')"
                                        data-dismiss="modal">
                                            Confirm
                                        </button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        `,
                        });
                        createModal({
                id: 'success_mdl2',
                header: `<div class="top_cross">
                                <button type="button" class="btn btn-default tp_crs" data-dismiss="modal">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                        </div>`,
                body: `
                <center>
                <div class="icn_ar">
                    <i class="fa fa-check-circle-o" aria-hidden="true"></i>

                    </div>
                <div class="icn_ar_text">
                    <b>Success</b>

                    </div>
                    </center>
                `,
                footer: `
                
                `,
            });
                        var status = item.status;
                        if (status == 'pending') {
                            var confirm_btn =
                                `<a class="btn cus_btnsucc" data-toggle="modal" data-target="#` +
                                'confirm_order_detail_' + item.id + `">Confirm</a>`;
                            var reject_btn =
                                `<a class="btn cus_btndang" data-toggle="modal" data-target="#` +
                                'cancel_order_detail_' + item.id + `">Cancel</a>`;
                            status = confirm_btn + reject_btn;
                        } else {
                            var status = capitalize_first_letter(item.status);
                        }

                        var divers_transport_select = get_divers_transport_select(item);
                        details_list += `<tr>
                                        <td>` + item.journey.name + `</td>
                                        <td>` + format_date_time_from_timestamp(item.pick_up_date_time)['date_time'] + `</td>
                                        <td>` + item.customer_collection_price + `</td>
                                        <td>` + item.final_price + `</td>
                                        <td>` + item.transport_type.name + `</td>
                                        <td>` + item.adult_passengers + `</td>
                                        <td>` + item.infant_passengers + `</td>
                                        <td>` + item.total_passengers + `</td>
                                        <td>` + ticket_img  + `</td>
                                        <td>` + divers_transport_select.drivers + `</td>
                                        <td>` + divers_transport_select.transport + `</td>
                                        <td>` + divers_transport_select.update_btn + `</td>
                                        <td id="order_detail_` + item.id + `"><div class="btun">` + status + `</div></td>
                                        <td>` + divers_transport_select.voucher_btn + `</td>
                                        <td>` + time_btn + `</td>
                                        </tr>`;
                        })
                        $('.orderdetails_list').html(details_list);
                    } else {
                        alert('Someting went wrong');
                    }
                },
                error: function(err) {
                    console.log('ajax error');
                }
            });

        }

        function saveTime(order_detail_id) {
            // Retrieve the value of the input field for the corresponding modal
            var selectedTime = $('#manual_time_' + order_detail_id).val();
            console.log('Selected time:', selectedTime); // Check the value in the console
            $.ajax({
                url: "{!! asset('reports/order/set_manual_time/') !!}/" + order_detail_id,
                type: 'POST',
                dataType: 'json',
                data: {
                    time: selectedTime // Include the selectedTime in the data payload
                },
                success: function(response) {
                    console.log('Time saved successfully:', response);
                    sendMessage(order_detail_id);
                    $('#driver_info_time_' + order_detail_id).modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error('Error saving time:', error);
                }
            });
        }


        function sendMessage(order_detail_id) {
            $.ajax({
                url: "{!! asset('reports/order/send_message') !!}/" + order_detail_id,
                type: 'GET', // or POST depending on your route definition
                dataType: 'json',
                success: function(response) {
                    console.log('Message sent successfully:', response);
                    $('#success_mdl2').modal('show');

                },
                error: function(xhr, status, error) {
                    console.error('Error sending message:', error);
                    // Handle error response as needed
                }
            });
        }

        function sendVoucher(order_detail_id) {
            $.ajax({
                url: "{!! asset('reports/order/send_voucher') !!}/" + order_detail_id,
                type: 'GET', // or POST depending on your route definition
                dataType: 'json',
                success: function(response) {
                    console.log('Voucher sent successfully:', response);
                    // $('#success_mdl').modal('show');

                },
                error: function(xhr, status, error) {
                    console.error('Error sending message:', error);
                }
            });
        }

        function sendInvoice(id) {
            $.ajax({
                url: "{!! asset('reports/order/send_invoice') !!}/" + id,
                type: 'GET', // or 'POST' depending on your route
                dataType: 'json',
                success: function(response) {
                    console.log('Invoice sent successfully:', response);
                    $('#success_mdl').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Error sending invoice:', error);
                }
            });
        }


        function update_order_transport_driver(order_detail_id, driver_select, transport_select) {
            console.log('get_details order_detail_id', order_detail_id);
            var driver_user_id = $(driver_select).find(':selected').val();
            var transport_id = $(transport_select).find(':selected').val();
            $.ajax({
                url: "{!! asset('admin/order/update_order_detail_driver') !!}/" + order_detail_id,
                type: 'post',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}',
                    driver_user_id: driver_user_id,
                    transport_id: transport_id,
                },
                success: function(response) {
                    console.log(response.status);
                    console.log('response', response.status);
                    if (response.status) {
                        console.log('updated row ', order_detail_id);
                    $('#success_mdl2').modal('show');

                        

                    } else {
                        alert('Someting went wrong');
                    }
                },
                error: function(err) {
                    console.log('ajax error');
                }
            });

        }

        function change_order_detail_status(order_detail_id, status) {
            console.log('get_details order_detail_id', order_detail_id);
            var reason = $("#reason_" + order_detail_id).val();
            $.ajax({
                url: "{!! asset('admin/order/update_order_detail_status') !!}/" + order_detail_id,
                type: 'post',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}',
                    status: status,
                    reason: reason
                },
                success: function(response) {
                    console.log(response.status);
                    console.log('response', response);
                    if (response.status) {
                        $('#order_detail_' + order_detail_id).html(capitalize_first_letter(status));
                        // var myTable = $('#orderTableAppend').DataTable();
                        // let rowIndex = myTable.row('#row_' + order_detail_id).index();
                        // myTable.cell(rowIndex, 7).data(capitalize_first_letter(status));
                        // myTable.draw();
                    } else {
                        alert('Someting went wrong');
                    }
                },
                error: function(err) {
                    console.log('ajax error');
                }
            });

        }

        function change_status(order_id, status) {
            console.log('get_details order_id', order_id);
            $.ajax({
                url: "{!! asset('admin/order/update_order_status') !!}/" + order_id,
                type: 'post',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}',
                    status: status
                },
                success: function(response) {
                    console.log(response.status);
                    console.log('response', response.status);
                    $('.orderdetails_list').html('');
                    if (response.status) {
                        var myTable = $('#orderTableAppend').DataTable();
                        let rowIndex = myTable.row('#row_' + order_id).index();
                        myTable.cell(rowIndex, 7).data(capitalize_first_letter(status));
                        myTable.draw();
                    } else {
                        alert('Someting went wrong');
                    }
                },
                error: function(err) {
                    console.log('ajax error');
                }
            });

        }

        function set_msg_modal(msg) {
            $('.set_msg_modal').html(msg);
        }

        function delete_request(id) {
            $.ajax({

                url: "{!! asset('admin/order/delete') !!}/" + id,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}'
                },
                success: function(response) {
                    console.log(response.status);
                    if (response) {
                        var myTable = $('#orderTableAppend').DataTable();
                        console.log('removeasdasdasd');
                        myTable.row('#row_' + id).remove().draw();
                    }
                }
            });
        }
    </script>
@endsection
