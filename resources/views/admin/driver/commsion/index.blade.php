@extends('layouts.default_module')
@section('module_name')
    Driver Commissions
@stop

@section('add_btn')
<div class="row search-form">
    <div class="col-md-3">
        {!! Form::select('journey_id', $journey_list, null, [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Select Journey',
        ]) !!}
    </div>
    <div class="col-md-3">
        {!! Form::select('slot_id', $slot_list, null, [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Select Slot',
        ]) !!}
    </div>
    <div class="col-md-3">
        {!! Form::select('transport_type_id', $transport_type_list, null, [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Select Transport Type',
        ]) !!}
    </div>
    <div class="col-md-3">
        {!! Form::select('user_driver_id', $driver_list, null, [
            'class' => 'form-control',
            'data-parsley-required' => 'true',
            'data-parsley-trigger' => 'change',
            'placeholder' => 'Select Driver',
        ]) !!}
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
    .search {
        margin: 10px;
    }
</style>
@section('table')
<div class="toggle-edit-datatable">
    <input id="open-edit" checked value="Edit" type="radio" name="toggle-edit-export" onchange="fetchRecords();">
    <label for="open-edit">Edit</label>
    <input id="open-search" value="Export" type="radio" name="toggle-edit-export" onchange="fetchRecords();">
    <label for="open-search">Export</label>
</div>
    <table class="fhgyt" id="carTableAppend" style="opacity: 0">
        <thead>
            <tr>
                <th> Journey</th>
                <th> Slots</th>
                <th> Driver</th>
                <th> Transport Type</th>
                <th> Price</th>
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
            $("#carTableAppend tbody").html('');
            $('#carTableAppend').DataTable().destroy();
            var open_edit = $("#open-edit").is(":checked");
            var search_param = '';
            var search_concat = '?';
            $('.search-form select').each(function(item, index) {
                console.log('name', $(this).attr('name'));
                console.log('value', $(this).val());
                if ($(this).val() != '') {
                    search_param += search_concat + $(this).attr('name') + "=" + $(this).val();
                    search_concat = '&';
                }
            });
            search_url = '{!! asset('admin/driver_commission/get_driver_commission') !!}' + search_param;
            console.log('search_url', search_url);
            $.ajax({
                url: search_url,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                    $("#carTableAppend").css("opacity", 1);
                    var len = response['data'].length;
                    console.log('response2');
                    var tr_str = '';
                    for (var i = 0; i < len; i++) {
                        var id = response['data'][i].id;
                        var transport_type_name = response['data'][i].transport_type.name;

                        console.log('aaa', response['data'][i]);
                        var journey = response['data'][i].journey.name;
                        var slot = response['data'][i].slot.name;
                        var agent = response['data'][i].user_obj.name;
                        var commission = response['data'][i].commission;
                        var transport_prices_id = response['data'][i].id;
                        var price_td = commission;
                        if (open_edit) {
                            price_td = `<input onchange=update_user_price(` + transport_prices_id +
                                `,this) type='text' value='` + commission + `'>
                            `;
                        }

                         tr_str += "<tr id='row_" + response['data'][i].id + "'>" +
                            "<td>" + journey + "</td>" +
                            "<td>" + slot + "</td>" +
                            "<td>" + agent + "</td>" +
                            "<td>" + transport_type_name + "</td>" +
                            "<td>" + price_td + "</td>" +
                            "</tr>";
                    }
                    $("#carTableAppend tbody").html(tr_str);
                    if (open_edit) {
                        var dt = {
                        dom: '<"top_datatable">lftipr',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
                    };
                    }
                    else{
                        var dt = {
                        dom: '<"top_datatable"B>lftipr',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
                    };
                    }

                    // $(document).ready(function() {
                    $('#carTableAppend').DataTable(dt);
                    // });
                }
            });
        }

        function update_delay_user_price(transport_prices_id, e) {

            setTimeout(() => {
                update_user_price(transport_prices_id, e);
            }, 500);
        }

        function update_user_price(price_id, e) {

            var commission = $(e).val();
            $.ajax({
                url: '{!! asset('admin/driver_commission/update_price') !!}/' + price_id + '?commission=' + commission,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                }

            });
        }

        function set_msg_modal(msg) {
            $('.set_msg_modal').html(msg);
        }

        function delete_request(id) {
            $.ajax({

                url: "{!! asset('admin/car/delete') !!}/" + id,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}'
                },
                success: function(response) {
                    console.log(response.status);
                    if (response) {
                        var myTable = $('#carTableAppend').DataTable();
                        console.log('removeasdasdasd');
                        myTable.row('#row_' + id).remove().draw();
                    }
                }
            });
        }
    </script>
@endsection
