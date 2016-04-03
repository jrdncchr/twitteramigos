<h2 style="font-weight: bold;">Settings</h2>

<div class="row">
    <div class="col-md-5">
        <div class="danero-box">
            <h2>Account</h2>
            <div id="account-form" style="margin-top: 10px;">
                <div class="notice"></div>
                <div class="form-group">
                    <label for="admin-name">* Name</label>
                    <input id="admin-name" type="text" class="form-control required"
                           value="<?php echo $admin->name; ?>" />
                </div>
                <div class="form-group">
                    <label for="admin-email">* Email</label>
                    <input id="admin-email" type="email" class="form-control required email"
                           value="<?php echo $admin->email; ?>"/>
                </div>
            </div>
            <button class="btn btn-xs btn-success" id="save-account-btn">Save Account</button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="danero-box">
            <h2>Change Password</h2>
            <div id="password-form" style="margin-top: 10px;">
                <div class="notice"></div>
                <div class="form-group">
                    <label for="password">* Password</label>
                    <input id="password" type="password" class="form-control required" />
                </div>
                <div class="form-group">
                    <label for="new-password">* New Password</label>
                    <input id="new-password" type="password" class="form-control required" />
                </div>
                <div class="form-group">
                    <label for="confirm-password">* Confirm Password</label>
                    <input id="confirm-password" type="password" class="form-control required" />
                </div>
                <button class="btn btn-xs btn-success" id="change-password-btn">Change Password</button>
            </div>
        </div>
    </div>
</div>

<script>
    var actionUrl = "<?php echo base_url() . 'admin/action'; ?>";

    $(function() {
        $('#admin-nav-settings').addClass('active');

        $("#save-account-btn").on("click", function() {
            if(validator.validateForm($('#account-form'))) {
                var data = {
                    action  : 'admin_update',
                    admin   : {
                        name  : $("#admin-name").val(),
                        email : $("#admin-email").val()
                    }
                };
                toastr.info("Saving account settings, please wait...");
                $.post(actionUrl, data, function(data) {
                    if(data.success == true) {
                        toastr.success("Saving account settings successful!");
                    }
                }, 'json');
            }
        });

        $("#change-password-btn").on("click", function() {
            if(validator.validateForm($('#password-form'))) {
                if($('#new-password').val().length < 5) {
                    validator.displayAlertError($('#password-form'), true, 'New Password must be at least 5 characters.');
                    validator.displayInputError($('#new-password'), true);
                    return false;
                }
                if($('#new-password').val() != $('#confirm-password').val()) {
                    validator.displayAlertError($('#password-form'), true, 'Passwords did not match.');
                    validator.displayInputError($('#new-password'), true);
                    validator.displayInputError($('#confirm-password'), true);
                    return false;
                }
                validator.displayAlertError($('#password-form'), false);
                var data = {
                    action  : 'admin_update',
                    admin   : {
                        password            : $("#password").val(),
                        new_password        : $("#new-password").val(),
                        confirm_password    : $("#confirm-password").val()
                    }
                };
                toastr.info("Updating password, please wait...");
                $.post(actionUrl, data, function(data) {
                    if(data.success == true) {
                        toastr.success("Updating password successful!");
                        validator.displayAlertError($('#password-form'), false);
                        validator.clearForm($('#password-form'));
                    } else {
                        validator.displayAlertError($('#password-form'), true, data.message);
                        validator.displayInputError($('#password'), true);
                    }
                }, 'json');
            }
        });
    });
</script>
