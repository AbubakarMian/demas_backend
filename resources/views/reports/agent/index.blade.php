@extends('layouts.default_module')
@section('module_name')
    Agents
@stop

@section('single_file_use')
    <div>
        <form class="search_filter">
            <div class="row filter_box_p">
                <button style="margin-left: 10px;">Columns</button>
                {{-- modal --}}
                <div class="row show_columns aa-modal">

                </div>
                {{-- modal --}}

                <div class="row">
                    <div class="col-md-2">
                        <label>. From:</label>
                        {!! Form::date('to_date', null, [
                            'class' => 'form-control',
                            'data-parsley-required' => 'true',
                            'data-parsley-trigger' => 'change',
                            'placeholder' => 'Select Date',
                        ]) !!}
                    </div>
                    <div class="col-md-2">
                        <label>To:</label>
                        {!! Form::date('from_date', null, [
                            'class' => 'form-control',
                            'data-parsley-required' => 'true',
                            'data-parsley-trigger' => 'change',
                            'placeholder' => 'Select Date',
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Journey:</label>
                    {!! Form::select('journey_id', $journey_list, null, [
                        'class' => 'form-control',
                        'data-parsley-required' => 'true',
                        'data-parsley-trigger' => 'change',
                        'placeholder' => 'Select Journey',
                    ]) !!}

                </div>
                <div class="col-md-2">
                    <label>Slot:</label>
                    {!! Form::select('slot_id', $slot_list, null, [
                        'class' => 'form-control',
                        'data-parsley-required' => 'true',
                        'data-parsley-trigger' => 'change',
                        'placeholder' => 'Select Slot',
                    ]) !!}

                </div>
                <div class="col-md-2">
                    <label>Transport Type:</label>

                    {!! Form::select('transport_type_id', $transport_type_list, null, [
                        'class' => 'form-control',
                        'data-parsley-required' => 'true',
                        'data-parsley-trigger' => 'change',
                        'placeholder' => 'Select Transport Type',
                    ]) !!}

                </div>
                <div class="col-md-2">
                    <label>Agent: </label>

                    {!! Form::select('travel_agent_user_id', $travel_agent_list, null, [
                        'class' => 'form-control',
                        'data-parsley-required' => 'true',
                        'data-parsley-trigger' => 'change',
                        'placeholder' => 'Select Agent',
                    ]) !!}

                </div>
            </div>
            <div class="search">

                {!! Form::button('Search', ['class' => 'btn btn-success pull-right', 'onclick' => 'fetchRecords()']) !!}

            </div>
        </form>
    </div>

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

    .modal_table th {
        padding: 10px;
    }

    .modal_table td {
        padding: 10px;
    }

    .modal-dialog {
        width: auto !important;
        padding: 10px !important;
        margin: 30px auto !important;
        display: flex;
        justify-content: center;
    }
</style>
@section('table')

    <table class="fhgyt" id="orderTableAppend" style="opacity: 0">
        <thead>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@stop
@section('app_jquery')

    <script>
        $(document).ready(function() {
            $('#orderTableAppend').DataTable({
                dom: '<"top_datatable"B>lftipr',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
            });
            fetchRecords();
        });

        function fetchRecords() {
            console.log('agent ftach er');
            var formdata = $('.search_filter').serialize();
            var showdatacolumn = $('.data-checkbox-show-column:checked').map(function() {
                return $(this).val();
            }).get();

            // Destroy DataTable after the AJAX request is complete
            $.ajax({
                url: "{!! asset('admin/reports/agent/get_order') !!}",
                type: 'post',
                data: formdata,
                dataType: 'json',
                success: function(response) {
                    console.log('response', response);
                    // Clear previous table data
                    $("#orderTableAppend").DataTable().clear().destroy();
                    $("#orderTableAppend").css("opacity", 1);
                    var thead = '<tr>';
                    var tbody = '';
                    var response_info = response['table_info'];
                    var response_data = response['report_data'];
                    $('.show_columns').html(show_columns_filter_html(response_info, showdatacolumn));
                    $.each(response_info, function(report_info_index, report_info_data) {
                        $.each(report_info_data['columns'], function(column_index, column) {
                            if ($.inArray(column['data_column'], showdatacolumn) !== -1 || !
                                showdatacolumn.length) {
                                // thead += '<th style="background-color:  red">' + column['heading'] + '</th>';
                                thead += `<th style="background-color:`+report_info_data['color']+`">` + column['heading'] + `</th>`;
                            }
                        });
                    });
                    thead += '</tr>';
                    $.each(response_data, function(res_data_index, res_data) {
                        tbody += '<tr>';
                        $.each(response_info, function(report_info_index, report_info_data) {
                            $.each(report_info_data['columns'], function(column_index, column) {
                                if ($.inArray(column['data_column'], showdatacolumn) !==
                                    -1 || !showdatacolumn.length) {
                                    tbody += `<td>` + res_data[column['data_column']] +
                                        `</td>`;
                                }
                            });
                        });
                        tbody += '</tr>';
                    });
                    $("#orderTableAppend thead").html(thead);
                    $("#orderTableAppend tbody").html(tbody);

                    // Reinitialize DataTable after updating the table
                    $('#orderTableAppend').DataTable({
                        dom: '<"top_datatable"B>lftipr',
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function show_columns_filter_html(table_info, showdatacolumn) {
            var html = '<div class="data-heading">';
            $.each(table_info, function(t_index, t_info) {
                console.log('t_index', t_index);
                console.log('t_info', t_info);
                html += `<div class="data-subheading">` + t_info.heading;
                html += get_html_checkbox_filter(t_info.columns, showdatacolumn)
                html += `</div>`;
            });
            html += `</div>`;
            return html;
        }

        function get_html_checkbox_filter(columns, showdatacolumn) {
            let check_all = !showdatacolumn.length ? 'checked' : '';
            let html = `<div class="segement-area">` +
                `<input type="checkbox" onclick="check_segment(this)" ` + check_all + `>`;

            $.each(columns, function(c_index, column) {
                var is_checked = '';
                if ($.inArray(column.data_column, showdatacolumn) !== -1 || !showdatacolumn.length) {
                    is_checked = 'checked';
                }
                html += `<input type="checkbox" ` + is_checked +
                    ` class="data-checkbox-show-column" name="show_columns[]" value="` +
                    column.data_column + `">`;
                html += `<label class="data-checkbox-label">` + column.heading + `</label>`;

            });
            html += `</div>`;
            return html;
        }

        function check_segment(e) {
            var is_checked = $(e).is(":checked");
            if (is_checked) {
                $(e).closest('.segement-area').find('.data-checkbox-show-column').prop("checked", true);
            } else {
                $(e).closest('.segement-area').find('.data-checkbox-show-column').prop("checked", false);
            }
        }
    </script>
@endsection
