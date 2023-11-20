@extends('layouts.default_module')
@section('module_name')
Driver
@stop

@section('add_btn')
{!! Form::open(['method' => 'get', 'url' => ['admin/driver/create'], 'files' => true]) !!}
<span>{!! Form::submit('Add Driver', ['class' => 'btn btn-success pull-right']) !!}</span>
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

<table class="fhgyt" id="driverTableAppend" style="opacity: 0">
<thead>
	<tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone no</th>
        <th>whatsapp no</th>
        <th>Iqama no</th>
        <th>Category</th>
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
         url: '{!!asset("admin/driver/get_driver")!!}',
         type: 'get',
         dataType: 'json',
         success: function(response){
            console.log('response');
            $("#driverTableAppend").css("opacity",1);
           var len = response['data'].length;
		   console.log('response2');

          
              for(var i=0; i<len; i++){
                  var id =  response['data'][i].id;
                  var driver_name =  response['data'][i].user_obj.name;
                  var email =  response['data'][i].user_obj.email;
                  var phone_no =  response['data'][i].user_obj.phone_no;
                  var whatsapp_number =  response['data'][i].user_obj.whatsapp_number;
                  var iqama_number =  response['data'][i].iqama_number;
                  var driver_category =  response['data'][i].driver_category;

                console.log('aaa',response['data'][i]);
                // console.log('ccaaa',response['data'][i].transport_type);


                
				  var edit = `<a class="btn btn-info" href="{!!asset('admin/driver/edit/` + id + `')!!}">Edit</a>`;
                       createModal({
                            id: 'driver_' + response['data'][i].id,
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
                        var delete_btn = `<a class="btn btn-info" data-toggle="modal" data-target="#` + 'driver_' + response['data'][i].id + `">Delete</a>`;

                        var tr_str = "<tr id='row_"+response['data'][i].id+"'>" +
                    "<td>" +driver_name+ "</td>" +
                    "<td>" +email+ "</td>" +
                    "<td>" +phone_no+ "</td>" +
                    "<td>" +whatsapp_number+ "</td>" +
                    "<td>" +iqama_number+ "</td>" +
                    "<td>" + format_value_for_display(driver_category)+ "</td>" +
                    "<td>" +edit+ "</td>" +
                    "<td>" +delete_btn+ "</td>" +
       

                "</tr>";

                $("#driverTableAppend tbody").append(tr_str);
                }
                $(document).ready(function() {
console.log('sadasdasdad');
                $('#driverTableAppend').DataTable({
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

            url: "{!!asset('admin/driver/delete')!!}/" + id,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{!!@csrf_token()!!}'
            },
            success: function(response) {
                console.log(response.status);
                if(response){
                    var myTable = $('#driverTableAppend').DataTable();
                    console.log('removeasdasdasd');
                    myTable.row('#row_'+id).remove().draw();
                }
            }
        });
    }

</script>
@endsection

