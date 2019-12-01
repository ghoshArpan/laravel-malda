@extends('layout.master')
@section('content')

<div class="row" id="row-content">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
				<h3 class="card-title">User List</h3>



                <div class="form-horizontal">
                    <div class="form-group row">
                        {{csrf_field()}}
                         
                        <div class="datatbl " style="width: 96%;margin: 20px;">
                            <table class="table table-striped table-bordered table-hover" id="tbl_user_list" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>              
                                        <th>Mobile Number</th> 
                                        <th>Designation</th>   
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody></tbody>
                                <!-- Table Footer -->

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div> 
</div>



@endsection

@section('script')

<script>



    $(document).ready(function () {


        create_table();

        // $("#search_button").click(function(){

        // 	create_table();

        // });

        var table = $('#tbl_user_list').DataTable();
        table.on('draw.dt', function () {

            $('.edit-button').click(function () {
                var user_code = this.id;

                // alert(user_code);


                var datas = {'user_code': user_code, '_token': $('input[name="_token"]').val()};
                redirectPost('{{url("user_edit")}}', datas);
            });

            $('.delete-button').click(function () {

                var reply = confirm('Are you sure to delete the record?');
                if (!reply) {
                    return false;
                }
                var user_code = this.id;
                // alert(road_challan_code);
                $.ajax({
                    type: 'post',
                    url: 'user_delete',
                    data: {'user_code': user_code, '_token': $('input[name="_token"]').val()},
                    dataType: 'json',
                    success: function (datam) {
                        if (datam.status == 1) {
                            create_table();
                            $.alert({
                                type: 'green',
                                icon: 'fa fa-check',
                                title: 'Success!!',
                                content: '<strong>SUCCESS:</strong> User deleted successfully.'
                            });
                        } else {
                            $.alert({
                                type: 'red',
                                icon: 'fa fa-warning',
                                title: 'Error!!',
                                content: '<strong>SUCCESS:</strong> Failed to delete data.'
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        var msg = "<strong>Failed to Delete data.</strong><br/>";
                        if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                            msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                        } else {
                            if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                                if (jqXHR.responseJSON.exception_code == 23000) {
                                    msg += "Data Already Used!! Cannot Be Deleted.";
                                }
                            } else {
                                msg += "Error(s):<strong><ul>";
                                $.each(jqXHR.responseJSON, function (key, value) {
                                    msg += "<li>" + value + "</li>";
                                });
                                msg += "</ul></strong>";
                            }
                        }
                        $.alert({
                            type: 'red',
                            icon: 'fa fa-warning',
                            title: 'Error!!',
                            content: msg
                        });

                    }
                });
            });


        });





    });






    function create_table() {
        var table = "";
        var token = $('input[name="_token"]').val();



        $("#tbl_user_list").dataTable().fnDestroy();
        table = $('#tbl_user_list').DataTable({
            "responsive": true,
            bProcessing: true,
            bServerSide: true,
            bjQueryUI: true,
            "bInfo": false,

            "ajax": {
                url: "userlist_datatable",
                type: "post",
                data: {'_token': $('input[name="_token"]').val()},
                dataSrc: "record_details",
                error: function (jqXHR, textStatus, errorThrown) {
                    var msg = "";
                    if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                        msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                    } else {
                        if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                            if (jqXHR.responseJSON.exception_code == 23000) {
                                msg += "Some Sql Exception Occured";
                            } else {
                                msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                            }
                        } else {
                            msg += "Error(s):<strong><ul>";
                            $.each(jqXHR.responseJSON['errors'], function (key, value) {
                                msg += "<li>" + value + "</li>";
                            });
                            msg += "</ul></strong>";
                        }
                    }
                    $.alert({
                        title: 'Error!!',
                        type: 'red',
                        icon: 'fa fa-warning',
                        content: msg,
                    });
                }
            },
            "dataType": 'json',
            "columnDefs":
                    [
                        {className: "table-text", "targets": "_all"},
                        {
                            "targets": 0,
                            "data": "id",
                            "searchable": false,
                            "sortable": false,
                            "defaultContent": ""
                        },
                        {
                            "targets": 1,
                            "sortable": true,
                            "data": "name",

                        },
                        {
                            "targets": 2,
                            "sortable": true,
                            "data": "mobile_no",

                        },
                        {
                            "targets": 3,
                            "sortable": true,
                            "data": "designation",

                        },

                        {
                            "targets": -1,
                            "data": 'action',
                            "searchable": false,
                            "sortable": false,
                            "render": function (data, type, full, meta) {
                                var str_btns = "";
                                str_btns += '<button type="button"  class="btn btn-success  edit-button btn_new1" id="' + data.e + '" title="Edit"><i class="fa fa-edit"></i></button>&nbsp;';

                                str_btns += '<button type="button"  class="btn btn-info delete-button btn_new1" id="' + data.d + '" title="Delete"><i class="fa fa-trash"></i></button>&nbsp;';



                                return str_btns;
                            }
                        }
                    ],

            "order": [[1, 'asc']]
        });
        table.on('order.dt search.dt draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
            table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = table.page() * table.page.len() + (i + 1);
            });
        });
    }

    function redirectPost(url, data1) {
        var $form = $("<form />");
        $form.attr("action", url);
        $form.attr("method", "post");
        //         $form.attr("target", "_blank");
        for (var data in data1)
            $form.append('<input type="hidden" name="' + data + '" value="' + data1[data] + '" />');
        $("body").append($form);
        $form.submit();
    }
</script>



@endsection 