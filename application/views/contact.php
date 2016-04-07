<div class="col-md-4 col-md-offset-4">
    <div class="danero-box">
        <form id="contactForm" action="<?php echo base_url() . "main/contact_send_email";  ?>" method="post" onsubmit="return validateForm()">
            <h2 class="text-center">Contact Us</h2>
            <hr />
            <?php if($this->session->flashdata('error')) { ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php } ?>
            <div class="notice"></div>
            <div class="form-group">
                <label for="name">* Name</label>
                <input type="text" class="form-control required" name="name" id="name" />
            </div>
            <div class="form-group">
                <label for="email">* Email</label>
                <input type="email" class="form-control email required " name="email" id="email" />
            </div>
            <div class="form-group">
                <label for="message">* Message</label>
                <textarea class="form-control required" name="message" id="message" rows="3"></textarea>
            </div>
            <button class="btn btn-success btn-xs">Send</button>
        </form>
    </div>
</div>

<script>
    function validateForm() {
        $result = validator.validateForm($('#contactForm'));
        return $result;
    }
</script>