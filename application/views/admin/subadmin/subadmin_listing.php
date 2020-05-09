
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

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/vendorddd/stylesheets/css/minitoggle.css">
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="index.html">Manage Sub Admins</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="manage-schools manage-user">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-content">
                        <div class="table-responsive">
                            <div class="dataTables_wrapper">
                                <div class="col-sm-12">
                                    <div id="responsive-table_filter" class="dataTables_filter pull-right">
                                        <a href="<?php echo base_url();?>sub-admin/0" class="btn btn-primary add_newbtn"><i class="fa fa-plus"> </i>  Add New</a>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                            </div> 
                            <table class="table table-striped table-hover table-bordered text-center data-table">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Sub Admin Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Unblock / Block</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                    if (count($items) && isset($items)) {
                                        foreach ($items as $value) {
                                            ?>
                                            <tr>
                                                <td><?php echo $value->id; ?></td>
                                                <td><?php echo $value->name; ?></td>
                                                <td><?php echo $value->email; ?></td>
                                                <td><?php echo $value->contact; ?></td>
                                                <?php $is_blocked = ((int) $value->is_blocked == 1) ? "checked" : ""; ?>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" name="check" class="check"   user-id = "<?php echo $value->id; ?>" <?php echo $is_blocked; ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <td><a href="<?php echo base_url(); ?>sub-admin/<?php echo $value->id;?>" class="green-btn"><i class="fa fa-eye"></i></a></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url() ?>assets/admin/vendor/data-table/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url() ?>assets/admin/vendor/data-table/media/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php //echo base_url()   ?>assets/admin/vendor/javascripts/minitoggle.js"></script>
    <script type="text/javascript">
        $(function () {
            "use strict";
            $('.data-table').DataTable({
                "bSort": false
            });
        });
        $("div#DataTables_Table_0_filter").addClass("pull-right");

        $("input[name='check']").click(function () {
            var status = 0;
            var n = $(this).prop('checked');
            var user_id = $(this).attr("user-id");
            if (n) {
                status = 1;
            } else {
                status = 0;
            }
            update_user_status(user_id, status);
        });
        function update_user_status(user_id, status) {
            $.ajax({
                url: "<?php echo base_url(); ?>admin/subadmin/subadmin_controller/update_user_status",
                cache: false,
                type: "POST",
                processData: true,
                data: {user_id: user_id, status: status},
                success: function (data) {
                    //console.log(data);
                    var response = JSON.parse(data);
                    alert(response.message);
                }
            });
        }
    </script>