@extends('layouts.default_module')
@section('module_name')
Slots
@stop

@section('add_btn')
    {!! Form::open(['method' => 'get', 'url' => ['admin/slot/create'], 'files' => true]) !!}
    <span>{!! Form::submit('Add Slot', ['class' => 'btn btn-success pull-right']) !!}</span>
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
</style>
@section('table')
    <table class="fhgyt" id="slotTableAppend" style="opacity: 0">
        <thead>
            <tr>
                <th> Name</th>
                <th> Start Date</th>
                <th> End Date</th>
                <th>Edit </th>
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

            function formatDate(timestamp) {
                var date = new Date(timestamp * 1000);
                var day = String(date.getDate()).padStart(2, '0');
                var month = String(date.getMonth() + 1).padStart(2, '0'); // Month index starts from 0
                var year = date.getFullYear();
                return day + '/' + month + '/' + year;
            }

            function fetchRecords() {
                $.ajax({
                    url: '{!! asset('admin/slot/get_slot') !!}',
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        console.log('response');
                        $("#slotTableAppend").css("opacity", 1);
                        var len = response['data'].length;
                        console.log('response2');

                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var from_timestamp = response['data'][i].start_date;
                            var to_timestamp = response['data'][i].end_date;

                            var from_date_formatted = formatDate(from_timestamp);
                            var to_date_formatted = formatDate(to_timestamp);

                           
                            var edit =
                                `<a class="btn btn-info" href="{!! asset('admin/slot/edit/`  + response["data"][i].id +`') !!}">Edit</a>`;
                            createModal({
                                id: 'slot_' + response["data"][i].id,
                                header: '<h4>Delete</h4>',
                                body: 'Do you want to continue ?',
                                footer: `
                                <button class="btn btn-danger" onclick="delete_request(` + response["data"][i].id + `)"
                                data-dismiss="modal">
                                    Delete
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                `,
                            });
                            var delete_btn =
                                `<a class="btn btn-info" data-toggle="modal" data-target="#` +
                                'slot_' + response["data"][i].id + `">Delete</a>`;

                            var tr_str = "<tr id='row_" + id + "'>" +
                                "<td>" + name + "</td>" +
                                "<td>" + from_date_formatted + "</td>" +
                                "<td>" + to_date_formatted + "</td>" +
                                "<td>" + edit + "</td>" +
                                "<td>" + delete_btn + "</td>" +
                                "</tr>";

                            $("#slotTableAppend tbody").append(tr_str);
                        }

                        $('#slotTableAppend').DataTable({
                            dom: '<"top_datatable"B>lftipr',
                            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
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
                url: "{!! asset('admin/slot/delete') !!}/" + id,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}'
                },
                success: function(response) {
                    console.log(response.status);
                    if (response) {
                        var myTable = $('#slotTableAppend').DataTable();
                        console.log('removeasdasdasd');
                        myTable.row('#row_' + id).remove().draw();
                    }
                }
            });
        }
    </script>
@endsection
