
<!doctype html>
<html lang="en" class="fixed accounts sign-in">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>Prime Storage</title>
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/admin/img/favicon-32x32.png">

        <!--BASIC css-->
        <!-- ========================================================= -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/animate.css">
        <!--SECTION css-->
        <!-- ========================================================= -->
        <!--TEMPLATE css-->
        <!-- ========================================================= -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/style.css">
    </head>

    <body>
        <div class="wrap">
            <!-- page BODY -->
            <!-- ========================================================= -->
            <div class="page-body animated slideInDown">
                <!-- =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
                <!--LOGO-->
                <div class="logo">
                    <img alt="logo" src="<?php echo base_url(); ?>assets/admin/img/logo.png" />
                </div>
                <div class="box">
                    <!--SIGN IN FORM-->
                    <div class="panel mb-none">
                        <div class="panel-content bg-scale-0">
                            <?php //echo validation_errors("<div class='btn btn-primary btn-block'>","</div>"); ?>
                            <?php echo display_message_info([1 => $success, 2 => $error, 3 => validation_errors()]); ?>
                            <?php echo form_open("login/submit", ["id" => "login"]); ?>
                            <h4><center><?php echo $this->lang->line("login_label");?></center></h4>
                            <div class="form-group mt-md">
                                <span class="input-with-icon">
                                    <?php
                                    $email = [
                                        "type"=>"email",
                                        "class"=>"form-control",
                                        "name"=>"email",
                                        "id"=>"email",
                                        "placeholder"=>"Email",
                                        'value' => set_value('email')
                                    ];
                                    echo form_input($email);
                                    ?>                                   
                                    <i class="fa fa-envelope"></i>
                                </span>
                            </div>
                            <div class="form-group">
                                <span class="input-with-icon">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                    <i class="fa fa-key"></i>
                                </span>
                            </div>
                            <div class="form-group" style="display:none"> 
                                <div class="checkbox-custom checkbox-primary"> 
                                    <input type="checkbox" id="remember-me" value="option1" checked> 
                                    <label class="check" for="remember-me">Remember me</label> 
                                </div> 
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary btn-block" value="Sign in" name="submit">
                            </div>
                            <div class="form-group text-center">
                                <a href="<?php echo base_url();?>forgot-password">Forgot password?</a>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url(); ?>assets/admin/js/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js" ></script>
        <script>
            $("document").ready(function () {
                $("#login").validate({
                    errorPlacement: function (error, element) {
                        error.appendTo(element.closest('.form-group').after());
                        //element.parent('.input-group').after(error);
                    },
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                            minlength: 5
                        },
//                        messages: {
//                            email: {
//                                required: "Please enter a email",
//                                minlength: "Your email must consist of at least 2 characters"
//                            },
//                            password: {
//                                required: "Please provide a password",
//                                minlength: "Your password must be at least 5 characters long"
//                            }
//                        },
                        // Make sure the form is submitted to the destination defined
                        // in the "action" attribute of the form when valid
                        submitHandler: function (form) {
                            form.submit();
                        }
                    }
                });
            });
        </script>
        <noscript>Sorry, your browser does not support JavaScript!</noscript>

    </body>
</html>
