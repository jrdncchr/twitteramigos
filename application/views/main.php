<style>
    .screen-name {
        text-align: right;
    }
    .follow {
        text-align: left;
        padding-left: 30px !important;
    }
</style>
<div class="col-md-5">
    <div class="danero-box">
        <?php if(isset($_SESSION['notice'])) { ?>
            <div class="alert alert-success"><i class='fa fa-check'></i> <?php echo $_SESSION['notice']; ?></div>
        <?php unset($_SESSION['notice']);
        } ?>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#users" aria-controls="home" role="tab" data-toggle="tab">Users</a></li>
            <?php if(null != $user) { ?>
            <li role="presentation"><a href="#following" aria-controls="profile" role="tab" data-toggle="tab">Following</a></li>
            <li role="presentation"><a href="#followedBack" aria-controls="messages" role="tab" data-toggle="tab">Followed Back</a></li>
            <?php } ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" style="margin-top: 20px;">
            <div role="tabpanel" class="tab-pane active" id="users">
                <div class="alert alert-info"><i class="fa fa-thumbs-up"></i> Profiles that you'll follow will receive an email notification, containing a follow back link to your profile.</div>
                <table id="mainDt" class="table table-hover text-center" width="100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <?php if(null != $user) { ?>
            <div role="tabpanel" class="tab-pane" id="following">
                <div class="alert alert-info"><i class="fa fa-thumbs-up"></i> List of users you've followed and the time interval you followed them up to date.</div>
                <table id="followingDt" class="table table-hover text-center" width="100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="followedBack">
                <div class="alert alert-info"><i class="fa fa-thumbs-up"></i> List of users followed you back and the time interval they followed you up to date.</div>
                <table id="followedBackDt" class="table table-hover text-center" width="100%">
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>

    </div>
</div>
<div class="col-md-4">
  <div class="danero-box" style="padding-top: 40px;">
      <h2>Premium <a href="<?php echo base_url() . 'main/subscribe_premium'; ?>" class="btn btn-xs btn-primary pull-right"><i class="fa fa-rocket"></i> Subscribe as Premium</a></h2>
      <hr />
      <div class="alert alert-info"><i class="fa fa-thumbs-up"></i> Premium profiles are listed here, they will follow you back for sure, so make sure to follow them now.</div>
      <table id="premiumDt" class="table table-hover text-center" width="100%">
          <thead>
          <tr>
              <th></th>
              <th></th>
          </tr>
          </thead>
          <tbody>
          </tbody>
      </table>
  </div>
</div>
<div class="col-md-3">
    <?php if(null == $user) { ?>
        <div class="danero-box text-center">
                <i class="fa fa-twitter fa-4x"></i>
                <br />
                <h3>Sign in and join us now using your Twitter Account</h3>
                <br />
                <a href="<?php echo $twitter_auth_url; ?>" class="btn btn-twitter">
                    <i class="fa fa-twitter"></i> Sign in with twitter
                </a>
        </div>
    <?php } else { ?>
        <div class="danero-box">
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-xs-2 text-center">
                    <img src="<?php echo $user['twitter']->profile_image_url; ?>" class="img-circle" />
                </div>
                <div class="col-xs-10" style="padding-top: 10px; padding-left: 20px;">
                    <a href="<?php echo "https://twitter.com/" . $user['twitter']->screen_name; ?>" target="_blank" style="font-size: 22px;">
                        <?php echo $user['twitter']->screen_name; ?>
                    </a>
                </div>
            </div>
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-xs-12" id="emailDiv">
                    <?php if(isset($user)) { ?>
                        <?php if($user['user']->email != "") { ?>
                            <i class="fa fa-envelope"></i> <?php echo isset($user) ? $user['user']->email : ""; ?>
                        <?php } else { ?>
                            <p class="text-danger" style="font-size: 13px;"><i class="fa fa-exclamation-circle"></i> You have not setup your email yet.</p>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-danger" style="font-size: 13px;"><i class="fa fa-exclamation-circle"></i> You have not setup your email yet.</p>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#settingsModal">
                        Settings
                    </button>
                    <a class="btn btn-danger btn-xs" href="<?php echo base_url() . "main/logout"; ?>">Logout</a>
                </div>
            </div>


        </div>
    <?php } ?>

    <div class="danero-box text-center">
        <?php echo $ads['right-1']; ?>
    </div>

    <div class="danero-box text-center">
        <?php echo $ads['right-2']; ?>
    </div>



<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="topModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="topModalLabel">Settings</h4>
            </div>
            <div class="modal-body">
                <div class="notice"></div>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Email</label>
                        <div class="col-xs-6">
                            <input id="email" type="email" class="form-control input-sm" aria-label="email" value="<?php echo isset($user) ? $user['user']->email : ""; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Notification</label>
                        <div class="col-xs-6">
                            <div class="checkbox">
                                <label style="font-size: 14px;">
                                    <input id="emailNotification" type="checkbox" <?php echo isset($user) ? ($user['user']->email_notification ? "checked" : "") : "" ?> /> Receive email notification when a user follows you.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Privacy</label>
                        <div class="col-xs-6">
                            <div class="checkbox">
                                <label style="font-size: 14px;">
                                    <input id="showProfile" type="checkbox" <?php echo isset($user) ? ($user['user']->show_profile ? "checked" : "") : "" ?> /> Show your profile in the our list of users.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-xs" id="saveProfileBtn">Save</button>
            </div>
        </div>
    </div>
</div>


<!-- Top Modal -->
<div class="modal fade" id="topModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="settingsModalLabel"><i class="fa fa-arrow-up"></i> Top</h4>
            </div>
            <div class="modal-body">
                <p class="text-center">Put your profile on the top section again by donating $1.</p>
                <p></p>
                <p class="text-center">Selected Profile: <span id="selected-profile" style="font-weight: bold;"></span></p>
                <form action="<?php echo $paypal['url']; ?>" method="post" style="margin-top: 10px;">

                    <!-- Identify your business so that you can collect the payments. -->
                    <input type="hidden" name="business" value="<?php echo $paypal['business'] ?>">

                    <!-- Specify a Buy Now button. -->
                    <input type="hidden" name="cmd" value="_xclick">

                    <!-- Specify details about the item that buyers will purchase. -->
                    <input type="hidden" name="item_name" value="Make Profile Top" id="top_item_name">
                    <input type="hidden" name="item_number" id="top_item_number" />
                    <input type="hidden" name="custom" id="CacheroJordan" />
                    <input type="hidden" name="amount" value="1">
                    <input type="hidden" name="currency_code" value="USD">

                    <!-- Specify URLs -->
                    <input type='hidden' name='cancel_return' value='<?php echo base_url() . '/main'; ?>'>
                    <input type='hidden' name='return' value='<?php echo base_url() . '/main/top_success'; ?>'>

                    <!-- Display the payment button. -->
                    <div class="text-center">
                        <input type="image" name="submit" border="0"
                               src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_pponly_142x27.png" alt="PayPal - The safer, easier way to pay online">
                        <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    var baseUrl = "<?php echo base_url(); ?>";
    var userActionUrl = "<?php echo base_url() . 'user/action'; ?>";
    var userId = "<?php echo isset($user) ? $user['user']->id : 0; ?>";
    var newUser = "<?php echo isset($new_user) ? 1 : 0 ?>";

    $(function() {
        activateDataTables();

        $("#saveProfileBtn").on("click", function() {
            var email = $("#email").val();
            var emailNotification = $("#emailNotification").is(":checked") ? 1 : 0;
            var showProfile = $("#showProfile").is(":checked") ? 1 : 0;
            if(emailNotification) {
                if(!validator.validateEmail(email)) {
                    validator.displayAlertError($("#settingsModal"), true, "Invalid email address.");
                    validator.displayInputError($("#email"), true);
                    return false;
                }
            }
            validator.displayAlertError($("#settingsModal"), false);
            validator.displayInputError($("#email"), false);

            toastr.info('Saving profile settings.');
            var data = {
                action : 'update',
                user : {
                    email : email,
                    email_notification : emailNotification,
                    show_profile : showProfile
                }
            };
            $.post(userActionUrl, data, function(data) {
                if(data.success == true) {
                    toastr.success("Updating settings successful!");
                    if(email != "") {
                        $("#emailDiv").html("<i class='fa fa-envelope'></i> " + email);
                    } else {
                        $("#emailDiv").html("<p class='text-danger' style='font-size: 13px;'>You have not setup your email.</p>");
                    }
                    $("#settingsModal").modal("hide");
                }

            }, 'json');
        });
        if(newUser > 0) {
            $("#settingsModal").modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        }
    });

    function activateDataTables() {
        $("#mainDt").dataTable({
            autoWidth: false,
            info: false,
            filter: false,
            sort: false,
            lengthChange: false,
            displayLength: 25,
            destroy: true,
            ajax: {
                "type"  : "POST",
                "url"   : userActionUrl,
                "data"  : { action: "list", type: "main" }
            },
            columns: [
                {data: "name", width: "50%", render: function(data, type, row) {
                        return "<a class='pull-right' href='https://www.twitter.com/" + row.name + "' target='_blank'>" + data + "</a>";
                    }
                },
                {data: "twitter_id", width: "50%", render: function(data, type, row) {
                        var content = "";
                        if (row.premium == '1') {
                            content += '<b><i class="fa fa-star text-primary"></i></b>';
                        }
                        content += "<a href='" + baseUrl + "twitter/follow/" + row.twitter_id + "' class='btn-follow btn btn-twitter btn-xs pull-left'><i class='fa fa-twitter'></i> Follow</a>";
                        content += "<button onclick='topEvent(" + row.twitter_id + ", \"" + row.name + "\")' class='btn btn-default btn-xs pull-left' style='margin-left: 10px;'><i class='fa fa-arrow-up fa-sm'></i> Top</button>";


                        return content;
                    }
                },
                {data: "id", visible: false}
            ]
        });

        $("#premiumDt").dataTable({
            autoWidth: false,
            info: false,
            filter: false,
            sort: false,
            lengthChange: false,
            displayLength: 25,
            destroy: true,
            ajax: {
                "type"  : "POST",
                "url"   : userActionUrl,
                "data"  : { action: "list", type: "premium" }
            },
            columns: [
                {data: "name", width: "50%", render: function(data, type, row) {
                    return "<a class='pull-right' href='https://www.twitter.com/" + row.name + "' target='_blank'>" + data + "</a>";
                }
                },
                {data: "twitter_id", width: "50%", render: function(data, type, row) {
                    return "<a href='" + baseUrl + "twitter/follow/" + row.twitter_id + "' class='btn-follow btn btn-twitter btn-xs pull-left'><i class='fa fa-twitter'></i> Follow</a>";

                }
                },
                {data: "id", visible: false}
            ]
        });

        if(userId > 0) {
            $("#followingDt").dataTable({
                autoWidth: false,
                info: false,
                filter: false,
                sort: false,
                lengthChange: false,
                displayLength: 25,
                destroy: true,
                ajax: {
                    "type"  : "POST",
                    "url"   : userActionUrl,
                    "data"  : { action: "list", type: "following" }
                },
                columns: [
                    {data: "name", width: "50%", render: function(data, type, row) {
                        return "<a href='https://www.twitter.com/" + row.name + "' target='_blank'>" + data + "</a>";
                    }
                    },
                    {data: "time", width: "50%", render: function(data, type, row) {
                        return "<span style='font-size: 14px;' class='pull-left'>" + data + "</span>";
                    }
                    },
                    {data: "id", visible: false}
                ]
            });

            $("#followedBackDt").dataTable({
                autoWidth: false,
                info: false,
                filter: false,
                sort: false,
                lengthChange: false,
                displayLength: 25,
                destroy: true,
                ajax: {
                    "type"  : "POST",
                    "url"   : userActionUrl,
                    "data"  : { action: "list", type: "followed_back" }
                },
                columns: [
                    {data: "name", width: "50%", render: function(data, type, row) {
                        return "<a href='https://www.twitter.com/" + row.name + "' target='_blank'>" + data + "</a>";
                    }
                    },
                    {data: "time", width: "50%", render: function(data, type, row) {
                        return "<span style='font-size: 14px;' class='pull-left'>" + data + "</span>";
                    }
                    },
                    {data: "id", visible: false}
                ]
            });
        }

    }

    function topEvent(id, name) {
        $('#selected-profile').html(name);
        $('#top_item_number').val(id);
        $('#top_item_name').val("Make Profile Top [ " + name + " ]");
        $('#topModal').modal("show");
    }
</script>