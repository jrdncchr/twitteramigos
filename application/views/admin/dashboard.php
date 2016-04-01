<h2 style="font-weight: bold;">Dashboard</h2>
<div class="row">
    <div class="col-md-6">
        <div class="danero-box" style="margin-right: 20px;">
            Welcome back, <a href="<?php echo base_url() . "admin/settings"; ?>"><?php echo $admin->name; ?></a>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#admin-nav-dashboard').addClass('active');
    });
</script>