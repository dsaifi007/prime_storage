<?php ?>
<div class="content">
    <div class="content-header">
        <div class="leftside-content-header">
            <ul class="breadcrumbs">
                <li><i class="fa fa-home" aria-hidden="true"></i><a href="#">Manage Content</a></li>
                <li><a href="#">Contact-us</a></li>
            </ul>
        </div>
    </div>
    <div class="row animated fadeInUp">
        <div class="manage-schools manage-user">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-content">
                        <h4 class="content_title">Contact-us <a class="edit_content" href="#" ><i class="fa fa-edit"></i></a></h4>  
                        <?php echo display_message_info([1 => $success, 2 => $error, 3 => validation_errors()]); ?>
                        <?php echo form_open("contact-us", ["id" => "terms_text"]); ?>
                        <div class="form-group">
                            <label for="email">Email address:</label> <input tyep="text" name="email" class="contact form-control" value="<?php echo $items->email; ?>" disabled="disabled" ><br>
                        </div>
                        <div class="form-group">
                            <label for="pwd">Contact Number:</label> <input tyep="text" name="phone" class="contact form-control" value="<?php echo $items->phone; ?>" disabled="disabled">
                        </div>
                        <div class="form-group">
                        <input type="submit" name="save" id="save" class="btn btn-primary" style="display:none" value="Submit"> 
                        </div>
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
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
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
            $(".contact").removeAttr("disabled");
            $("#save").removeAttr("style");
        });
    </script>