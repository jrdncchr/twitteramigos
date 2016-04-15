<style>
    .dataTable th, .dataTable tr {
        text-align: center !important;
        font-size: 14px;
    }
    .dataTables_length {
        height: 40px;
    }
</style>

<h2 style="font-weight: bold;">Users</h2>
<div class="row">
    <div class="col-md-12">
        <div class="danero-box" style="margin-right: 20px;">
            <div role="tabpanel" class="tab-pane active" id="users">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-3 col-md-offset-9">
                        <button class="btn btn-primary btn-sm btn-block" id="show-add-user-form-btn"><i class="fa fa-plus-circle"></i> Add User</button>
                    </div>
                </div>
                <table id="usersDt" class="table table-hover table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>Actions</th>
                        <th><i class="fa fa-twitter"></i></th>
                        <th>Email</th>
                        <th>Notification</th>
                        <th>Show</th>
                        <th>Date Joined</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="add-user-modal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel">
    <div class="modal-dialog bs-example-modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addUserModalLabel">Add User</h4>
            </div>
            <div class="modal-body">
                <div class="notice"></div>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Screen Name</label>
                        <div class="col-xs-6">
                            <input id="user-screen-name" type="email" class="form-control input-sm required" aria-label="email"  />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Email</label>
                        <div class="col-xs-6">
                            <input id="user-email" type="email" class="form-control input-sm" aria-label="email" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Notification</label>
                        <div class="col-xs-6">
                            <div class="checkbox">
                                <label style="font-size: 14px;">
                                    <input id="user-email-notification" type="checkbox" /> Receive email notification
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Privacy</label>
                        <div class="col-xs-6">
                            <div class="checkbox">
                                <label style="font-size: 14px;">
                                    <input id="user-show-profile" type="checkbox" /> Show profile
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-xs" id="add-user-btn">Add</button>
            </div>
        </div>
    </div>
</div>


<!-- Send Email Modal -->
<div class="modal fade" id="send-email-modal" tabindex="-1" role="dialog" aria-labelledby="send-email-modal-label">
    <div class="modal-dialog bs-example-modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="send-email-modal-label">Send Email</h4>
            </div>
            <div class="modal-body" id="send-email-form">
                <div class="notice"></div>
                <div class="form-group">
                    <p class="form-control-static" id="send-email-to"></p>
                </div>
                <div class="form-group">
                    <label for="send-email-title">Title</label>
                    <input id="send-email-title" type="email" class="form-control input-sm required" aria-label="email"  />
                </div>
                <div class="form-group">
                    <label for="send-email-message">Message</label>
                    <textarea class="form-control required" id="send-email-message" rows="5"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-xs" id="send-email-btn">Send Email</button>
            </div>
        </div>
    </div>
</div>

<script>
    var actionUrl = "<?php echo base_url() . 'admin/action'; ?>";
    var usersDt;
    var userEmail;

    $(function() {
        $('#admin-nav-users').addClass('active');

        $('#show-add-user-form-btn').on('click', function() {
            $('#add-user-modal').modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        });


        $('#add-user-btn').on('click', function() {
            if(validator.validateForm($('#add-user-modal'))) {
                var screenName = $('#user-screen-name').val();
                var email = $("#user-email").val();
                var emailNotification = $("#user-email-notification").is(":checked") ? 1 : 0;
                var showProfile = $("#user-show-profile").is(":checked") ? 1 : 0;
                if(emailNotification) {
                    if(!validator.validateEmail(email)) {
                        validator.displayAlertError($("#add-user-modal"), true, "Invalid email address.");
                        validator.displayInputError($("#user-email"), true);
                        return false;
                    }
                }
                validator.displayInputError($("#user-email"), false);
                validator.displayAlertError($("#add-user-modal"), false);

                toastr.info('Adding user, please wait...');

                var data = {
                    action  : 'user_add',
                    user    : {
                        name                : screenName,
                        email               : email,
                        email_notification  : emailNotification,
                        show_profile        : showProfile,
                        admin_insert        : 1
                    }
                };

                $.post(baseUrl + 'admin/get_twitter_id', {name: screenName}, function(id) {
                    if(id != "error") {
                        data.user.twitter_id = id;
                        $.post(actionUrl, data, function(data) {
                            if(data.success == true) {
                                toastr.success("Adding user successful!");
                                $('#add-user-modal').modal('hide');
                                validator.clearForm($('#add-user-modal'));
                                usersDt.fnReloadAjax();
                            } else {
                                validator.displayAlertError($("#add-user-modal"), true, data.message);
                                validator.displayInputError($("#user-screen-name"), true);
                            }
                        }, 'json');
                    } else {
                        validator.displayAlertError($("#add-user-modal"), true, "Twitter user not found.");
                        validator.displayInputError($("#user-screen-name"), true);
                    }
                });

            }
        });

        $('#send-email-btn').on('click', function() {
            if(validator.validateForm($('#send-email-form'))) {
                var data = {
                    action : 'send_email',
                    email : {
                        to : userEmail,
                        title : $('#send-email-title').val(),
                        message : $('#send-email-message').val()
                    }
                };

                $.post(actionUrl, data, function(res) {
                    toastr.success("Sending Email Successful!");
                    $('#send-email-modal').modal("hide");
                }, 'json');
            }
        });

        usersDt = $("#usersDt").dataTable({
            sorting: [4],
            bDestroy: true,
            ajax: {
                "type"  : "POST",
                "url"   : actionUrl,
                "data"  : { action: "user_list", public_only: 0 }
            },
            columns: [
                {data: "id",
                    render: function(data, type, row) {
                        var emailBtn = "&nbsp;<button class='btn btn-xs btn-default' onclick='sendEmail(\"" + row.email + "\", \"" + row.name + "\");'><i class='fa fa-envelope'></i></button>";
                        var actionButtons = "<button class='btn btn-xs btn-primary' onclick='subscribe(" + data + ", \"PREMIUM\");'><i class='fa fa-star'></i></button>&nbsp;<button class='btn btn-xs btn-default' onclick='subscribe(" + data + ", \"TOP\");'><i class='fa fa-arrow-up'></i></button>";
                        if(row.email) {
                            actionButtons += emailBtn;
                        }
                        return actionButtons;
                    }
                },
                {data: "name",
                    render: function(data, type, row) {
                        return "<a href='https://www.twitter.com/" + row.name + "' target='_blank'>" + data + "</a>";
                    }
                },
                {data: "email"},
                {data: "email_notification",
                    render: function(data, type, row) {
                        return data == 1 ? "<i class='fa fa-check text-success'></i>" : "<i class='fa fa-times text-danger'></i>";
                    }
                },
                {data: "show_profile",
                    render: function(data, type, row) {
                        return data == 1 ? "<i class='fa fa-check text-success'></i>" : "<i class='fa fa-times text-danger'></i>";
                    }
                },
                {data: "date_created"}
            ]
        });
    });

    function subscribe(id, type) {
        var data = {
            action : 'subscribe',
            subscription : {
                user_id : id,
                service : type
            }
        };
        $.post(actionUrl, data, function(res) {
            if(res.success) {
                toastr.success("Subscribing user successful!");
            }
        }, 'json');
    }

    function sendEmail(email, name) {
        $('#send-email-modal').modal("show");
        $('#send-email-to').html("To: " + name);
        userEmail = email;
    }
</script>