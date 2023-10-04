@extends('layouts.default_module')
@section('module_name')
    Transport Prices
@stop

@section('add_btn')
<div class="search">
    {!! Form::select('journey_id', $journey_list, null, [
        'class' => 'form-control',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select Journey',
    ]) !!}
    {!! Form::select('slot_id', $slot_list, null, [
        'class' => 'form-control',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select Slot',
    ]) !!}
    {!! Form::select('transport_type_id', $transport_type_list, null, [
        'class' => 'form-control',
        'data-parsley-required' => 'true',
        'data-parsley-trigger' => 'change',
        'placeholder' => 'Select Transport Type',
    ]) !!}
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
</style>
@section('table')

    <table class="fhgyt" id="carTableAppend" style="opacity: 0">
        <thead>
            <tr>
                <th> Journey</th>
                <th> Slots</th>
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
            var search_param = '';
            var search_concat = '?';
            $('.search select').each(function(item, index) {
                console.log('name', $(this).attr('name'));
                console.log('value', $(this).val());
                if ($(this).val() != '') {
                    search_param += search_concat + $(this).attr('name') + "=" + $(this).val();
                    search_concat = '&';
                }
            });
            search_url = '{!! asset('admin/price/get_car_prices') !!}' + search_param;
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
                            var price = response['data'][i].price;
                            var transport_prices_id = response['data'][i].id;

                            tr_str += "<tr id='row_" + response['data'][i].id + "'>" +
                                "<td>" + journey + "</td>" +
                                "<td>" + slot + "</td>" +
                                "<td>" + transport_type_name + "</td>" +
                                "<td><input onchange=update_user_price(" + transport_prices_id +
                                ",this) type='text' value='" + price + "'></td>" +

                                "</tr>";

                            $("#carTableAppend tbody").append(tr_str);
                        }
                        $("#carTableAppend tbody").html(tr_str);

                        $(document).ready(function() {
                            $('#carTableAppend').DataTable({
                                dom: '<"top_datatable"B>lftipr',
                                buttons: [
                                    'copy', 'csv', 'excel', 'pdf', 'print'
                                ],
                            });
                        });
                    }
                });
            }

        function update_delay_user_price(transport_prices_id, e) {

            setTimeout(() => {
                update_user_price(transport_prices_id, e);
            }, 500);
        }

        function update_user_price(transport_prices_id, e) {

            var price = $(e).val();
            $.ajax({
                url: '{!! asset('admin/price/update_price') !!}/' + transport_prices_id + '?price=' + price,
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
