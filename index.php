<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>North Carolina Zoo</title>

    <!-- Third party -->
    <link rel="stylesheet" type="text/css" href="thirdparty/bootstrap/css/bootstrap.min.css" class="no-delete-css">
    <link rel="stylesheet" type="text/css" href="thirdparty/font-awesome/css/font-awesome.min.css" class="no-delete-css">

    <link rel="stylesheet" type="text/css" href="thirdparty/jquery-ui/jquery-ui.min.css" class="no-delete-css">
    <link rel="stylesheet" type="text/css" href="thirdparty/jquery-ui/jquery-ui.theme.min.css" class="no-delete-css">
    <link rel="stylesheet" type="text/css" href="thirdparty/jquery-ui/jquery-ui.structure.min.css" class="no-delete-css">

    <script type="text/javascript" src="thirdparty/jquery/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="thirdparty/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="thirdparty/jquery-ui/jquery-ui.min.js"></script>

    <!-- Ours -->
    <link rel="stylesheet" type="text/css" href="css/main.css" class="no-delete-css">
    <script type="text/javascript" src="js/main.js"></script>

</head>
<body>

    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id))
                return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
        })(document, 'script', 'facebook-jssdk');
    </script>

    <div class="panel panel-heading">
        <div class="row">
            <div class="col-xs-12 col-md-8 text-left"><h1>Welcome to the <a href="#" class="head-nav">NC State Zoo</a></h1></div>
            <div class="hidden-xs hidden-sm col-md-4 text-right"><a href="#index.php" class="head-nav"><img src="images/ZooLogo.png" alt="NC Zoo"></a></div>
        </div>
    </div>

    <div class="panel panel-danger" id="hierarchy">
        &nbsp;
    </div>

    <div class="page-header" id="fav-questions">
        <div id="page-count"></div>
        <?php
        include 'presenter-ips.php';
        if (!in_array($_SERVER['REMOTE_ADDR'], $presenters)) {
        ?>
        <div id="buttons">
            <div id="heart" class="fa fa-heart">&nbsp;</div>
            <div id="question-dialog">
                <input id="question-submit" type="text" maxlength="100" placeholder="Have a question?">
                <span id="submit-dialog" class="fa fa-envelope"></span>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="container container-fluid" id="dashboard">
        <noscript>
            You need JavaScript enabled to view this page.
        </noscript>
    </div>

    <?php
    include 'presenter-ips.php';
    if (in_array($_SERVER['REMOTE_ADDR'], $presenters)) {
    ?>
    <div id="presenter-view" class="panel">
        <div class="row">
            <div class="col-xs-4 border-right">
                <h4>Most Liked</h4>
            </div>
            <div class="col-xs-4 border-right">
                <h4>Viewing Now</h4>
            </div>
            <div class="col-xs-4">
                <h4>Most Visited</h4>
            </div>
        </div>
        <div class="row">
            <div class="container col-xs-4 border-right" id="favorites"></div>
            <div class="container col-xs-4 border-right" id="viewing"></div>
            <div class="container col-xs-4" id="visited"></div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-xs-12">
                <h4>Questions</h4>
            </div>
        </div>
        <div class="row">
            <div class="container col-xs-12" id="questions"></div>
        </div>
        <span id="refresh-presenter" class="fa fa-refresh"></span>
    </div>
    <?php } ?>

    <div class="container container-fluid" id="facebook-like">
        <div class="row">
            <div class="col-xs-4 col-xs-offset-4 text-center">
                <div class="fb-like" data-href="https://www.facebook.com/f4ep" data-layout="button_count"
                     data-action="like" data-show-faces="false" data-share="false"></div>
            </div>
        </div>
    </div>

</body>
</html>