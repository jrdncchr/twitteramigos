<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $head; ?>
</head>

<body>
<div class="wrapper">

    <div id="wrapper">

        <div id="sidebar-wrapper">
            <?php echo $sidenav; ?>
        </div>

        <div id="page-content-wrapper">
            <section class="container-fluid">
                <?php echo $content; ?>
            </section>
        </div>

    </div>

    <div class="push"></div>
</div>

</body>

</html>