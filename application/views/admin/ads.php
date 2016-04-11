<style>
    .dataTable th, .dataTable tr {
        text-align: center !important;
        font-size: 14px;
    }
    .dataTables_length {
        height: 40px;
    }
</style>

<h2 style="font-weight: bold;">Advertisements</h2>
<div class="row">
    <div class="col-md-12">
        <div class="danero-box" style="margin-right: 20px;">
            <div class="form-group">
                <label for="top-1">Top</label>
                <textarea id="top-1" class="form-control required" rows="6"><?php echo $ads['top-1'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="right-1">Right 1</label>
                <textarea id="right-1" class="form-control required" rows="6"><?php echo $ads['right-1'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="right-2">Right 2</label>
                <textarea id="right-2" class="form-control required" rows="6"><?php echo $ads['right-2'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="bottom-1">Bottom</label>
                <textarea id="bottom-1" class="form-control required" rows="6"><?php echo $ads['bottom-1'] ?></textarea>
            </div>
            <button class="btn btn-sm btn-success" id="save-ads-btn">Save Ads</button>
        </div>
    </div>
</div>

<script>
    var actionUrl = "<?php echo base_url() . 'admin/action'; ?>";
    var adsDt;

    $(function() {
        $('#admin-nav-ads').addClass('active');

        $('#save-ads-btn').on('click', function() {
            var data = {
                action : 'ads_update',
                category : 'ads',
                settings : {
                    'top-1' : $('#top-1').val(),
                    'right-1' : $('#right-1').val(),
                    'right-2' : $('#right-2').val(),
                    'bottom-1' : $('#bottom-1').val()
                }
            };

            $.post(actionUrl, data, function(res) {
                if(res.success == true) {
                    toastr.success('Saving advertisements successful!');
                }
            }, 'json');
        });
    });
</script>