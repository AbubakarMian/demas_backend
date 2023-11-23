@extends('layouts.default_module')
@section('module_name')
    Agents
@stop

@section('add_btn')
    <form class="search_filter">
        <div class="row">
            <div class="row show_columns">
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label>. From:</label>
                    {!! Form::date('to_date', null, [
                        'class' => 'form-control',
                        'data-parsley-required' => 'true',
                        'data-parsley-trigger' => 'change',
                        'placeholder' => 'Select Date',
                    ]) !!}
                </div>
                <div class="col-md-2">
                    <label>To:</label>
                    {!! Form::date('from_date', null, [
                        'class' => 'form-control',
                        'data-parsley-required' => 'true',
                        'data-parsley-trigger' => 'change',
                        'placeholder' => 'Select Date',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-2">
                <label>Journey:</label>
                {!! Form::select('journey_id', $journey_list, null, [
                    'class' => 'form-control',
                    'data-parsley-required' => 'true',
                    'data-parsley-trigger' => 'change',
                    'placeholder' => 'Select Journey',
                ]) !!}

            </div>
            <div class="col-md-2">
                <label>Slot:</label>
                {!! Form::select('slot_id', $slot_list, null, [
                    'class' => 'form-control',
                    'data-parsley-required' => 'true',
                    'data-parsley-trigger' => 'change',
                    'placeholder' => 'Select Slot',
                ]) !!}

            </div>
            <div class="col-md-2">
                <label>Transport Type:</label>

                {!! Form::select('transport_type_id', $transport_type_list, null, [
                    'class' => 'form-control',
                    'data-parsley-required' => 'true',
                    'data-parsley-trigger' => 'change',
                    'placeholder' => 'Select Transport Type',
                ]) !!}

            </div>
            <div class="col-md-2">
                <label>Agent: </label>

                {!! Form::select('travel_agent_user_id', $travel_agent_list, null, [
                    'class' => 'form-control',
                    'data-parsley-required' => 'true',
                    'data-parsley-trigger' => 'change',
                    'placeholder' => 'Select Agent',
                ]) !!}

            </div>
        </div>
    </form>
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
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@stop
@section('app_jquery')

    <script>
        $(document).ready(function() {
            $('#orderTableAppend').DataTable({
                dom: '<"top_datatable"B>lftipr',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
            });
            fetchRecords();
        });

        function fetchRecords() {
            console.log('agent ftach er');
            var formdata = $('.search_filter').serialize();

            // Destroy DataTable after the AJAX request is complete
            $.ajax({
                url: "{!! asset('admin/reports/agent/get_order') !!}",
                type: 'post',
                data: formdata,
                dataType: 'json',
                success: function(response) {
                    console.log('response', response);

                    // Clear previous table data
                    $("#orderTableAppend").DataTable().clear().destroy();

                    $("#orderTableAppend").css("opacity", 1);

                    var thead = '<tr>';
                    var tbody = '';

                    var response_info = response['table_info'];
                    var response_data = response['report_data'];

                    $('.show_columns').html(show_columns_filter_html(response_info));

                    $.each(response_info, function(report_info_index, report_info_data) {
                        $.each(report_info_data['columns'], function(column_index, column) {
                            thead += '<th>' + column['heading'] + '</th>';
                        });
                    });

                    thead += '</tr>';

                    $.each(response_data, function(res_data_index, res_data) {
                        tbody += '<tr>';
                        $.each(response_info, function(report_info_index, report_info_data) {
                            $.each(report_info_data['columns'], function(column_index, column) {
                                tbody += `<td>` + res_data[column['data_column']] +
                                    `</td>`;
                            });
                        });
                        tbody += '</tr>';
                    });
                    $("#orderTableAppend thead").html(thead);
                    $("#orderTableAppend tbody").html(tbody);

                    // Reinitialize DataTable after updating the table
                    $('#orderTableAppend').DataTable({
                        dom: '<"top_datatable"B>lftipr',
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function show_columns_filter_html(column){
            var html  = '';

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
                    if (response.status) {
                        var details_list = '';
                        $.each(response.response['order_details'], function(index, item) {
                            console.log('response item get_details ', item);
                            console.log('response index', index);
                            var drivers = `<select onchange="change_driver('` + item.id + `',this)">
                                <option value="0">Select Driver</option>`;
                            $.each(response.response['drivers'], function(driver_index, driver_item) {
                                var user_driver = driver_item.user_obj;
                                var selected = user_driver.id == item.driver_user_id ?
                                    'selected' : '';
                                drivers += `<option value="` + user_driver.id + `" ` +
                                    selected + `>` +
                                    user_driver.name + `</option>`;
                            })
                            drivers += '</select>';
                            details_list += `<tr>
                                <td>` + item.journey.name + `</td>
                                <td>` + format_date_time_from_timestamp(item.pick_up_date_time)['date_time'] + `</td>
                                <td>` + item.price + `</td>
                                <td>` + drivers + `</td>
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

        function change_driver(order_detail_id, e) {
            console.log('get_details order_detail_id', order_detail_id);
            var driver_user_id = $(e).find(':selected').val();
            $.ajax({
                url: "{!! asset('admin/order/update_order_detail_driver') !!}/" + order_detail_id,
                type: 'post',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}',
                    driver_user_id: driver_user_id
                },
                success: function(response) {
                    console.log(response.status);
                    console.log('response', response.status);
                    if (response.status) {
                        console.log('updated row ', order_detail_id);
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
