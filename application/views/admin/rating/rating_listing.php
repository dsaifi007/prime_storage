<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Manage Rating & Review</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">

        <div class="col-sm-12">
            <div class="panel">
                <div class="panel-content">
                    <div class="table-responsive">
                        <?php echo display_message_info([1 => $success, 2 => @$error, 3 => validation_errors()]); ?>

                        <table class="table table-striped table-hover table-bordered text-center data-table">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>UserName</th>
                                    <th>Location</th>
                                    <th>Storage Type</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($items) && !empty($items)) {
                                    foreach ($items as $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value->id; ?></td>
                                            <td><?php echo $value->full_name; ?></td>
                                            <td><?php echo $value->address; ?></td>
                                            <td><?php echo $value->size; ?></td>
                                            <td><?php echo $value->rating; ?></td>
                                            <td><?php echo $value->review; ?></td>                                               
                                            <td><a href="<?php echo base_url(); ?>rating-delete/<?php echo $value->rating_id; ?>" onclick="return confirm('Are you sure you want to delete this record')"><i class="fa fa-trash" aria-hidden="true"></i></a></td>                                               
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
                //"bSort": false
            });
            //$("input[name='search']").hide();
        });

        //$("div#DataTables_Table_0_filter").addClass("pull-right");
    </script>