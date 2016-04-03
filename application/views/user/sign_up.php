<div class="col-md-4 col-md-offset-2">
    <div id="sign_up_form" class="danero-box">
        <h2 class="text-center">Sign Up</h2>
        <hr />
        <div class="notice"></div>
        <div class="form-group">
            <label for="email">* Email</label>
            <input type="email" class="form-control required email" id="email" placeholder="Email" />
        </div>
        <div class="form-group">
            <label for="name">* Full Name</label>
            <input type="email" class="form-control required" id="name" placeholder="Full Name" />
        </div>
        <div class="form-group">
            <label for="password">* Password</label>
            <input type="password" class="form-control required" id="password" placeholder="Password" />
        </div>
        <div class="form-group">
            <label for="confirm_password">* Confirm Password</label>
            <input type="password" class="form-control required" id="confirm_password" placeholder="Confirm Password" />
        </div>
        <button id="sign_up_btn" class="btn btn-default btn-sm pull-right">Sign Up</button>
        <br />
    </div>
</div>
<div class="col-md-4">
    <div class="danero-box text-center">
        <i class="fa fa-twitter fa-4x"></i>
        <br />
        <h3>Sign in using your Twitter Account</h3>
        <br />
        <a href="<?php echo $twitter_auth_url; ?>" class="btn btn-twitter">
            <i class="fa fa-twitter"></i> Sign in with twitter
        </a>

    </div>
</div>

<script>
    $(function() {
        var sign_up_form = $("#sign_up_form");
        $("#sign_up_btn").on("click", function() {
            if(validator.validateForm(sign_up_form)) {
                if($("#password").val().length < 6) {
                    validator.displayAlertError(sign_up_form, true, 'Password should be at least 6 characters.');
                    validator.displayInputError($('#password'), true);
                    return false;
                }
                if($("#password").val() != $("#confirm_password").val()) {
                    validator.displayAlertError(sign_up_form, true, 'Passwords did not match.');
                    validator.displayInputError($('#password'), true);
                    validator.displayInputError($('#confirm_password'), true);
                    return false;
                }

                /* Ajax Request or Submit Form */
            }
        })

    });
</script>