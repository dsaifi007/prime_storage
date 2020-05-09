
<style>
    .dataTables_filter{
        float:right
    }
</style>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {display:none;}

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    .manage-schools .switch{
        width: 60px;
        height: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 19px;
        width: 19px;
        left: 4px;
        bottom: 3px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #402a73;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(35px);
        -ms-transform: translateX(35px);
        transform: translateX(35px);
    }

    Rounded sliders 
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url();?>subadmin-list">Manage Sub Admins</a></li>
                <li><a href="#">Add New Sub Admin</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="manage-schools manage-user">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-content">  
                        <?php echo display_message_info([1 => $success, 2 => $error, 3 => validation_errors()]); ?>
                        <?php echo form_open("subadmin/submited", ["id" => "subadmin"]); ?>
                        <div class="col-md-4 col-lg-4 ">
                            <div class="form-group">
                                <label for="password">Name </label>
                                <?php
                                $name = ["class" => "form-control", "id" => "name", "name" => "full_name", "placeholder" => "Enter Name"];
                                echo form_input($name);
                                ?>
                            </div>

                        </div>
                        <div class="col-md-4 col-lg-4 ">
                            <div class="form-group">
                                <label for="password">Email</label>
                                <?php
                                $email = ["class" => "form-control", "type" => "email", "id" => "email", "name" => "email", "placeholder" => "Enter Email"];
                                echo form_input($email);
                                ?>
                            </div>

                        </div>
                        <div class="col-md-4 col-lg-4 ">
                            <div class="form-group">
                                <label for="password">Contact Number</label>
                                <?php
                                $phone = ["class" => "form-control", "id" => "phone", "name" => "phone", "placeholder" => "Enter Phone"];
                                echo form_input($phone);
                                ?>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-12 manage_subadmins">
                            <h4>Assign Roles<font style="margin-left: 750px;">Block/Unblock</font></h4>

                            <?php
                            if ($module_name) {
                                foreach ($module_name as $value) {
                                    ?>
                                    <!-- roles start -->
                                    <div class="col-md-6 text-left">
                                        <h4><?php echo $value->module_name; ?></h4>
                                    </div>
                                    <div class="col-md-6 rightPermissions text-right">
                                        <label class="switch">
                                            <input type="checkbox" name="control_id[]" id="check"  value="<?php echo $value->id; ?>">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 text-center">
                            <?php
                            $submit = ["class" => "editB-profile btn btn-primary addingPlan", "name" => "Save", "value" => "Submit"];
                            echo form_submit($submit);
                            ?>
                        </div><div class="clearfix"></div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js" ></script>
<script>
    $("document").ready(function () {
        $("#subadmin").validate({
            errorPlacement: function (error, element) {
                error.appendTo(element.closest('.form-group').after());
                //element.parent('.input-group').after(error);
            },
            rules: {
                full_name: {
                    required: true,
                    minlength: 5
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true
                },
                'control_id[]': {
                    required: true,
                    maxlength: 2
                }
            },
            messages: {
                'control_id[]': {
                    required: "You must assign at least 1 Role",
                    maxlength: "Check no more than {0} boxes"
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
            //}
        });
    });
</script>