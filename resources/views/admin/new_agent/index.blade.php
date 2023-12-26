@extends('layouts.default_module')
@section('module_name')
Agent Requests
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

<table class="fhgyt" id="new_agentTableAppend" style="opacity: 0">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>phone_no</th>
            <th>whatsapp_number</th>
            <th>message</th>
            <th>Actions</th>
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


        function convertDate(date) {
            var yyyy = date.getFullYear().toString();
            var mm = (date.getMonth() + 1).toString();
            var dd = date.getDate().toString();

            var mmChars = mm.split('');
            var ddChars = dd.split('');

            return yyyy + '-' + (mmChars[1] ? mm : "0" + mmChars[0]) + '-' + (ddChars[1] ? dd : "0" + ddChars[0]);
        }


        function fetchRecords() {

            $.ajax({
                url: '{!! asset('admin/new_agent/get_new_agent ') !!}',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    console.log('response');
                    $("#new_agentTableAppend").css("opacity", 1);
                    var len = response['data'].length;
                    console.log('response2');

                    console.log(response);

                    for (var i = 0; i < len; i++) {

                        var id = response['data'][i].id;
                        var name = response['data'][i].name;
                        var email = response['data'][i].email;
                        var phone_no = response['data'][i].phone_no;
                        var whatsapp_number = response['data'][i].whatsapp_number;
                        var message = response['data'][i].message;


                        // Now, you can use from_date_formatted and to_date_formatted in your frontend code


                        var action =
                            `<a class="btn btn-success" data-toggle="modal" data-target="#` +
                            'new_agent_accept' + response['data'][i].id + `">Accept</a>
                                <a class="btn btn-danger" data-toggle="modal" data-target="#` +
                            'new_agent_reject' + response['data'][i].id + `">Reject</a>`;
                        createModal({
                            id: 'new_agent_reject' + response['data'][i].id,
                            header: '<h4>Reject</h4>',
                            body: 'Do you want to continue ?',
                            footer: `
                                <button class="btn btn-danger" onclick="delete_request(` + response['data'][i].id + `)"
                                data-dismiss="modal">
                                Reject
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                `,
                        });
                        createModal({
                            id: 'new_agent_accept' + response['data'][i].id,
                            header: '<h4>Accept</h4>',
                            body: 'Do you want to continue ?',
                            footer: `
                                <button class="btn btn-success" onclick="save_request(` + response['data'][i].id + `)"
                                data-dismiss="modal">
                                Accept
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                `,
                        });
                        // var delete_btn =
                        //     `<a class="btn btn-info" data-toggle="modal" data-target="#` +
                        //     'new_agent_' + response['data'][i].id + `">Delete</a>`;

                        var tr_str = "<tr id='row_" + response['data'][i].id + "'>" +
                            "<td>" + name + "</td>" +
                            "<td>" + email + "</td>" +
                            "<td>" + phone_no + "</td>" +
                            "<td>" + whatsapp_number + "</td>" +
                            "<td>" + message + "</td>" +
                            "<td>" + action + "</td>" +
                            // "<td>" + delete_btn + "</td>" +


                            "</tr>";

                        $("#new_agentTableAppend tbody").append(tr_str);
                    }
                    $(document).ready(function() {
                        console.log('sadasdasdad');
                        $('#new_agentTableAppend').DataTable({
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

            url: "{!! asset('admin/new_agent/delete') !!}/" + id,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{!! @csrf_token() !!}'
            },
            success: function(response) {
                console.log(response.status);
                if (response) {
                    var myTable = $('#new_agentTableAppend').DataTable();
                    console.log('removeasdasdasd');
                    myTable.row('#row_' + id).remove().draw();
                }
            }
        });
    }

    function save_request(id) {

        var create_agent_url = "{!! asset('admin/new_agent/create') !!}/" + id;
        window.location.href = create_agent_url;
        // localtion.url();
        // $.ajax({

        //     url: "{!! asset('admin/new_agent/create') !!}/" + id,
        //     type: 'POST',
        //     dataType: 'json',
        //     data: {
        //         _token: '{!! @csrf_token() !!}'
        //     },
        //     success: function(response) {
        //         console.log(response.status);
        //         if (response) {
        //             var myTable = $('#new_agentTableAppend').DataTable();
        //             console.log('removeasdasdasd');
        //             myTable.row('#row_' + id).remove().draw();
        //         }
        //     }
        // });
    }
    
</script>
@endsection