<h2 style="font-weight: bold;">Email</h2>

<div class="row">
    <div class="col-md-6">
        <div class="danero-box">
            <h2>Email Notification</h2>
            <div id="notification-form" style="margin-top: 10px;">
                <div class="notice"></div>
                <div class="form-group">
                    <label for="notification-from">* From</label>
                    <input id="notification-from" type="text" class="form-control email required"
                           value="<?php echo $email_notification['from']; ?>" />
                </div>
                <div class="form-group">
                    <label for="notification-name">* Name</label>
                    <input id="notification-name" type="text" class="form-control required"
                           value="<?php echo $email_notification['name']; ?>" />
                </div>
                <div class="form-group">
                    <label for="notification-cc">CC <i>(Add comma for multiple cc)</i></label>
                    <input id="notification-cc" type="text" class="form-control email required"
                           value="<?php echo $email_notification['cc']; ?>" />
                </div>
                <div class="form-group">
                    <label for="notification-body">* Body</label>
                    <textarea id="notification-body" class="form-control required" rows="8"><?php echo $email_notification['body'] ?></textarea>
                </div>
            </div>
            <button class="btn btn-xs btn-success" id="save-email-notification-btn">Save Email Notification</button>
        </div>
    </div>
    <div class="col-md-5">
        <div class="danero-box">
            <h2>Email Contact</h2>
            <div id="contact-form" style="margin-top: 10px;">
                <div class="notice"></div>
                <div class="form-group">
                    <label for="contact-to">* To</label>
                    <input id="contact-to" type="text" class="form-control email required"
                           value="<?php echo $email_contact['to']; ?>" />
                </div>
            </div>
            <button class="btn btn-xs btn-success" id="save-email-contact-btn">Save Email Contact</button>
        </div>
    </div>

    <div class="col-md-5">
        <div class="danero-box">
            <h2>PayPal</h2>
            <div id="paypal-form" style="margin-top: 10px;">
                <div class="notice"></div>
                <div class="form-group">
                    <label for="paypal-email">* Email (Will receive payments)</label>
                    <input id="paypal-email" type="text" class="form-control email required"
                           value="<?php echo $paypal['email']; ?>" />
                </div>
            </div>
            <button class="btn btn-xs btn-success" id="save-paypal-email-btn">Save PayPal Email</button>
        </div>
    </div>
</div>

<script>
    var actionUrl = "<?php echo base_url() . 'admin/action'; ?>";

    $(function() {
        $('#admin-nav-email').addClass('active');

        $('#save-email-notification-btn').on('click', function() {
            if(validator.validateForm($('#notification-form'))) {
                var data = {
                    action : 'settings_update',
                    category : 'email_notification',
                    settings : {
                        from : $('#notification-from').val(),
                        name : $('#notification-name').val(),
                        cc : $('#notification-cc').val(),
                        body : $('#notification-body').val()
                    }
                };

                $.post(actionUrl, data, function(res) {
                    if(res.success == true) {
                        toastr.success("Saving email notification successful!");
                    } else {
                        toastr.error("Something went wrong!");
                    }
                }, 'json');
            }
        });

        $('#save-email-contact-btn').on('click', function() {
            if(validator.validateForm($('#contact-form'))) {
                var data = {
                    action : 'settings_update',
                    category : 'email_contact',
                    settings : {
                        to : $('#contact-to').val()
                    }
                };

                $.post(actionUrl, data, function(res) {
                    if(res.success == true) {
                        toastr.success("Saving email contact successful!");
                    } else {
                        toastr.error("Something went wrong!");
                    }
                }, 'json');
            }
        });

        $('#save-paypal-email-btn').on('click', function() {
            if(validator.validateForm($('#paypal-form'))) {
                var data = {
                    action : 'settings_update',
                    category : 'paypal',
                    settings : {
                        email : $('#paypal-email').val()
                    }
                };

                $.post(actionUrl, data, function(res) {
                    if(res.success == true) {
                        toastr.success("Saving paypal successful!");
                    } else {
                        toastr.error("Something went wrong!");
                    }
                }, 'json');
            }
        });
    });
</script>