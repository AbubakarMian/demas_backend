@extends('layouts.default_module')
@section('module_name')
Staff Payments Incoming
@stop

@section('add_btn')
{!! Form::open(['method' => 'get', 'url' => ['reports/staff_payments_incoming/create'], 'files' => true]) !!}
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

<table class="fhgyt" id="staff_payments_incomingTableAppend" style="opacity: 0">
<thead>
	<tr>
        <th> Name</th>
        <th> Staff Type</th>
        <th> Amount</th>
        <th> detail</th>
        <th> Verification Status</th>
	  
	</tr>
</thead>
<tbody>
</tbody>
</table>

@stop
@section('app_jquery')

<script>

$(document).ready(function(){

    fetchRecords();

    function fetchRecords(){

       $.ajax({
         url: '{!!asset("reports/staff_payments_incoming/get_staff_payments_incoming")!!}',
         type: 'get',
         dataType: 'json',
         success: function(response){
            console.log('response');
            $("#staff_payments_incomingTableAppend").css("opacity",1);
           var len = response['data'].length;
		   console.log('response2');

          
              for(var i=0; i<len; i++){
                  var id =  response['data'][i].id;
                  var user_id =  response['data'][i].user_obj.name;
                  var staff_type =  response['data'][i].user_obj.role.name;
                  var amount =  response['data'][i].amount;
                  var detail =  response['data'][i].detail;
                  var verification_status =  response['data'][i].verification_status;

                console.log('aaa',response['data'][i]);
                // console.log('ccaaa',response['data'][i].transport_type);


                //   var user_owner_id =  response['data'][i].driver.user.name;
                  var details =  response['data'][i].details;
                  
				  var edit = `<a class="btn btn-info" href="{!!asset('reports/staff_payments_incoming/edit/` + id + `')!!}">Edit</a>`;
                       createModal({
                            id: 'staff_payments_incoming_' + response['data'][i].id,
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
                        var delete_btn = `<a class="btn btn-info" data-toggle="modal" data-target="#` + 'staff_payments_incoming_' + response['data'][i].id + `">Delete</a>`;
                        // var img = `<img width="42" src="`+image+`">`;
                        var tr_str = "<tr id='row_"+response['data'][i].id+"'>" +
                    "<td>" +user_id+ "</td>" +
                    "<td>" +staff_type+ "</td>" +
                    "<td>" +amount+ "</td>" +
                    "<td>" +detail+ "</td>" +
                    "<td>" +verification_status+ "</td>" +
                    
       

                "</tr>";

                $("#staff_payments_incomingTableAppend tbody").append(tr_str);
                }
                $(document).ready(function() {
console.log('sadasdasdad');
                $('#staff_payments_incomingTableAppend').DataTable({
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

function set_msg_modal(msg){
        $('.set_msg_modal').html(msg);
    }
    function delete_request(id) {
        $.ajax({

            url: "{!!asset('admin/staff_payments_incoming/delete')!!}/" + id,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{!!@csrf_token()!!}'
            },
            success: function(response) {
                console.log(response.status);
                if(response){
                    var myTable = $('#staff_payments_incomingTableAppend').DataTable();
                    console.log('removeasdasdasd');
                    myTable.row('#row_'+id).remove().draw();
                }
            }
        });
    }

</script>
@endsection

