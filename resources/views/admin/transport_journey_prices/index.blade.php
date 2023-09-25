@extends('layouts.default_module')
@section('module_name')
    Transport Journey Prices
@stop

@section('add_btn')
    {!! Form::open(['method' => 'get', 'url' => ['admin/transport_journey_prices/create'], 'files' => true]) !!}
    <span>{!! Form::submit('Add Journey Prices', ['class' => 'btn btn-success pull-right']) !!}</span>
    {!! Form::close() !!}
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

    input.inp_td {
        width: 100%;
        height: 100%;
        border: solid 1px #efdcdc;
        background: #e5e5e5;
    }
</style>
@section('table')

    <table class="fhgyt" id="transport_journey_pricesTableAppend" style="opacity: 0">
        <thead>
            <tr>
                <th>Journey Slot</th>
                <th> Trip Price</th>
                <th> Sale Agent</th>
                <th> S/A Commisssion</th>
                <th> Travel Agent</th>
                <th> T/A Commisssion</th>
                <th> Driver Commisssion</th>
                {{-- <th>Edit </th> --}}
                {{-- <th>Delete </th> --}}
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
                    url: '{!! asset('admin/transport_journey_prices/get_transport_journey_prices') !!}',
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        console.log('response');
                        $("#transport_journey_pricesTableAppend").css("opacity", 1);
                        response = response.response;
                        var len = response.length;
                        console.log('response2',response);

                        for (var i = 0; i < len; i++) {
                            var id = response[i].id;
                            //   var journey_slot =  response[i].journeyslot.slot.name;
                            var journey_slot = response[i].journeyslot.slot.name;
                            var journey_slot_id = response[i].journey_slot_id;
                            var trip_price = response[i].trip_price;
                            var sale_agent = response[i].sale_agent.user_obj.name;
                            var sale_agent_com = response[i].sale_agent_commision;
                            var travel_agent = response[i].travel_agent.user_obj.name;
                            var tavel_agent_com = response[i].travel_agent_commision;
                            var driver_com = response[i].driver_user_commision;

                            console.log('aaa', response[i]);
                            // console.log('ccaaa',response[i].transport_type);



                            // var edit =
                            // `<a class="btn btn-info" href="{!! asset('admin/transport_journey_prices/edit/` + id + `') !!}">Edit</a>`;
                            createModal({
                                id: 'transport_journey_prices_' + response[i].id,
                                header: '<h4>Delete</h4>',
                                body: 'Do you want to continue ?',
                                footer: `
                                <button class="btn btn-danger" onclick="delete_request(` + response[i].id + `)"
                                data-dismiss="modal">
                                    Delete
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                `,
                            });
                            // var delete_btn =
                            //     `<a class="btn btn-info" data-toggle="modal" data-target="#` +
                            //     'transport_journey_prices_' + response[i].id + `">Delete</a>`;
                            var trip_price_values = '';
                            if (trip_price) {
                                trip_price_values = trip_price;
                            } else(
                                trip_price_values = 0

                            )
                            var sale_agent_values = '';
                            if (sale_agent) {
                                sale_agent_values = sale_agent;
                            } else(
                                sale_agent_values = 0

                            )
                            var sale_agent_com_values = '';
                            if (sale_agent_com) {
                                sale_agent_com_values = sale_agent_com;
                            } else(
                                sale_agent_com_values = 0

                            )
                            var travel_agent_values = '';
                            if (travel_agent) {
                                travel_agent_values = travel_agent;
                            } else(
                                travel_agent_values = 0

                            )
                            var tavel_agent_com_values = '';
                            if (tavel_agent_com) {
                                tavel_agent_com_values = tavel_agent_com;
                            } else(
                                tavel_agent_com_values = 0

                            )
                            var driver_com_values = '';
                            if (driver_com) {
                                driver_com_values = driver_com;
                            } else(
                                driver_com_values = 0

                            )
                            var tr_str = "<tr id='row_" + response[i].id + "'>" +
                                "<td>" + journey_slot + "</td>" +
                                // "<td>" +trip_price+ "</td>" +
                                "<td><input onchange=update_trip_price(" + journey_slot_id +
                                ",this) class='inp_td' type='text' name='trip_price' value='" +
                                trip_price_values + "'></td>" +
                                "<td>" +sale_agent+ "</td>" +
                                // "<td><input onchange=update_sale_agent(" + journey_slot_id +
                                // ",this) class='inp_td'  type='text' value='" + sale_agent_values +
                                // "'></td>" +

                                // "<td>" +sale_agent_com+ "</td>" +
                                "<td><input onchange=update_sale_agent_com(" + journey_slot_id +
                                ",this) class='inp_td'  type='text' value='" + sale_agent_com_values +
                                "'></td>" +
                                "<td>" +travel_agent+ "</td>" +
                                // "<td><input onchange=update_travel_agent(" + journey_slot_id +
                                // ",this) class='inp_td'  type='text' value='" + travel_agent_values +
                                // "'></td>" +
                                // "<td>" +tavel_agent_com+ "</td>" +
                                "<td><input onchange=update_tavel_agent_com(" + journey_slot_id +
                                ",this) class='inp_td'  type='text' value='" + tavel_agent_com_values +
                                "'></td>" +
                                // "<td>" +driver_com+ "</td>" +
                                "<td><input onchange=update_driver_com(" + journey_slot_id +
                                ",this) class='inp_td'  type='text' value='" + driver_com_values +
                                "'></td>" +
                                // "<td>" + edit + "</td>" +
                                // "<td>" + delete_btn + "</td>" +


                                "</tr>";

                            $("#transport_journey_pricesTableAppend tbody").append(tr_str);
                        }
                        $(document).ready(function() {
                            console.log('sadasdasdad');
                            $('#transport_journey_pricesTableAppend').DataTable({
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

                url: "{!! asset('admin/transport_journey_prices/delete') !!}/" + id,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}'
                },
                success: function(response) {
                    console.log(response.status);
                    if (response) {
                        var myTable = $('#transport_journey_pricesTableAppend').DataTable();
                        console.log('removeasdasdasd');
                        myTable.row('#row_' + id).remove().draw();
                    }
                }
            });
        }

        function update_trip_price(journey_slot_id, e) {

            var trip_price_values = $(e).val();
            $.ajax({
                url: '{!! asset('admin/transport_journey_prices/update_price') !!}/' + journey_slot_id + '?trip_price=' + trip_price_values,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                }

            });
        }

        function update_sale_agent(journey_slot_id, e) {
            var sale_agent_values = $(e).val();
            $.ajax({
                url: '{!! asset('admin/transport_journey_prices/update_sale_agent') !!}/' + journey_slot_id + '?sale_agent_user_id=' + sale_agent_values,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                }

            });
        }

        function update_sale_agent_com(journey_slot_id, e) {
            var sale_agent_com_values = $(e).val();
            $.ajax({
                url: '{!! asset('admin/transport_journey_prices/update_sale_agent_com') !!}/' + journey_slot_id + '?sale_agent_commision=' +
                    sale_agent_com_values,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                }

            });
        }

        function update_travel_agent(journey_slot_id, e) {
            var update_travel_agent_values = $(e).val();
            $.ajax({
                url: '{!! asset('admin/transport_journey_prices/update_travel_agent') !!}/' + journey_slot_id + '?travel_agent_user_id=' +
                    update_travel_agent_values,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                }

            });
        }

        function update_tavel_agent_com(journey_slot_id, e) {
            var update_tavel_agent_com_values = $(e).val();
            $.ajax({
                url: '{!! asset('admin/transport_journey_prices/update_tavel_agent_com') !!}/' + journey_slot_id + '?travel_agent_commision=' +
                    update_tavel_agent_com_values,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                }

            });
        }

        function update_driver_com(journey_slot_id, e) {
            var update_driver_com_values = $(e).val();
            $.ajax({
                url: '{!! asset('admin/transport_journey_prices/update_driver_com') !!}/' + journey_slot_id + '?driver_user_commision=' +
                    update_driver_com_values,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                }

            });
        }
    </script>
@endsection
