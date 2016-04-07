<!--<section class="danero-nav">-->
<!--    <div class="container-fluid" style="padding: 15px 40px">-->
<!--        <div class="row">-->
<!--            <div class="col-md-6">-->
<!--                <h2 style="font-weight: bold;">-->
<!--                    <a href="--><?php //echo base_url(); ?><!--"><span style="font-size: 30px;">T</span>WITTER AMIGOS-->
<!--                </h2>-->
<!--            </div>-->
<!--            --><?php //if(null == $user) { ?>
<!--                <div class="col-md-3 col-md-offset-3">-->
<!--                    <div class="un-logged">-->
<!--                        <a href="--><?php //echo base_url() . 'main/contact'; ?><!--">Contact</a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            --><?php //} else { ?>
<!--                <div class="danero-box">-->
<!--                    <div class="row" style="margin-bottom: 10px;">-->
<!--                        <div class="col-xs-2 text-center">-->
<!--                            <img src="--><?php //echo $user['twitter']->profile_image_url; ?><!--" class="img-circle" />-->
<!--                        </div>-->
<!--                        <div class="col-xs-10" style="padding-top: 10px; padding-left: 20px;">-->
<!--                            <a href="--><?php //echo "https://twitter.com/" . $user['twitter']->screen_name; ?><!--" target="_blank" style="font-size: 22px;">-->
<!--                                --><?php //echo $user['twitter']->screen_name; ?>
<!--                            </a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="row" style="margin-bottom: 10px;">-->
<!--                        <div class="col-xs-12" id="emailDiv">-->
<!--                            --><?php //if(isset($user)) { ?>
<!--                                --><?php //if($user['user']->email != "") { ?>
<!--                                    <i class="fa fa-envelope"></i> --><?php //echo isset($user) ? $user['user']->email : ""; ?>
<!--                                --><?php //} else { ?>
<!--                                    <p class="text-danger" style="font-size: 13px;"><i class="fa fa-exclamation-circle"></i> You have not setup your email yet.</p>-->
<!--                                --><?php //} ?>
<!--                            --><?php //} else { ?>
<!--                                <p class="text-danger" style="font-size: 13px;"><i class="fa fa-exclamation-circle"></i> You have not setup your email yet.</p>-->
<!--                            --><?php //} ?>
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="row">-->
<!--                        <div class="col-xs-12">-->
<!--                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#settingsModal">-->
<!--                                Settings-->
<!--                            </button>-->
<!--                            <a class="btn btn-danger btn-xs" href="--><?php //echo base_url() . "main/logout"; ?><!--">Logout</a>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!---->
<!--                </div>-->
<!--            --><?php //} ?>
<!--        </div>-->
<!--    </div>-->
<!--</section>-->

<nav class="navbar navbar-default danero-nav">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url(); ?>"><span style="font-size: 30px;">T</span>WITTER AMIGOS
        </div>

        <div class="collapse navbar-collapse" id="top-navbar">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo base_url() . 'main/contact'; ?>">Contact</a></li>
                <?php if(null == $user) { ?>
                <li>
                    <a href="<?php echo $twitter_auth_url; ?>">
                        <i class="fa fa-twitter"></i> Sign in with twitter
                    </a>
                </li>
                <?php } else { ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo $user['twitter']->profile_image_url; ?>" class="img-circle pull-left" height="25" /> &nbsp;
                        <?php echo $user['twitter']->screen_name; ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="modal" data-target="#settingsModal">Settings</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?php echo base_url() . "main/logout"; ?>">Logout</a></li>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

