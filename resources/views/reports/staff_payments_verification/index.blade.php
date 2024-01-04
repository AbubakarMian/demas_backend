@extends('layouts.default_module')
@section('module_name')
    Staff Payments Verification
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

    <table class="fhgyt" id="staff_payments_verificationTableAppend" style="opacity: 0">
        <thead>
            <tr>
                <th> Name</th>
                <th> Staff Type</th>
                <th> Amount</th>
                <th> detail</th>
                {{-- <th> receipt_url</th> --}}
                <th> Verification Status</th>
                <th> Action</th>

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
                    url: '{!! asset('reports/staff_payments_verification/get_staff_payments_verification') !!}',
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        console.log('response');
                        $("#staff_payments_verificationTableAppend").css("opacity", 1);
                        var len = response['data'].length;
                        console.log('response2');


                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var user_id = response['data'][i].user_obj.name;
                            var staff_type = response['data'][i].user_obj.role.name;
                            var amount = response['data'][i].amount;
                            var detail = response['data'][i].detail;
                            var verification_status = response['data'][i].verification_status;

                            console.log('aaa', response['data'][i]);

                            var details = response['data'][i].details;
                            createModal({
                                id: 'staff_payments_verification_confirm_' + response['data'][i]
                                    .id,
                                header: '<h4>Confirm</h4>',
                                body: 'Do you want to continue ?',
                                footer: `
                                <button class="btn btn-danger" onclick="update_request_payment_status(` + response[
                                    'data'][i].id + `,'accepted')"
                                data-dismiss="modal">
                                    Confirm
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                `,
                            });
                            // var action = response['data'][i].verification_status;
                            if (response['data'][i].verification_status == 'pending') {
                                var action =
                                    `<a class="btn btn-warning" data-toggle="modal" data-target="#` +
                                    'staff_payments_verification_confirm_' + response['data'][i].id +
                                    `">Confirm</a> 
                                <a class="btn btn-warning" data-toggle="modal" data-target="#` +
                                    'staff_payments_verification_reject_' + response['data'][i].id +
                                    `">Reject</a>`;
                                createModal({
                                    id: 'staff_payments_verification_reject_' + response['data']
                                        [i]
                                        .id,
                                    header: '<h4>Reject</h4>',
                                    body: 'Do you want to continue ?',
                                    footer: `
                                <button class="btn btn-danger" onclick="update_request_payment_status(` + response[
                                        'data'][i].id + `,'rejected')"
                                data-dismiss="modal">
                                    Reject
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                `,
                                });
                            }else{
                                var action = '';
                            }

                            // var recipt_img = `<img width="42" class="imgshow show-product-img" src="` +
                            // receipt_url + `">`;
                            var tr_str = "<tr id='row_" + response['data'][i].id + "'>" +
                                "<td>" + user_id + "</td>" +
                                "<td>" + staff_type + "</td>" +
                                "<td>" + amount + "</td>" +
                                "<td>" + detail + "</td>" +
                                // "<td>" + recipt_img + "</td>" +
                                "<td>" + verification_status + "</td>" +
                                "<td>" + action + "</td>" +



                                "</tr>";

                            $("#staff_payments_verificationTableAppend tbody").append(tr_str);
                        }
                        $(document).ready(function() {
                            console.log('sadasdasdad');
                            $('#staff_payments_verificationTableAppend').DataTable({
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

        function update_request_payment_status(id, status) {
    var my_url = "{!! asset('reports/staff_payments_verification/verify_status') !!}/" + id;
   // url: "{!! asset('admin/staff_payments_verification/delete') !!}/" + id+'?status'+status,
    $.ajax({
        url: my_url,
        type: 'POST',
        dataType: 'json',
        data: {
            _token: '{!! @csrf_token() !!}',
            status: status
        },
        success: function(response) {
            console.log(response.response.verification_status, 'sada');
            if (response) {
                var myTable = $('#staff_payments_verificationTableAppend').DataTable();

                // Update the content of the verification status cell in the table
                myTable.cell('#row_' + id, 4).data(response.response.verification_status).draw();
                myTable.cell('#row_' + id, 5).data('').draw();

                // Display the verification status elsewhere on the page if needed
                // $('#verificationStatusDisplay').text(response.response.verification_status);
            }
        }
    });
}

    </script>
@endsection
