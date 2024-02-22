@extends('layouts.default_module')
@section('module_name')
    WALLET
@stop

@section('add_btn')
{!! Form::open(['method' => 'get', 'url' => ['reports/staff_payments/create'], 'files' => true]) !!}
<span>{!! Form::submit('Add Staff Payments ', ['class' => 'btn btn-success pull-right']) !!}</span>
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

    <table class="fhgyt" id="userTableAppend" style="opacity: 0">
        <thead>
            <tr>
                <th> Name</th>
                <th> Role</th>
                <th> wallet</th>

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
                    url: '{!! asset('admin/user/get_user') !!}',
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        console.log('response');
                        $("#userTableAppend").css("opacity", 1);
                        var len = response['data'].length;
                        console.log('response2');

                        console.log(response);

                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var wallet = response['data'][i].wallet;

                            // Check if the role_id is 1 (Admin)
                            if (response['data'][i].role_id != 1) {
                                // Exclude Admin users, only append rows for other roles
                                var role_name = '';
                                if (response['data'][i].role_id == 2) {
                                    role_name = 'User';
                                } 
                                else if (response['data'][i].role_id == 3) {
                                    role_name = 'Sales Agent';
                                } 
                                else if (response['data'][i].role_id == 4) {
                                    role_name = 'Travel Agent';
                                } 
                                else {
                                    role_name = 'Driver';
                                }
                                var role = role_name;

                                var edit =
                                    `<a class="btn btn-info" href="{!! asset('admin/user/edit/` + id + `') !!}">Edit</a>`;
                                createModal({
                                    id: 'user_' + response['data'][i].id,
                                    header: '<h4>Delete</h4>',
                                    body: 'Do you want to continue ?',
                                    footer: `
                <button class="btn btn-danger" onclick="delete_request(` + response['data'][i].id + `)" data-dismiss="modal">
                    Delete
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            `,
                                });

                                var tr_str = "<tr id='row_" + response['data'][i].id + "'>" +
                                    "<td>" + name + "</td>" +
                                    "<td>" + role + "</td>" +
                                    "<td>" + wallet + "</td>" +
                                    
                                    "</tr>";

                                $("#userTableAppend tbody").append(tr_str);
                            }
                        }
                        $(document).ready(function() {
                            console.log('sadasdasdad');
                            $('#userTableAppend').DataTable({
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

                url: "{!! asset('admin/user/delete') !!}/" + id,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{!! @csrf_token() !!}'
                },
                success: function(response) {
                    console.log(response.status);
                    if (response) {
                        var myTable = $('#userTableAppend').DataTable();
                        console.log('removeasdasdasd');
                        myTable.row('#row_' + id).remove().draw();
                    }
                }
            });
        }
    </script>
@endsection
