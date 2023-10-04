@extends('layouts.default_module')
@section('module_name')
Transport Prices
@stop

@section('add_btn')
{!! Form::open(['method' => 'get', 'url' => ['admin/car/create'], 'files' => true]) !!}
{{-- <span>{!! Form::submit('Add Transport prices', ['class' => 'btn btn-success pull-right']) !!}</span> --}}
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
        <th> Journey</th>
        <th> Slots</th>
        <th> Transport Type</th>
        <th> Price</th>
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
                  var journey =  response['data'][i].journey.name;
                  var slot =  response['data'][i].slot.name;
                  var price =  response['data'][i].price;
                  var transport_prices_id =  response['data'][i].id;
            
                        var tr_str = "<tr id='row_"+response['data'][i].id+"'>" +
                    "<td>" +journey+ "</td>" +
                    "<td>" +slot+ "</td>" +
                    "<td>" +transport_type_name+ "</td>" +
                    "<td><input onchange=update_user_price("+transport_prices_id+",this) type='text' value='" +price+ "'></td>" +
     
                "</tr>";

                $("#carTableAppend tbody").append(tr_str);
                }
                $(document).ready(function() {
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

function update_delay_user_price(transport_prices_id,e){

    setTimeout(() => {
        update_user_price(transport_prices_id,e);
    }, 500);
}
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

