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
                <th>User Name</th>
                {{-- <th>payment_id</th> --}}
                <th>Sale Agent</th>
                <th>Travel Agent</th>
                <th>Driver</th>
                <th>Cash Collected by</th>
                <th>Cash Collected by Name </th>
                <th>Price</th>
                <th>Type</th>
                <th>Trip Type</th>
                <th>Total Price</th>
                <th>Status </th>
                <th>Payment Collected Type</th>
                <th>Payment Collected Name</th>
                <th>Amount Collected</th>
                <th>Order Type</th>
                <th>Payment Type</th>
                <th>Order Details</th>
                <th>Delete </th>
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
                            var user_sale_agent_id = response['data'][i].sale_agent.user_obj.name;
                            var user_travel_agent_id = response['data'][i].travel_agent.user_obj.name;
                            var user_driver_id = response['data'][i].driver.user_obj.name;
                            var cash_collected_by = response['data'][i].cash_collected_by;
                            var cash_collected_by_user_id = response['data'][i].cash_collected_by_user_id;
                            var price = response['data'][i].price;
                            var type = response['data'][i].type;
                            var trip_type = response['data'][i].trip_type;
                            var total_price = response['data'][i].total_price;
                            var status = response['data'][i].status;
                            var payment_collected_type = response['data'][i].payment_collected_type;
                            var payment_collected_user_id = response['data'][i]
                                .payment_collected_user_id;
                            var payment_collected_price = response['data'][i].payment_collected_price;
                            var order_type = response['data'][i].order_type;
                            var payment_type = response['data'][i].payment_type;

                            console.log('aaa', response['data'][i]);

                            var order_detail =
                                `<a class="btn btn-info" data-toggle="modal" data-target="#` +
                                'orderdetail_' + response['data'][i].id + `">View</a>`;
                            createModal({
                                id: 'orderdetail_' + response['data'][i].id,
                                header: '<h4>Order details</h4>',
                                body: `
                                <table class="modal_table fhgyt";>
                                    <thead>
                                        <tr>
                                            <th>order_id</th>
                                            <th>Pickup Location</th>
                                            <th>DropOff Location</th>
                                            <th>PickUp Date/Time</th>
                                            <th>Price</th>
                                            <th>Driver</th>
                                            <th>Journey</th>
                                            <th>Journey Slot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
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
                            var delete_btn =
                                `<a class="btn btn-info" data-toggle="modal" data-target="#` +
                                'order_' + response['data'][i].id + `">Delete</a>`;
                            // var img = `<img width="42" src="`+image+`">`;
                            var tr_str = "<tr id='row_" + response['data'][i].id + "'>" +
                                "<td>" + name + "</td>" +
                                // "<td>" + payment_id + "</td>" +
                                "<td>" + user_sale_agent_id + "</td>" +
                                "<td>" + user_travel_agent_id + "</td>" +
                                "<td>" + user_driver_id + "</td>" +
                                "<td>" + cash_collected_by + "</td>" +
                                "<td>" + cash_collected_by_user_id + "</td>" +
                                "<td>" + price + "</td>" +
                                "<td>" + type + "</td>" +
                                "<td>" + trip_type + "</td>" +
                                "<td>" + total_price + "</td>" +
                                "<td>" + status + "</td>" +
                                "<td>" + payment_collected_type + "</td>" +
                                "<td>" + payment_collected_user_id + "</td>" +
                                "<td>" + payment_collected_price + "</td>" +
                                "<td>" + order_type + "</td>" +
                                "<td>" + payment_type + "</td>" +
                                "<td>" + order_detail + "</td>" +
                                "<td>" + delete_btn + "</td>" +


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

        });

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
