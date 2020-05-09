<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Manage Analytics</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">

        <div class="col-sm-12">
            <div class="panel">
                <div class="panel-content">
                    <div class="table-responsive">
                        <span style="padding-left: 349px;"> Total number of downloaded - <b><?php echo count($items); ?></b></span>
                        <table class="table table-striped table-hover table-bordered text-center data-table">
                            
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Successful Attempt</th>
                                    <th>Unsuccessful Attempt</th>
                                    <th>IP-Information</th>
                                    <th>Location</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($items) && !empty($items)) {
                                    foreach ($items as $value) {
                                        ?>
                                        <tr>
                                            
                                            <td><?php echo ($value->full_name)?$value->full_name:"N/A"; ?></td>
                                            <td><?php echo ($value->email)?$value->email:"N/A"; ?></td>
                                            <td><?php echo ($value->success)?$value->success:"N/A"; ?></td>
                                            <td><?php echo ($value->failed)?$value->failed:"N/A"; ?></td>
                                            <td><?php echo ($value->ip_address)?$value->ip_address:"N/A"; ?></td>
                                            <td><?php echo ($value->location)?$value->location:"N/A"; ?></td>                                      
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

    <script src="<?php echo base_url() ?>assets/admin/vendor/data-table/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url() ?>assets/admin/vendor/data-table/media/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function () {
            "use strict";
            $('.data-table').DataTable({
                "bSort": false
            });
            //$("input[name='search']").hide();
        });
        
        //$("div#DataTables_Table_0_filter").addClass("pull-right");
    </script>