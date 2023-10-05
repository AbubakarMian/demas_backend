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
</style>
@section('table')

<table class="fhgyt" id="orderTableAppend" style="opacity: 0">
<thead>
	<tr>
        <th> Name</th>
        <th> Transport Type</th>
        <th> Seats</th>
        <th> Luggage</th>
        <th> Doors</th>
        {{-- <th> Details</th> --}}
	    <th>Edit  </th>
		<th>Delete  </th>
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
         url: '{!!asset("admin/order/get_order")!!}',
         type: 'get',
         dataType: 'json',
         success: function(response){
            console.log('response');
            $("#orderTableAppend").css("opacity",1);
           var len = response['data'].length;
		   console.log('response2');

          
              for(var i=0; i<len; i++){
                  var id =  response['data'][i].id;
                  var name =  response['data'][i].name;
                  var transport_type_name =  response['data'][i].transport_type?.name;

                console.log('aaa',response['data'][i]);
                // console.log('ccaaa',response['data'][i].transport_type);


                //   var user_owner_id =  response['data'][i].driver.user.name;
                  var details =  response['data'][i].details;
                  
				  var edit = `<a class="btn btn-info" href="{!!asset('admin/order/edit/` + id + `')!!}">Edit</a>`;
                       createModal({
                            id: 'order_' + response['data'][i].id,
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
                        var delete_btn = `<a class="btn btn-info" data-toggle="modal" data-target="#` + 'order_' + response['data'][i].id + `">Delete</a>`;
                        // var img = `<img width="42" src="`+image+`">`;
                        var tr_str = "<tr id='row_"+response['data'][i].id+"'>" +
                    "<td>" +name+ "</td>" +
                    "<td>" +transport_type_name+ "</td>" +
                    "<td>" +response['data'][i].seats+ "</td>" +
                    "<td>" +response['data'][i].luggage+ "</td>" +
                    "<td>" +response['data'][i].doors+ "</td>" +
                    // "<td>" +user_owner_id+ "</td>" +
                    // "<td>" +details+ "</td>" +
                    "<td>" +edit+ "</td>" +
                    "<td>" +delete_btn+ "</td>" +
       

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

});

function set_msg_modal(msg){
        $('.set_msg_modal').html(msg);
    }
    function delete_request(id) {
        $.ajax({

            url: "{!!asset('admin/order/delete')!!}/" + id,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{!!@csrf_token()!!}'
            },
            success: function(response) {
                console.log(response.status);
                if(response){
                    var myTable = $('#orderTableAppend').DataTable();
                    console.log('removeasdasdasd');
                    myTable.row('#row_'+id).remove().draw();
                }
            }
        });
    }

</script>
@endsection
