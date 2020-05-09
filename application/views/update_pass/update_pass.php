<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Update-password</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style>
            .form-horizontal{border:solid 1px #ccc;padding:50px}
        </style>
    </head>
    <body>

        <div class="container">

            <?php echo form_open("api/common/pass_update", ["class" => "form-horizontal col-md-6 col-md-offset-3"]); ?>
            
                <?php 
                echo validation_errors("<div class='alert alert-danger'>","</div>"); ?>
            <div class="form-group">
                <center><h3>Change Password</h3> </center>
            </div>
            <?php echo form_hidden('reset_code', @$reset_key); ?>
            <div class="form-group">
                <label class="control-label col-sm-4" for="email">Password:</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="pwd">Confirm Password:</label>
                <div class="col-sm-8">          
                    <input type="password" class="form-control" id="pwd" placeholder="Enter passconf" name="passconf">
                </div>
            </div>

            <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="save" class="btn btn-default">Submit</button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>







