@extends('layouts.default_module')
@section('module_name')
Prices
@stop

@section('add_btn')
{!! Form::open(['method' => 'get', 'url' => ['admin/car/create'], 'files' => true]) !!}
<span>{!! Form::submit('Add car', ['class' => 'btn btn-success pull-right']) !!}</span>
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

<table class="fhgyt" id="carTableAppend" style="opacity: 0">
<thead>
	<tr>
        <th> Location</th>
        <th> Journey Slots</th>
        <th> Transport Type</th>
        <th> Price</th>
	    {{-- <th>Edit  </th>
		<th>Delete  </th> --}}
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
         url: '{!!asset("admin/price/get_car_prices")!!}',
         type: 'get',
         dataType: 'json',
         success: function(response){
            console.log('response');
            $("#carTableAppend").css("opacity",1);
           var len = response['data'].length;
		   console.log('response2');

          
              for(var i=0; i<len; i++){
                  var id =  response['data'][i].id;
                  var transport_type_name =  response['data'][i].transport_type.name;

                console.log('aaa',response['data'][i]);
                // console.log('ccaaa',response['data'][i].transport_type);


                  var journey =  response['data'][i].journey.name;
                  var journey_slot =  response['data'][i].slot.slot_name;
                  var price =  response['data'][i].price;
                  var transport_prices_id =  response['data'][i].transport_prices_id;
                  
				//   var edit = `<a class="btn btn-info" href="{!!asset('admin/car/edit/` + id + `')!!}">Edit</a>`;
                //        createModal({
                //             id: 'car_' + response['data'][i].id,
                //             header: '<h4>Delete</h4>',
                //             body: 'Do you want to continue ?',
                //             footer: `
                //                 <button class="btn btn-danger" onclick="delete_request(` + response['data'][i].id + `)"
                //                 data-dismiss="modal">
                //                     Delete
                //                 </button>
                //                 <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                //                 `,
                //         });
                //         var delete_btn = `<a class="btn btn-info" data-toggle="modal" data-target="#` + 'car_' + response['data'][i].id + `">Delete</a>`;

                        var tr_str = "<tr id='row_"+response['data'][i].id+"'>" +
                    "<td>" +journey+ "</td>" +
                    "<td>" +journey_slot+ "</td>" +
                    "<td>" +transport_type_name+ "</td>" +
                    "<td><input onchange=update_user_price("+transport_prices_id+",this) type='text' value='" +price+ "'></td>" +
                    // "<td>" +edit+ "</td>" +
                    // "<td>" +delete_btn+ "</td>" +
       

                "</tr>";

                $("#carTableAppend tbody").append(tr_str);
                }
                $(document).ready(function() {
console.log('sadasdasdad');
                $('#carTableAppend').DataTable({
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

function update_user_price(transport_prices_id,e){

    var price = $(e).val();
    $.ajax({
        url:'{!!asset("admin/price/update_price")!!}/'+transport_prices_id+'?price='+price,
         type: 'get',
         dataType: 'json',
         success: function(response){
            console.log('response');
    }

});
}
function set_msg_modal(msg){
        $('.set_msg_modal').html(msg);
    }
    function delete_request(id) {
        $.ajax({

            url: "{!!asset('admin/car/delete')!!}/" + id,
            type: 'POST',
            dataType: 'json',
            data: {
                _token: '{!!@csrf_token()!!}'
            },
            success: function(response) {
                console.log(response.status);
                if(response){
                    var myTable = $('#carTableAppend').DataTable();
                    console.log('removeasdasdasd');
                    myTable.row('#row_'+id).remove().draw();
                }
            }
        });
    }

</script>
@endsection

