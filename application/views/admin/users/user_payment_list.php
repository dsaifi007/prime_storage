
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

<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="<?php echo base_url();?>user-detail">Manage User</a></li>
                <li><a href="#">Payment Detail</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="manage-schools manage-user">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-content">
                        <div class="col-md-8 col-lg-8 col-lg-offset-2 user-details">
                            <div class="col-md-2">
                                <div class="profile-photo">
                                    <?php
                                    if ($items[0]['user_image_url']) {
                                        echo "<img  src='" . $items[0]['user_image_url'] . "'>";
                                    } else {
                                        echo "<img  src=".$this->config->item("base_url")."/assets/admin/img/user-avatar.png>";
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class=""><b><?php echo $items[0]['full_name']; ?></b></h4>
                                <p><?php echo $items[0]['phone']; ?></p>
                                <p><?php echo $items[0]['email']; ?></p>
                            </div>
                            <div class="col-md-4">
                                <div class="btn-group btn-group-xs new_btnss">                                                        
                                    <label class="switch">
                                        <input type="checkbox" name="check" user-id="<?php echo $items[0]['user_id'] ?>" <?php echo ($items[0]['is_blocked'] == "1") ? "checked" : ""; ?>>
                                        <span class="slider round"></span>
                                    </label>
                                    <h6>Unlock / Block</h6>

                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div><div class="clearfix"></div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Booking Start Date</th>
                                        <th>Size Selected</th>
                                        <th>Amount Paid</th>
                                        <th>Storage Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                    if (count($items) > 0 && !empty($items)) {

                                        foreach ($items as $value) {
                                            ?>
                                            <tr>
                                                <td><?php echo $value['id'] ?></td>
                                                <td><?php echo $value['start_date'] ?></td>
                                                <td><?php echo $value['size'] ?></td>
                                                <td><?php echo $value['price'] ?>$</td>
                                                <td><?php echo $value['address'] ?></td>
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
</div>
<a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>

<script>
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
            url: "<?php echo base_url(); ?>admin/users/user_controller/update_user_status",
            cache: false,
            type: "POST",
            processData: true,
            data: {user_id: user_id, status: status},
            success: function (data) {
                var response = JSON.parse(data);
                alert(response.message);
            }
        });
    }
</script>