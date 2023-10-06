@extends('layouts.default_module')
@section('module_name')
    Orders
@stop

{{-- @section('add_btn')
{!! Form::open(['method' => 'get', 'url' => ['admin/order/create'], 'files' => true]) !!}
<span>{!! Form::submit('Add Transport', ['class' => 'btn btn-success pull-right']) !!}</span>
{!! Form::close() !!}
@stop --}}
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

    .modal-dialog {
        width: auto !important;
        padding: 10px !important;
        margin: 30px auto !important;
        display: flex;
        justify-content: center;
    }
</style>
@section('table')

    <table class="fhgyt" id="orderTableAppend" style="opacity: 0">
        <thead>
            <tr>
                <th>User</th>
                <th>Sale Agent</th>
                <th>Travel Agent</th>
                <th>Price</th>
                <th>Trip Type</th>
                <th>Paid</th>
                <th>Order Details</th>
                <th>Status </th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@stop
@section('app_jquery')

    <script>
        $(document).ready(function() {
            fetchRecords();
        });

        function fetchRecords() {

            $.ajax({
                url: '{!! asset('admin/order/get_order') !!}',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                    $("#orderTableAppend").css("opacity", 1);
                    var len = response['data'].length;
                    console.log('response2');


                    for (var i = 0; i < len; i++) {
                        var id = response['data'][i].id;
                        var name = response['data'][i].user_obj.name;
                        // var payment_id = response['data'][i].payment_id;
                        var user_sale_agent_name = response['data'][i].sale_agent.user_obj.name;
                        var user_travel_agent_name = response['data'][i].travel_agent.user_obj.name;
                        var user_driver_id = response['data'][i].driver.user_obj.name;
                        var cash_collected_by = response['data'][i].cash_collected_by;
                        var cash_collected_by_user_id = response['data'][i].cash_collected_by_user_id;
                        var price = response['data'][i].price;
                        var type = response['data'][i].type;
                        var trip_type = response['data'][i].trip_type;
                        var total_price = response['data'][i].total_price;
                        var ispaid = response['data'][i].is_paid ? 'True' : 'False';
                        var status = response['data'][i].status;
                        var payment_collected_type = response['data'][i].payment_collected_type;
                        var payment_collected_user_id = response['data'][i].payment_collected_user_id;
                        var payment_collected_price = response['data'][i].payment_collected_price;
                        var order_type = response['data'][i].order_type;
                        var payment_type = response['data'][i].payment_type;

                        console.log('aaa', response['data'][i]);

                        var order_detail =
                            `<a class="btn btn-info" data-toggle="modal" data-target="#orderdetails"
                                onclick="get_details(` + id + `)">View</a>`;
                        // 'orderdetail_' + response['data'][i].id + `">View</a>`;
                        createModal({
                            // id: 'orderdetail_' + response['data'][i].id,
                            id: 'orderdetails',
                            header: '<h4>Order details</h4>',
                            body: `
                                <table class="modal_table fhgyt";>
                                    <thead>
                                        <tr>
                                            <th>Pickup Location</th>
                                            <th>DropOff Location</th>
                                            <th>PickUp Date/Time</th>
                                            <th>Price</th>
                                            <th>Driver</th>
                                            <th>Journey</th>
                                            <th>Journey Slot</th>
                                        </tr>
                                    </thead>
                                    <tbody class="orderdetails_list">
                                        <tr>
                                            <td>Makkah Hotel</td>
                                            <td>Jaddah Airport</td>
                                            <td>05:00PM 06-jul-2023</td>
                                            <td>125 SAR</td>
                                            <td>Billal</td>
                                            <td>makkah hotel to Jaddah Airport</td>
                                            <td>06-jul-2023 - 07-jul-2023</td>
                                        </tr>
                                    </tbody>
                                </table>`,
                            footer: `
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                `,
                        });
                        createModal({
                            id: 'order_' + response['data'][i].id,
                            header: '<h4>Delete</h4>',
                            body: 'Do you want to continue ?',
                            footer: `
                                <button class="btn btn-danger" onclick="delete_request(` + response['data'][i].id + `)"
                                data-dismiss="modal">
                                    Delete
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                `,
                        });
                        var status = response['data'][i].status;
                        if(status == 'pending'){
                        var confirm_btn =
                            `<a class="btn btn-info" data-toggle="modal" data-target="#` +
                            'order_' + response['data'][i].id + `">Confirm</a>`;
                        var reject_btn =
                            `<a class="btn btn-danger" data-toggle="modal" data-target="#` +
                            'order_' + response['data'][i].id + `">Reject</a>`;
                            status = confirm_btn + reject_btn;
                        }
                        else{
                            var status = capitalize_first_letter(response['data'][i].status);
                        }
                        var tr_str = "<tr id='row_" + response['data'][i].id + "'>" +
                            "<td>" + name + "</td>" +
                            "<td>" + user_sale_agent_name + "</td>" +
                            "<td>" + user_travel_agent_name + "</td>" +
                            "<td>" + total_price + "</td>" +
                            "<td>" + trip_type + "</td>" +
                            "<td>" + ispaid + "</td>" +
                            "<td>" + order_detail + "</td>" +
                            `<td id='td_status_` + response['data'][i].id + `'>` +
                                status + `</td>` +
                            "</tr>";
                        $("#orderTableAppend tbody").append(tr_str);
                    }
                    $(document).ready(function() {
                        console.log('sadasdasdad');
                        $('#orderTableAppend').DataTable({
                            dom: '<"top_datatable"B>lftipr',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ],
                        });
                    });
                }
            });
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
                    console.log(response.status);
                    console.log('response details_list',response.status);
                    $('.orderdetails_list').html('');
                    if (response.status) {
                        var details_list = '';
                        $.each(response.response,function(item,index){
                            details_list += `<tr>`+
                                `<td>`+item.pickup_location+`</td`
                                `<td>`+item.dropoff_location+`</td`
                                `<td>`+item.pick_up_date_time+`</td`
                                `<td>`+item.price+`</td`
                                `<td>`+item.driver.user_obj.name+`</td`
                                `<td>`+item.journey.name+`</td`
                                `<td>`+item.journey_slot.slot.name+`</td`
                                `</tr>`;
                        })
                        $('.orderdetails_list').html(details_list);
                    }
                    else{
                        alert('Someting went wrong');
                    }
                },
                error:function(err){
                    console.log('ajax error');
                }
            });

        }
        
        function change_status(order_id,status) {
            console.log('get_details order_id', order_id);
            $.ajax({
                url: "{!! asset('admin/order/update_order_status') !!}/" + order_id,
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}',
                    status:status
                },
                success: function(response) {
                    console.log(response.status);
                    console.log('response',response.status);
                    $('.orderdetails_list').html('');
                    if (response.status) {
                        var myTable = $('#orderTableAppend').DataTable();
                        console.log('removeasdasdasd');
                        let row = myTable.row('#row_' + id);
                        row.data()[7] = capitalize_first_letter(status);
                    }
                    else{
                        alert('Someting went wrong');
                    }
                },
                error:function(err){
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
