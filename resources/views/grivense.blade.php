@extends('layout.frontmaster')
@section('content')
<div class="row" id="row-content">
    <div class="col-12">                        
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Grievance</h3>
                <div class="row">
                    <div class="col-sm-4  offset-sm-4">
                        <div class="alert alert-danger" style="display: none" id="error"></div>
                    </div>
                </div>

                {{Form::open(['name'=>'grivense_form','id'=>'grivense_form','url' => '', 'method' => 'post'])}}
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::label('grivense_name', 'Name:', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}
                    </div>
                    <div class="col-sm-4">
                        {{Form::text('grivense_name', '', ['id'=>'grivense_name','placeholder'=>'Enter Name','autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'30']) }}
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::label('mobile_no', 'Mobile Number :', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}
                    </div>
                    <div class="col-sm-4">
                        {{Form::text('mobile_no', '', ['id'=>'mobile_no','placeholder'=>'Enter Mobile No','autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'10']) }}
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::label('grivense_email', 'Email :', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}
                    </div>
                    <div class="col-sm-4">
                        {{Form::text('grivense_email', '', ['id'=>'grivense_email','placeholder'=>'Enter Email','autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'30']) }}
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-2 mg-t-10">
                        {{Form::label('grivense_complain', 'Complain :', ['class' => 'form-label mg-b-0 required','style'=>'font-weight:800; font-size:16px;']) }}
                    </div>
                    <div class="col-sm-4">
                        {{Form::textarea('grivense_complain', '', ['id'=>'grivense_complain','rows'=>"4", 'cols'=>"50",'autocomplete'=>'off', 'class' => 'form-control', 'maxlength'=>'300']) }}
                    </div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>
                <?php if(env("CAPTCHA")==1){  ?>

                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="form-group col-md-4">
                        <div class="captcha">
                            <span>{!! captcha_img() !!}</span>
                            <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="form-group col-md-4">
                        <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"></div>
                </div>
                <?php } ?>
                <div class="row">

                    <div class="col-sm-4">&nbsp;</div>
                    <div class="col-sm-4">
                        {{ Form::button('Submit', ['class' => 'btn btn-primary btn-block', 'type' => 'submit','id'=>'Submit']) }}
                    </div>

                </div>

                <div id="tbl_t" style="padding-left: 10px;"></div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#refresh').click(function () {
            $('#error').hide();
            $.ajax({
                type: 'GET',
                url: 'refreshcaptcha',
                dataType: 'json',
                success: function (data) {
                    $(".captcha span").html(data.captcha);
                    $("#case_number").val('');
                    $("#captcha").val('');
                }
            });
        });
        $('#grivense_form').bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                grivense_name: {
                    validators: {
                        notEmpty: {
                            message: 'Enter Name'
                        },
                        regexp: {
                            regexp: /^[a-z\s]+$/i,
                            message: 'Name consist of alphabatical characters and spaces only'
                        }
                    }
                },
                mobile_no: {
                    validators: {
                        notEmpty: {
                            message: 'Enter Mobile Number'
                        },
                        digits: {
                            message: 'Mobile Number is not valid'
                        },
                        stringLength: {
                            min: 10,
                            max: 10,
                            message: 'Mobile Number have 10 digit'
                        }
                    }
                },
                grivense_email: {
                    validators: {
                        notEmpty: {
                            message: 'Email Id Is Required'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i,
                            message: 'Enter correct email Format'
                        }
                    }
                },
                grivense_complain: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter complain'
                        },
                        regexp: {
                            regexp: /^[A-Za-z0-9\/.,\s()-]+$/i,
                            message: 'Alphanumric and some special characters like ()./- allow'
                        }
                    }
                },
                captcha: {
                    validators: {
                        notEmpty: {
                            message: 'Captcha is Required'
                        }

                    }
                }
            }
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            grivanceSave();
        });
        function grivanceSave() {
            $('#error').hide();
            var grivense_name = $('#grivense_name').val();
            var grivense_complain = $('#grivense_complain').val();
            var grivense_email = $('#grivense_email').val();
            var mobile_no = $('#mobile_no').val();
            var captcha = $('#captcha').val();

            var fd = new FormData();
            fd.append('grivense_name', grivense_name);
            fd.append('mobile_no', mobile_no);
            fd.append('grivense_email', grivense_email);
            fd.append('grivense_complain', grivense_complain);
            fd.append('captcha', captcha);
            fd.append('_token', '{{ csrf_token() }}');

            $.ajax({
                type: 'POST',
                url: "{{route('save_otp_for_grievance')}}",
                data: {'mobile_no': mobile_no, '_token': $("input[name='_token']").val()},
                dataType: "json",
                success: function (data) {
                    if (data.status == 1) {
                        var jc = $.confirm({
                            title: 'Please enter OTP to continue',
                            content: '<input type="hidden" class="form-control" id="mob_no_new" name="mob_no_new"  autocomplete="off" value="' + mobile_no + '"><br><input type="text" class="form-control" id="otp" name="otp"  autocomplete="off" placeholder="OTP">',
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                resend: {
                                    btnClass: 'btn-danger',
                                    action: function () {
                                        $.ajax({
                                            type: 'POST',
                                            url: "{{route('save_otp_for_grievance')}}",
                                            data: {'mobile_no': mobile_no, '_token': $("input[name='_token']").val()},
                                            dataType: "json",
                                            success: function (data) {
                                                jc.open(true);
                                            },
                                            error: function (jqXHR, textStatus, errorThrown) {
                                                $(".se-pre-con").fadeOut("slow");
                                                var msg = "";
                                                if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                                                    msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                                                } else {
                                                    if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                                                        msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                                                    } else {
                                                        msg += "Error(s):<strong><ul>";
                                                        $.each(jqXHR.responseJSON['errors'], function (key, value) {
                                                            msg += "<li>" + value + "</li>";
                                                        });
                                                        msg += "</ul></strong>";
                                                    }
                                                }
//                                                $.alert({
//                                                    title: 'Error!!',
//                                                    type: 'red',
//                                                    icon: 'fa fa-warning',
//                                                    content: msg,
//                                                });
                                                $('#error').html('');
                                                $('#error').append(msg);
                                                $('#error').show();
                                                // $("#save_app").attr('disabled',false);
                                            }
                                        });
                                    }
                                },
                                next: {
                                    btnClass: 'btn-primary',
                                    action: function () {
                                        jc.showLoading(true);
                                        var mob_no_new = $("#mob_no_new").val();
                                        var otp = $("#otp").val();
                                        if (isNaN(otp)) {
                                            jc.hideLoading(true);
                                            $.alert('Otp must be an integer');
                                            return false;
                                            jc.open(true);
                                        }
                                        if (isNaN(mob_no_new)) {
                                            jc.hideLoading(true);
                                            $.alert('Mobile No must be an integer');
                                            return false;
                                            jc.open(true);
                                        }
                                        return $.ajax({
                                            url: "{{route('check_otp_for_grievance')}}",
                                            dataType: 'json',
                                            data: {'mob': $("#mob_no_new").val(), 'otp': $("#otp").val(), '_token': $("input[name='_token']").val()},
                                            method: 'post'
                                        }).done(function (response) {
                                            //alert('hi');
                                            jc.hideLoading(true);
                                            if (response.status == 1) {
                                                jc.close(true);

                                                $.ajax({
                                                    type: 'POST',
                                                    url: "{{route('grivanceSave')}}",
                                                    data: fd,
                                                    processData: false,
                                                    contentType: false,
                                                    dataType: 'json',
                                                    success: function (data) {
                                                        if (data.status == 1) {
                                                            $.confirm({
                                                                title: 'Success!!',
                                                                type: 'green',
                                                                icon: 'fa fa-check',
                                                                content: "Grievance saved successfully.",
                                                                buttons: {
                                                                    ok: function () {
                                                                        location.reload();
                                                                    },

                                                                }
                                                            });

                                                        } else {
                                                            $.alert({
                                                                title: 'Error!!',
                                                                type: 'red',
                                                                icon: 'fa fa-warning',
                                                                content: '<strong>failed to save data. Try again</strong>',
                                                                buttons: {
                                                                    ok: function () {
                                                                        location.reload();
                                                                    },

                                                                }

                                                            });
                                                        }
                                                    },
                                                    error: function (jqXHR, textStatus, errorThrown) {

                                                        var msg = "";
                                                        if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                                                            msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                                                        } else {
                                                            if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                                                                msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                                                            } else {
                                                                msg += "Error(s):<strong><ul>";
                                                                $.each(jqXHR.responseJSON['errors'], function (key, value) {
                                                                    msg += "<li>" + value + "</li>";
                                                                });
                                                                msg += "</ul></strong>";
                                                            }
                                                        }
//                                                        $.alert({
//                                                            title: 'Error!!',
//                                                            type: 'red',
//                                                            icon: 'fa fa-warning',
//                                                            content: msg,
//                                                        });
                                                        $('#error').html('');
                                                        $('#error').append(msg);
                                                        $('#error').show();
                                                    }
                                                });
                                            } else {
                                                $.confirm({
                                                    title: 'Error!!',
                                                    type: 'red',
                                                    icon: 'fa fa-warning',
                                                    content: "Please Enter Corretct Otp To continue",
                                                    buttons: {
                                                        Ok: function () {
                                                            jc.open(true);
                                                        },
                                                    }
                                                });
                                            }
                                        }).fail(function (jqXHR, textStatus, errorThrown) {
                                            $(".se-pre-con").fadeOut("slow");
                                            var msg = "";
                                            if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                                                msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                                            } else {
                                                if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                                                    msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
                                                } else {
                                                    msg += "Error(s):<strong><ul>";
                                                    $.each(jqXHR.responseJSON, function (key, value) {
                                                        msg += "<li>" + value + "</li>";
                                                    });
                                                    msg += "</ul></strong>";
                                                }
                                            }
//                                            $.alert({
//                                                title: 'Error!!',
//                                                type: 'red',
//                                                icon: 'fa fa-warning',
//                                                content: msg,
//                                            });
                                            $('#error').html('');
                                            $('#error').append(msg);
                                            $('#error').show();
                                        });
                                    }
                                },
                                close: function () {
                                }
                            },
                            onOpen: function () {
                                startTimer();
                            }
                        });
                    } else {
//                        $.confirm({
//                            title: 'Error!!',
//                            type: 'red',
//                            icon: 'fa fa-warning',
//                            content: 'Mobile no is already register',
//                        });
                        $('#error').html('');
                        $('#error').append('Mobile no is already register');
                        $('#error').show();

                    }
                    function startTimer() {
                        var counter = 30;
                        setInterval(function () {
                            counter--;
                            if (counter >= 0) {
                                jc.buttons.resend.setText(counter + " Sec Remaining");
                            }
                            if (counter === 0) {
                                jc.buttons.resend.removeClass("btn-danger");
                                jc.buttons.resend.setText("Resend OTP");
                                jc.buttons.resend.addClass("btn-success")
                            }
                        }, 1000);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(".se-pre-con").fadeOut("slow");
                    var msg = "";
                    if (jqXHR.status !== 422 && jqXHR.status !== 400) {
                        msg += "<strong>" + jqXHR.status + ": " + errorThrown + "</strong>";
                    } else {
                        if (jqXHR.responseJSON.hasOwnProperty('exception')) {
                            msg += "Exception: <strong>" + jqXHR.responseJSON.exception_message + "</strong>";
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
                    $('#error').html('');
                    $('#error').append(msg);
                    $('#error').show();
                }
            });
        }
    });
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>

@endsection