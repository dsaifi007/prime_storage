
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
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url(); ?>subadmin-list">Manage Sub Admins</a></li>
                <li><a href="#"> Sub Admin Detail</a></li>
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


                        <div class="panel-content">
                            <div class="col-md-8 col-lg-8 col-lg-offset-2 user-details">
                                <div class="col-md-0">
                                    <!--  <div class="profile-photo">
                                        <img alt="Jane Doe" src="<?php //echo base_url();    ?>images/user-avatar.jpg">
                                    </div>-->
                                </div>
                                <div class="col-md-6">

                                    <h4 class=""><b><?php echo @$access_control[0]['name']; ?></b></h4>
                                    <p><?php echo @$access_control[0]['contact']; ?></p>
                                    <p><?php echo @$access_control[0]['email']; ?></p>
                                </div>
                                <div class="col-md-6"><br>
                                    <div class="btn-group btn-group-xs new_btnss">                                                        
                                        <label class="switch">
                                            <input type="checkbox" name="control_id" module-id='0' user-id='<?php echo @$access_control[0]['user_id']; ?>' class="check" <?php echo ($access_control[0]['is_blocked']==1)?"checked":'';?>>
                                            <span class="slider round"></span>

                                        </label>
                                        <h6>Unblock / Block</h6>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div><div class="clearfix"></div>
                            <div class="col-md-12 manage_subadmins">

                               <h4>Assign Roles<font style="margin-left: 733px;">Block/Unblock</font></h4>
                                <?php
                                if ($module_name) {
                                    //dd($access_control);
                                    foreach ($module_name as $k => $value) {
                                        $is_blocked = (($access_control[$k]['access_controller'] == $value->id) && ($access_control[$k]['status'] == 0)) ? "checked" : '';
                                        ?>

                                        <!-- roles start -->
                                        <div class="col-md-6 text-left">
                                            <h4><?php echo $value->module_name; ?> </h4>
                                        </div>
                                        <div class="col-md-6 rightPermissions text-right">
                                            <label class="switch">
                                                <input type="checkbox" name="control_id" class="check" module-id='<?php echo $value->id ?>' user-id='<?php echo $access_control[$k]['user_id']; ?>'   <?php echo $is_blocked; ?>>
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
            $("input[name='control_id']").click(function () {
                var status = 0;
                //alert("dd");
                //var n = $("input[name='check']:checked").length;
                var n = $(this).is(":checked");
                var user_id = $(this).attr("user-id");
                var module_id = $(this).attr("module-id");
               
                if (n) {
                    status = 0;
                } else {
                    status = 1;
                }
                 //alert(status);
                update_user_status(user_id, status,module_id);
                //alert(module_id);
            });
            function update_user_status(user_id, status,module_id) {
                //alert(status);
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/subadmin/subadmin_controller/update_edit_info",
                    cache: false,
                    type: "POST",
                    processData: true,
                    data: {user_id: user_id, status: status,access_controller:module_id},
                    success: function (data) {
                        //console.log(data);
                        var response = JSON.parse(data);
                        alert(response.message);
                    }
                });
            }
        });
    </script>