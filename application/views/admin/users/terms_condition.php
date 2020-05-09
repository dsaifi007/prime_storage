<?php ?>
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Manage Content</a></li>
                <li><a href="#">Terms & Conditions</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="manage-schools manage-user">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-content">
                        <h4 class="content_title">Terms & Conditions <a class="edit_content" href="#" ><i class="fa fa-edit"></i></a></h4>  
                        <?php echo display_message_info([1 => $success, 2 => $error, 3 => validation_errors()]); ?>
                        <?php echo form_open("term-condition-list", ["id" => "terms_text"]); ?>
                        <textarea name="terms_text"  style="width:100%" id="about_text" disabled="disabled"><?php echo $items->text; ?></textarea>
                        <input type="submit" name="save" id="save" class="btn btn-primary" style="display:none" value="Submit"> 
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js" ></script>
    <script>
        $("document").ready(function () {
            $("#terms_text").validate({

                rules: {
                    terms_text: {
                        required: true
                    },
                    submitHandler: function (form) {
                        form.submit();
                    }
                }
            });
        });
    </script>
    <script>
        $("body").on("click", ".edit_content", function (e) {
            e.preventDefault();
            $("#about_text").removeAttr("disabled");
            $("#save").removeAttr("style");
        });
    </script>