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
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Manage Offered Features</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="manage-schools manage-user">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-content">
                        <div class="col-md-12 manage_subadmins">
                            <h4 class="feature_heading">List of Features</h4>
                         

                            <?php
                           
                            if (count($items)) {
                                foreach ($items as $value) {
                                    ?>
                                    <!-- roles start -->
                                    <div class="col-md-6 text-left">
                                        <h4><?php echo $value['name']; ?></h4>
                                    </div>
                                    <div class="col-md-6 rightPermissions text-right">
                                        <div class="btn-group btn-group-xs new_btnss">                                                        
                                            <fieldset class="demo">
                                                <div class="btn-group btn-group-xs new_btnss">                                                        
                                                    <label class="switch">
                                                        <input type="checkbox" name="offer_id" class="check" offer-id='<?php echo $value['id'];?>' <?php echo ($value['status'] == 1)?"checked":"";?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php
                                }
                            }
                            ?>
                        </div><div class="clearfix"></div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="#" class="scroll-to-top"><i class="fa fa-angle-double-up"></i></a>
<script>
    $("document").ready(function () {
        $("input[name='offer_id']").click(function () {
            var status = 0;
            var n = $(this).is(":checked");
            var offer_id = $(this).attr("offer-id");       
            if (n) {
                status = 1;
            } else {
                status = 0;
            }
            update_user_status(offer_id, status);
        });
        function update_user_status(offer_id, status) {         
            $.ajax({
                url: "<?php echo base_url(); ?>admin/offer_featured/offer_controller/update_feature_status",
                cache: false,
                type: "POST",
                processData: true,
                data: {offer_id: offer_id, status: status},
                success: function (data) {
                    var response = JSON.parse(data);
                    alert(response.message);
                }
            });
        }
    });
</script>