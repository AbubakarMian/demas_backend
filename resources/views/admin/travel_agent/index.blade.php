@extends('layouts.default_module')
@section('module_name')
Travel Agents
@stop

@section('add_btn')
{!! Form::open(['method' => 'get', 'url' => ['admin/travel_agent/create'], 'files' => true]) !!}
<span>{!! Form::submit('Add Travel Agent', ['class' => 'btn btn-success pull-right']) !!}</span>
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

<table class="fhgyt" id="travel_agentTableAppend" style="opacity: 0">
<thead>
	<tr>
        <th>Name</th>
        <th>Email</th>
        <th>Company no</th>
        <th>License no</th>
        <th>Country</th>
        <th>City</th>
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
         url: '{!!asset("admin/travel_agent/get_travel_agent")!!}',
         type: 'get',
         dataType: 'json',
         success: function(response){
            console.log('response');
            $("#travel_agentTableAppend").css("opacity",1);
           var len = response['data'].length;
		   console.log('response2');

          
              for(var i=0; i<len; i++){
                  var id =  response['data'][i].id;
                  var travel_agent_name =  response['data'][i].user_obj.name;
                  var email =  response['data'][i].user_obj.email;
                  var company_name =  response['data'][i].company_name;
                  var license_num =  response['data'][i].license_num;
                  var country =  response['data'][i].country;
                  var city =  response['data'][i].city;

                console.log('aaa',response['data'][i]);
                // console.log('ccaaa',response['data'][i].transport_type);


                
				  var edit = `<a class="btn btn-info" href="{!!asset('admin/travel_agent/edit/` + id + `')!!}">Edit</a>`;
                       createModal({
                            id: 'travel_agent_' + response['data'][i].id,
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
                        var delete_btn = `<a class="btn btn-info" data-toggle="modal" data-target="#` + 'travel_agent_' + response['data'][i].id + `">Delete</a>`;

                        var tr_str = "<tr id='row_"+response['data'][i].id+"'>" +
                    "<td>" +travel_agent_name+ "</td>" +
                    "<td>" +email+ "</td>" +
                    "<td>" +company_name+ "</td>" +
                    "<td>" +license_num+ "</td>" +
                    "<td>" +country+ "</td>" +
                    "<td>" +city+ "</td>" +
                    "<td>" +edit+ "</td>" +
                    "<td>" +delete_btn+ "</td>" +
       

                "</tr>";

                $("#travel_agentTableAppend tbody").append(tr_str);
                }
                $(document).ready(function() {
console.log('sadasdasdad');
                $('#travel_agentTableAppend').DataTable({
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

            url: "{!!asset('admin/travel_agent/delete')!!}/" + id,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{!!@csrf_token()!!}'
            },
            success: function(response) {
                console.log(response.status);
                if(response){
                    var myTable = $('#travel_agentTableAppend').DataTable();
                    console.log('removeasdasdasd');
                    myTable.row('#row_'+id).remove().draw();
                }
            }
        });
    }

</script>
@endsection

