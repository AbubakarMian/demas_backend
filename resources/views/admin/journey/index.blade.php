@extends('layouts.default_module')
@section('module_name')
JOURNEY
@stop

@section('add_btn')
{!! Form::open(['method' => 'get', 'url' => ['admin/journey/create'], 'files' => true]) !!}
<span>{!! Form::submit('Add Journey', ['class' => 'btn btn-success pull-right']) !!}</span>
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

<table class="fhgyt" id="journeyTableAppend" style="opacity: 0">
<thead>
	<tr>
	    <th> Journey</th>
        {{-- <th> DropOff Location</th> --}}
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
         url: '{!!asset("admin/journey/get_journey")!!}',
         type: 'get',
         dataType: 'json',
         success: function(response){
            console.log('response');
            $("#journeyTableAppend").css("opacity",1);
           var len = response['data'].length;
		   console.log('response2');

           console.log(response);

              for(var i=0; i<len; i++){
                  var id =  response['data'][i].id;
                  var pickup_location_id =  response['data'][i].name;
                //   var dropoff_location_id =  response['data'][i].dropoff_location_id;
                  
				  var edit = `<a class="btn btn-info" href="{!!asset('admin/journey/edit/` + id + `')!!}">Edit</a>`;
                       createModal({
                            id: 'journey_' + response['data'][i].id,
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
                        var delete_btn = `<a class="btn btn-info" data-toggle="modal" data-target="#` + 'journey_' + response['data'][i].id + `">Delete</a>`;

                        var tr_str = "<tr id='row_"+response['data'][i].id+"'>" +
                    "<td>" +pickup_location_id+ "</td>" +
                    // "<td>" +dropoff_location_id+ "</td>" +
                    "<td>" +edit+ "</td>" +
                    "<td>" +delete_btn+ "</td>" +


                "</tr>";

                $("#journeyTableAppend tbody").append(tr_str);
                }
                $(document).ready(function() {
console.log('sadasdasdad');
                $('#journeyTableAppend').DataTable({
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

            url: "{!!asset('admin/journey/delete')!!}/" + id,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{!!@csrf_token()!!}'
            },
            success: function(response) {
                console.log(response.status);
                if(response){
                    var myTable = $('#journeyTableAppend').DataTable();
                    console.log('removeasdasdasd');
                    myTable.row('#row_'+id).remove().draw();
                }
            }
        });
    }

</script>
@endsection

