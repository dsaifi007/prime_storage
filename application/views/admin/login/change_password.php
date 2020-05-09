<style>
    .first-example input{
        margin-bottom:0px;
    }
</style>

<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.html">Home</a></li>
                <li><a href="#">Change Password</a></li>
            </ul>
        </div>
    </div>

    <div class="row animated fadeInUp">
        <div class="setting">
            <div class="col-lg-offset-4 col-lg-4 col-md-6 col-md-offset-3 col-sm-12 col-sm-offset-0">
                <div class="panel b-primary bt-sm mt-xl">
                    <div class="panel-content">
                        <?php echo display_message_info([1 => $success, 2 => $error, 3 => validation_errors()]); ?>
                        <?php echo form_open("change-password/submited", ["id" => "change-pass"]) ?>
                        <h4 class="text-center">Change Password</h4>
                        <div class="first-example form-group">
                            <label>Enter Your Current password</label>
                            <input type="password" id="password-field" name="current_pass" placeholder="Enter Your Current password" >
                            <i id="pass-status" class="fa fa-lock" aria-hidden="true" ></i>
                        </div>
                        <div class="first-example form-group">
                            <label>Enter Your New password</label>
                            <input type="password" id="password" name="password" placeholder="Enter Your New password" >
                            <i id="pass-status1" class="fa fa-lock" aria-hidden="true" ></i>
                        </div>
                        <div class="first-example form-group">
                            <label>Confirm Your  password</label>
                            <input type="password" id="password-field2" name="passconf" placeholder="Confirm Your  password" >
                            <i id="pass-status2" class="fa fa-lock" aria-hidden="true" ></i>
                        </div>
                        <div class="text-center">
                            <input class="btn btn-wide btn-success" type="submit" value="SAVE" />
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js" ></script>
    <script>
        $("document").ready(function () {
            $("#change-pass").validate({
                errorPlacement: function (error, element) {
                    error.appendTo(element.closest('.form-group').after());
                    //element.parent('.input-group').after(error);
                },
                rules: {
                    current_pass: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    passconf: {
                        required: true,
                        minlength: 5,
                        equalTo: "#password"
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                }
            });
        });
    </script>