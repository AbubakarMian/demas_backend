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

    /* .modal-dialog {
        width: auto !important;
        padding: 10px !important;
        margin: 30px auto !important;
        display: flex;
        justify-content: center;
    }
    .modal-content.custom_mdl_class {
    min-width: 40%;
} */
</style>
@section('table')
    <table class="fhgyt" id="orderTableAppend" style="opacity: 0">
        <thead>
            @if ($user->role_id == 3)
                <tr>
                    <th>OrderId</th>
                    <th>Customer Name</th>
                    <th>Created by </th>
                    <th>Travel Agent Commission</th>
                    <th>Sale Agent Commission</th>
                    <th>Travel Agent Payment Status</th>
                    <th>Sale Agent Payment Status</th>
                    <th>Admin Payment Status</th>
                    <th>User Payment Status</th>
                    <th>Order Status</th>
                    <th>Reason</th>
                </tr>
            @elseif($user->role_id == 4)
                <tr>
                    <th>OrderId</th>
                    <th>Customer Name</th>
                    <th>Created by </th>
                    <th>Travel Agent Commission</th>
                    <th>Travel Agent Payment Status</th>
                    <th>Sale Agent Payment Status</th>
                    <th>Admin Payment Status</th>
                    <th>User Payment Status</th>
                    <th>Order Status</th>
                    <th>Reason</th>
                </tr>
            @else
                <tr>
                    <th>OrderId</th>
                    <th>Customer Name</th>
                    <th>Created by </th>
                    <th>Driver Commmission </th>
                    <th>Travel Agent Payment Status</th>
                    <th>Sale Agent Payment Status</th>
                    <th>Admin Payment Status</th>
                    <th>User Payment Status</th>
                    <th>Order Status</th>
                    <th>Reason</th>
                </tr>
            @endif
        </thead>
        <tbody>
        </tbody>
    </table>

@stop
@section('app_jquery')

    <script>
        $(document).ready(function() {
            if ("{!! $user['role_id'] !!}" == 3) {
                fetchRecords();
            } else {
                fetchRecords();
            }
        });

        function fetchDriverRecords() {
            // alert('fe');
            $.ajax({
                url: '{!! asset('admin/sub_admin/order/get_driver_order') !!}',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response', response);
                    $("#orderTableAppend").css("opacity", 1);
                    var len = response['data'].length;
                    for (var i = 0; i < len; i++) {
                        var row_data = response['data'][i];
                        var tr_str = "<tr id='row_" + response['data'][i].id + "'>" +
                            "<td>" + row_data.order.order_id + "</td>" +
                            "<td>" + row_data.order.user_obj.name + "</td>" +
                            "<td>" + row_data.journey.name + "</td>" +
                            "<td>" + row_data.driver.user_obj.name + "</td>" +
                            "<td>" + format_date_time_from_timestamp(row_data.pick_up_date_time)['date_time'] +
                            "</td>" +
                            "<td>" + row_data.driver_commission + "</td>" +
                            "<td>" + capitalize_first_letter(row_data.status) + "</td>" +
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

        function sale_agent_data(data) {
            var reason =  data.status == 'cancelled'? `<a class="btn btn-warning" data-toggle="modal" data-target="#` +
                                'reason_' + data.order_id + `">Reason</a>`:'';

            createModal({
                id: 'reason_' + data.order_id,
                header: '<h4>Cancelation Reason</h4>',
                body: `
            <p>
                ` + data.reason + `
               
            </p>`,
                footer: `
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        `,
            });({
                id: 'reason_' + data.order_id,
                header: '<h4>Cancelation Reason</h4>',
                body: `
            <p>
                ` + data.reason + `
               
            </p>`,
                footer: `
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        `,
            });


            var tr_str = "<tr id='row_" + data.order_id + "'>" +
                "<td>" + data.order_id + "</td>" +
                "<td>" + data.order.customer_name + "</td>" +
                "<td>" + data.order.user_obj.name + "</td>" +
                "<td>" + data.travel_agent_commission + "</td>" +
                "<td>" + data.sale_agent_commission + "</td>" +
                "<td>" + data.travel_agent_payment_status + "</td>" +
                "<td>" + data.sale_agent_payment_status + "</td>" +
                "<td>" + data.admin_payment_status + "</td>" +
                "<td>" + data.user_payment_status + "</td>" +
                "<td>" + data.status + "</td>" +
                "<td>" + reason + "</td>" +
                "</tr>";
            return tr_str;
        }

        function travel_agent_data(data) {
            var reason =  data.status == 'cancelled'? `<a class="btn btn-warning" data-toggle="modal" data-target="#` +
                                'reason_' + data.order_id + `">Reason</a>`:'';

            createModal({
                id: 'reason_' + data.order_id,
                header: '<h4>Cancelation Reason</h4>',
                body: `
            <p>
                ` + data.reason + `
               
            </p>`,
                footer: `
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        `,
            });({
                id: 'reason_' + data.order_id,
                header: '<h4>Cancelation Reason</h4>',
                body: `
            <p>
                ` + data.reason + `
               
            </p>`,
                footer: `
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        `,
            });
            var tr_str = "<tr id='row_" + data.order_id + "'>" +
                "<td>" + data.order_id + "</td>" +
                "<td>" + data.order.customer_name + "</td>" +
                "<td>" + data.order.user_obj.name + "</td>" +
                "<td>" + data.travel_agent_commission + "</td>" +
                "<td>" + data.travel_agent_payment_status + "</td>" +
                "<td>" + data.sale_agent_payment_status + "</td>" +
                "<td>" + data.admin_payment_status + "</td>" +
                "<td>" + data.user_payment_status + "</td>" +
                "<td>" + data.status + "</td>" +
                "<td>" + reason + "</td>" +
                "</tr>";
            return tr_str;
        }

        function driver_data(data) {
            var reason =  data.status == 'cancelled'? `<a class="btn btn-warning" data-toggle="modal" data-target="#` +
                                'reason_' + data.order_id + `">Reason</a>`:'';

            createModal({
                id: 'reason_' + data.order_id,
                header: '<h4>Cancelation Reason</h4>',
                body: `
            <p>
                ` + data.reason + `
               
            </p>`,
                footer: `
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        `,
            });({
                id: 'reason_' + data.order_id,
                header: '<h4>Cancelation Reason</h4>',
                body: `
            <p>
                ` + data.reason + `
               
            </p>`,
                footer: `
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        `,
            });
            var tr_str = "<tr id='row_" + data.order_id + "'>" +
                "<td>" + data.order_id + "</td>" +
                "<td>" + data.order.customer_name + "</td>" +
                "<td>" + data.order.user_obj.name + "</td>" +
                "<td>" + data.driver_commission + "</td>" +
                "<td>" + data.travel_agent_payment_status + "</td>" +
                "<td>" + data.sale_agent_payment_status + "</td>" +
                "<td>" + data.admin_payment_status + "</td>" +
                "<td>" + data.user_payment_status + "</td>" +
                "<td>" + data.status + "</td>" +
                "<td>" + reason + "</td>" +
                "</tr>";
            return tr_str;
        }


        function fetchRecords() {

            $.ajax({
                url: '{!! asset('admin/sub_admin/order/get_order') !!}',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('responsesadss', response);
                    $("#orderTableAppend").css("opacity", 1);
                    var len = response['data'].length;
                    for (var i = 0; i < len; i++) {
                        var id = response['data'][i].id;
                        var customer_name = response['data'][i].order.customer_name;
                        var created_by_user_name = response['data'][i].order.order_created_by_role_id;
                        var travel_agent_commission = response['data'][i].travel_agent_commission;
                        var sale_agent_commission = response['data'][i].sale_agent_commission;
                        var travel_agent_payment_status = response['data'][i].travel_agent_payment_status;
                        var sale_agent_payment_status = response['data'][i].sale_agent_payment_status;
                        var admin_payment_status = response['data'][i].admin_payment_status;
                        var user_payment_status = response['data'][i].user_payment_status;
                        var order_status = response['data'][i].status;
                        // var reason = response['data'][i].order.order_created_by_role_id;
                        // var created_by_user_name = response['data'][i].order.order_created_by_role_id;
                        // var created_by_user_name = response['data'][i].order.order_created_by_role_id;
                        // var user_sale_agent_name = response['data'][i].sale_agent?.user_obj?.name ??
                        //     '';
                        // var user_travel_agent_name = response['data'][i].travel_agent?.user_obj?.name ??
                        //     '';
                        // var cash_collected_by = response['data'][i].cash_collected_by;
                        // var cash_collected_by_user_id = response['data'][i].cash_collected_by_user_id;
                        // var price = response['data'][i].price;
                        // var type = response['data'][i].type;
                        // var trip_type = response['data'][i].trip_type;
                        // var total_price = response['data'][i].total_price;
                        // var ispaid = response['data'][i].is_paid ?
                        //     '<i class="fa fa-check" style="color:#38da38;" aria-hidden="true"></i>' :
                        //     '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
                        // var status = response['data'][i].status;
                        // var payment_collected_type = response['data'][i].payment_collected_type;
                        // var payment_collected_user_id = response['data'][i].payment_collected_user_id;
                        // var payment_collected_price = response['data'][i].payment_collected_price;
                        // var order_type = response['data'][i].order_type;
                        // var payment_type = response['data'][i].payment_type;
                        var reason =
                            `<a class="btn btn-info" data-toggle="modal" data-target="#reason_"
                                onclick="get_details(` + id + `)">View</a>`;
                        // 'orderdetail_' + response['data'][i].id + `">View</a>`;
                        createModal({
                            id: 'reason_' + response['data'][i].id,
                            // id: 'reason',
                            header: '<h4>Cancelation Reason</h4>',
                            body: `
                                <table class="modal_table fhgyt";>
                                   <p>
                                    ` + response['data'][i].reason + `
                                   </p>
                                    <tbody class="orderdetails_list">
                                    </tbody>
                                </table>`,
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

                        if (response['role_id'] == 3) { // sale agent
                            var status = capitalize_first_letter(response['data'][i].status);
                            var commission = response['data'][i].sale_agent_commission_total;
                        } else { //travel_agent
                            var commission = response['data'][i].travel_agent_commission_total;
                        }

                        if (response['role_id'] == 3) {
                            var tr_str = sale_agent_data(response['data'][i]);
                        }
                        if (response['role_id'] == 4) {
                            var tr_str = travel_agent_data(response['data'][i]);
                        }
                        if (response['role_id'] == 5) {
                            var tr_str = driver_data(response['data'][i]);
                        }

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
            // console.log('get_details order_id', order_id);
            $.ajax({
                url: "{!! asset('admin/sub_admin/order/details_list') !!}/" + order_id,
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}'
                },
                success: function(response) {
                    $('.orderdetails_list').html('');
                    if (response.status) {
                        var details_list = '';
                        $.each(response.response['order_details'], function(index, item) {
                            console.log('response item', item);
                            console.log('response index', index);
                            if (response['role_id'] == 3) { // sale agent
                                var status = capitalize_first_letter(item.status);
                                var commission = item.sale_agent_commission;
                            } else { //travel_agent
                                var commission = item.travel_agent_commission;
                            }
                            details_list += `<tr>
                                <td>` + item.journey.name + `</td>
                                <td>` + format_date_time_from_timestamp(item.pick_up_date_time)['date_time'] +
                                `</td>
                                <td>` + commission + `</td>
                                <td>` + item.driver.user_obj.name + `</td>
                                
                                </tr>`;
                        })
                        // <td>` + item.driver.user_obj.name + `</td>
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


        function change_status(order_id, status) {
            // console.log('get_details order_id', order_id);
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
                        console.log('removeasdasdasd row ', '#row_' + order_id);
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
