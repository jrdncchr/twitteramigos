<div class="col-md-4 col-md-offset-4">
    <div class="danero-box">
        <h2 class="text-center">Administration</h2>
        <hr />
        <?php if(isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); } ?>

        <form action="<?php echo base_url() . 'main/admin_login'; ?>" method="post">
            <div class="form-group">
                <label for="username">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Username" />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
            </div>
            <button type="submit" class="btn btn-default btn-sm pull-right">Login</button>
        </form>
        <br />
    </div>
</div>