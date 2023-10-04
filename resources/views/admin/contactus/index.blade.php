@extends('layouts.default_module')
@section('module_name')
Contact Us
@stop

{{-- @section('add_btn')
{!! Form::open(['method' => 'get', 'url' => ['admin/contactus/create'], 'files' => true]) !!}
<span>{!! Form::submit('Add Transport', ['class' => 'btn btn-success pull-right']) !!}</span>
{!! Form::close() !!} --}}
{{-- @stop --}}
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

<table class="fhgyt" id="contactusTableAppend" style="opacity: 0">
<thead>
	<tr>
        <th> Name</th>
        <th> Email</th>
        <th> WhatsApp No</th>
        <th> Message</th>
	    {{-- <th>Edit  </th> --}}
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
         url: '{!!asset("admin/contactus/get_contactus")!!}',
         type: 'get',
         dataType: 'json',
         success: function(response){
            console.log('response');
            $("#contactusTableAppend").css("opacity",1);
           var len = response['data'].length;
		   console.log('response2');

          
              for(var i=0; i<len; i++){
                  var id =  response['data'][i].id;
                  var name =  response['data'][i].name;
                  var email =  response['data'][i].email;
                  var whatsapp_number =  response['data'][i].whatsapp_number;
                  var message = `<a class="btn btn-info" data-toggle="modal" data-target="#` + 'contactus_message' + response['data'][i].id + `">View</a>`;

                console.log('aaa',response['data'][i]);
                // console.log('ccaaa',response['data'][i].transport_type);


                //   var user_owner_id =  response['data'][i].driver.user.name;
                  
				//   var edit = `<a class="btn btn-info" href="{!!asset('admin/contactus/edit/` + id + `')!!}">Edit</a>`;
                createModal({
                            id: 'contactus_message' + response['data'][i].id,
                            header: '<h4>Message</h4>',
                            body: '<p>'+response['data'][i].message+'</p>',
                            footer: `
                              
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                `,
                        });
                       createModal({
                            id: 'contactus_' + response['data'][i].id,
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
                        var delete_btn = `<a class="btn btn-info" data-toggle="modal" data-target="#` + 'contactus_' + response['data'][i].id + `">Delete</a>`;

                        var tr_str = "<tr id='row_"+response['data'][i].id+"'>" +
                    "<td>" +name+ "</td>" +
                    "<td>" +email+ "</td>" +
                    "<td>" +whatsapp_number+ "</td>" +
                    "<td>" +message+ "</td>" +
                    // "<td>" +edit+ "</td>" +
                    "<td>" +delete_btn+ "</td>" +
       

                "</tr>";

                $("#contactusTableAppend tbody").append(tr_str);
                }
                $(document).ready(function() {
console.log('sadasdasdad');
                $('#contactusTableAppend').DataTable({
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

            url: "{!!asset('admin/contactus/delete')!!}/" + id,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{!!@csrf_token()!!}'
            },
            success: function(response) {
                console.log(response.status);
                if(response){
                    var myTable = $('#contactusTableAppend').DataTable();
                    console.log('removeasdasdasd');
                    myTable.row('#row_'+id).remove().draw();
                }
            }
        });
    }

</script>
@endsection

