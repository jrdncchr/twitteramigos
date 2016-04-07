<div class="col-md-4 col-md-offset-4">
    <div class="danero-box">
        <h2 class="text-center"><b><i class="fa fa-rocket"></i> Subscribe as Premium</b></h2>
        <hr />
        <?php if($this->session->flashdata('notice')) { ?>
            <div class="alert alert-success">
                <i class="fa fa-check"></i> <?php echo $this->session->flashdata('notice'); ?>
            </div>
        <?php } ?>

        <p>Hi <b><?php echo $user['user']->name; ?>!</b></p>
        <p>Be a premium for a month for only $5, by subscribing you'll have benefits like: </p>
        <br/>
        <p class="text-primary"><i class="fa fa-arrow-right"></i> Profile is listed in the premium list.</p>
        <p class="text-primary"><i class="fa fa-arrow-right"></i> You can filter the users list.</p>
        <p class="text-primary"><i class="fa fa-arrow-right"></i> Be on the top of the users list again.</p>
        <p class="text-primary"><i class="fa fa-arrow-right"></i> Marked as a premium profile, telling other users that you'll follow them back.</p>
        <br />
        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">

            <!-- Identify your business so that you can collect the payments. -->
            <input type="hidden" name="business" value="jrdn-sb-business@gmail.com">

            <!-- Specify a Buy Now button. -->
            <input type="hidden" name="cmd" value="_xclick">

            <!-- Specify details about the item that buyers will purchase. -->
            <input type="hidden" name="item_name" value="Subscribe as Premium [ <?php echo $user['user']->name; ?> ]">
            <input type="hidden" name="item_number" id="<?php echo $user['user']->twitter_id; ?>" />
            <input type="hidden" name="amount" value="5">
            <input type="hidden" name="currency_code" value="USD">

            <!-- Specify URLs -->
            <input type='hidden' name='cancel_return' value='http://127.0.0.1/twitteramigos/main'>
            <input type='hidden' name='return' value='http://127.0.0.1/twitteramigos/main/premium_success'>

            <!-- Display the payment button. -->
            <div class="text-center">
                <input type="image" name="submit" border="0"
                       src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_pponly_142x27.png" alt="PayPal - The safer, easier way to pay online">
                <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
            </div>
        </form>
    </div>
</div>