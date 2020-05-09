<?php
$data = display_left_navigation();
$uri = $this->uri->segment(1);
?>

<div class="page-body">
    <div class="left-sidebar">
        <!-- left sidebar HEADER -->
        <div class="left-sidebar-header">
            <div class="left-sidebar-title"></div>
            <div class="left-sidebar-toggle c-hamburger c-hamburger--htla hidden-xs" data-toggle-class="left-sidebar-collapsed" data-target="html">
                <span></span>
            </div>
        </div>
        <!-- NAVIGATION -->
        <!-- ========================================================= -->
        <div id="left-nav" class="nano">
            <div class="nano-content">
                <nav>
                    <ul class="nav" id="main-nav">
                        <?php
                        if (count($data) > 0) {
                            if ($data[0]['module_name'] == "Manage Users" && $data[0]['status'] == 0) {
                                ?>
                                <li class="<?php //echo (@$uri == 'users')?'active-item':"" ?>"><a href="<?php echo base_url(); ?>users"><i class="fa fa-user-circle" aria-hidden="true"></i><span>Manage User</span></a></li>
                                <?php
                            }
                            if ($data[1]['module_name'] == "Manage Payment" && $data[1]['status'] == 0) {
                                ?>
                                <li><a href="<?php echo base_url(); ?>payment-list"><i class="fa fa-credit-card" aria-hidden="true"></i><span>Manage Payments</span></a></li>
                                <?php
                            }
                            if ($data[2]['module_name'] == "Manage Queries" && $data[2]['status'] == 0) {
                                ?>
                                <li><a href="<?php echo base_url(); ?>queries"><i class="fa fa-question-circle" aria-hidden="true"></i><span>Manage Queries</span></a></li>
                                <?php
                            }
                            if ($data[3]['module_name'] == "Manage Sub Admin" && $data[3]['status'] == 0) {
                                ?>
                                <li><a href="<?php echo base_url(); ?>subadmin-list"><i class="fa fa-user-plus" aria-hidden="true"></i><span>Manage Sub Admin</span></a></li>
                                <?php
                            }
                            if ($data[4]['module_name'] == "Manage Analytics" && $data[4]['status'] == 0) {
                                ?>
                                <li><a href="<?php echo base_url(); ?>analytics"><i class="fa fa-chart-pie" aria-hidden="true"></i><span>Manage Analytics</span></a></li>
                                <?php
                            }
                            if ($data[5]['module_name'] == "Manage Offer Features" && $data[5]['status'] == 0) {
                                ?>
                                <li><a href="<?php echo base_url(); ?>offer-feature-list"><i class="fa fa-tasks" aria-hidden="true"></i><span>Manage Offered Features</span></a></li>
                                <?php
                            }
                            if ($data[6]['module_name'] == "Manage Content" && $data[6]['status'] == 0) {
                                ?>
                                <li class="has-child-item close-item">
                                    <a><i class="fa fa-info-circle" aria-hidden="true"></i> <span>Manage Content</span></a>
                                    <ul class="nav child-nav level-1" style="">
                                        <li><a href="<?php echo base_url(); ?>content/about-us">About Us</a></li>
                                        <li><a href="<?php echo base_url(); ?>terms-condition">Terms & Conditions</a></li>
                                        <li><a href="<?php echo base_url(); ?>contact-us">Contact Us</a></li>
                                    </ul>
                                </li>
                                <?php
                            }
                            if ($data[7]['module_name'] == "Manage Rating" && $data[7]['status'] == 0) {
                                ?>
                                <li><a href="<?php echo base_url(); ?>rating"><i class="fa fa-tasks" aria-hidden="true"></i><span>Manage Rating & Review</span></a></li>
                                <?php
                            }
                        } else {
                            ?>
                            <li class="active-item"><a href="<?php echo base_url(); ?>users"><i class="fa fa-user-circle" aria-hidden="true"></i><span>Manage User</span></a></li>
                            <li><a href="<?php echo base_url(); ?>payment-list"><i class="fa fa-credit-card" aria-hidden="true"></i><span>Manage Payments</span></a></li>
                            <li><a href="<?php echo base_url(); ?>queries"><i class="fa fa-question-circle" aria-hidden="true"></i><span>Manage Queries</span></a></li>
                            <li><a href="<?php echo base_url(); ?>subadmin-list"><i class="fa fa-user-plus" aria-hidden="true"></i><span>Manage Sub Admin</span></a></li>
                            <li><a href="<?php echo base_url(); ?>analytics"><i class="fa fa-chart-pie" aria-hidden="true"></i><span>Manage Analytics</span></a></li>
                            <li><a href="<?php echo base_url(); ?>offer-feature-list"><i class="fa fa-tasks" aria-hidden="true"></i><span>Manage Offered Features</span></a></li>
                            <li><a href="<?php echo base_url(); ?>rating"><i class="fa fa-star" aria-hidden="true"></i><span>Manage Rating & Reviews</span></a></li>
                            <li class="has-child-item  <?php echo (isset($active_class1) || isset($active_class2) || isset($active_class3)) ? "open" : "close"; ?>-item">
                                <a><i class="fa fa-info-circle" aria-hidden="true"></i> <span>Manage Content</span></a>
                                <ul class="nav child-nav level-1" style="">
                                    <li class="<?php echo ((isset($active_class1)) ? $active_class1 : ""); ?>"   ><a href="<?php echo base_url(); ?>content/about-us">About Us</a></li>
                                    <li class="<?php echo ((isset($active_class2)) ? $active_class2 : ""); ?>" ><a href="<?php echo base_url(); ?>content/terms-condition">Terms & Conditions</a></li>
                                    <li class="<?php echo ((isset($active_class3)) ? $active_class3 : ""); ?>" ><a href="<?php echo base_url(); ?>contact-us">Contact Us</a></li>
                                </ul>
                            </li>
                        <?php }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
