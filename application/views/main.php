<div class="col-md-8">
    <div class="danero-box text-center">
        <table class="table table-hover">
            <thead>
            <tr>
                <th class="text-center">Twitter</th>
                <th class="text-center">Follow</th>
                <th class="text-center">Status</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            <tr>
                <td>jrdncchr</td>
                <td><button class="btn btn-twitter btn-sm">Follow</button></td>
                <td>5 Days</td>
            </tr>
            </tbody>
        </table>
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
    <div class="danero-box text-center">
        Advertisement Area
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