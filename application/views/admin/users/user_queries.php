
<style>
    .dataTables_filter{
        float:right
    }
</style>
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Manage Queries</a></li>
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
                                    <div id="responsive-table_filter" class="dataTables_filter pull-left">

                                    </div>
                                </div>

                                <div class="clearfix"></div>
                            </div> 
                            <table class="table table-striped table-hover table-bordered text-center data-table">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Name of User</th>
                                        <th>Email Address</th>
                                        <th>Contact Number</th>
                                        <th>Query</th>
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
                                                <td><?php echo $value->phone; ?></td>
                                                <td><?php echo $value->query; ?></td>
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
    <script type="text/javascript">
        $(function () {
            "use strict";
            $('.data-table').DataTable({
                //"bSort": false
            });
        });
        $("div#DataTables_Table_0_filter").addClass("pull-right");

    </script>