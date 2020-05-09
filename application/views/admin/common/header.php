<!doctype html>
<html lang="en" class="fixed">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>Home | Prime Storage</title> 
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/admin/img/favicon-32x32.png">

        <link href="<?php echo base_url() ?>assets/admin/vendor/pace/pace-theme-minimal.css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/vendor/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/vendor/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/vendor/animate.css/animate.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/vendor/data-table/media/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/vendor/stylesheets/css/custom-bootstrap-margin-padding.css">     
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/vendor/stylesheets/css/style.css">
        <script src="<?php echo base_url() ?>assets/admin/vendor/jquery/jquery-1.12.3.min.js"></script>
        <script src="<?php echo base_url() ?>assets/admin/vendor/bootstrap/js/bootstrap.min.js"></script>
        <style>
            label.error{
                font-size: 12px;
                font-style: italic;
                font-weight: 400;
            }
        </style>
    </head>

    <body>
        <div class="wrap">
            <div class="page-header">
                <div class="leftside-header">
                    <div class="logo">
                        <a href="<?php echo base_url(); ?>" class="on-click">
                            <img alt="logo" src="<?php echo base_url() ?>assets/admin/img/logo1.png" />
                        </a>
                    </div>
                    <div id="menu-toggle" class="visible-xs toggle-left-sidebar" data-toggle-class="left-sidebar-open" data-target="html">
                        <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                    </div>
                </div>
                <div class="rightside-header">
                    <div class="header-middle"></div>
                    <div class="header-section hidden-xs" id="notice-headerbox">
                    </div>
                    <div class="header-section" id="user-headerbox">
                        <div class="user-header-wrap">
                            <div class="user-photo">
<!--                                <img src="<?php //echo base_url() ?>assets/admin/img/user-avatar.jpg" alt="Admin" />-->
                            </div>
                            <div class="user-info">
                                <span class="user-name">Admin</span>
                                <span class="user-profile">Admin</span>
                            </div>
                            <i class="fa fa-plus icon-open" aria-hidden="true"></i>
                            <i class="fa fa-minus icon-close" aria-hidden="true"></i>
                        </div>
                        <div class="user-options dropdown-box">
                            <div class="drop-content basic">
                                <ul>
                                    <li> <a href="<?php echo base_url(); ?>change-password"><i class="fa fa-key" aria-hidden="true"></i> Change Password</a></li>
                                    <li><a href="<?php echo base_url(); ?>admin/login/login/logout"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>