<?php
include_once("php_includes/check_login_status.php");
// Eğer zaten giriş yapılmış ise varsayılan sayfaya yönlendir
if ($user_ok == true) {
    header("Location: success_login_redirect.php");
    exit();
}
?>
<?php
// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if (isset($_POST["u"])) {

    // GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
    $u = $_POST['u'];
    $p = strtoupper(md5($_POST['p']));
    // FORM DATA ERROR HANDLING
    if ($u == "" || $p == "") {
        echo "login_failed1";
        exit();
    } else {
        // CONNECT TO THE DATABASE
        require_once "php_includes/DBConnection.php";
        $dbConnection = new DBConnection();
        $dbConnection->Connect();

        // END FORM DATA ERROR HANDLING
        $sql = "SELECT e_mail username, password passwordhash FROM user_credentials WHERE e_mail='$u'";
        $query = mysql_query($sql);
        if (mysql_num_rows($query) > 0) {
            $row = mysql_fetch_array($query);
            $db_username = $row['username'];
            $db_pass_str = strtoupper($row['passwordhash']);
            if ($p != $db_pass_str) {
                echo "login_failed2,$p,$db_pass_str";
                $dbConnection->Close();
                exit();
            } else {
                // CREATE THEIR SESSIONS AND COOKIES
                $_SESSION['username'] = $db_username;
                $_SESSION['password'] = $db_pass_str;
                setcookie("user", $db_username, strtotime('+30 days'), "/", "", "", TRUE);
                setcookie("pass", $db_pass_str, strtotime('+30 days'), "/", "", "", TRUE);
                // UPDATE LASTLOGIN FIELD
                $sql = "UPDATE users u JOIN user_credentials e ON u.id=e.id SET last_login=now() WHERE e_mail='$u'";
                $query = mysql_query($sql);
                echo $db_username;
                $dbConnection->Close();
                exit();
            }
        }
        echo "login_failed3";
        $dbConnection->Close();
        exit();
    }
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>Login Form</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
    <link href="assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link rel="stylesheet" type="text/css" href="assets/plugins/select2/select2_metro.css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="assets/css/pages/login-soft.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL STYLES -->
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
<!-- BEGIN LOGO -->
<div class="logo">
    <!-- PUT YOUR LOGO HERE -->
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="form-vertical login-form" onsubmit="return false;">
        <h3 class="form-title">Hesap Girişi</h3>
        <div class="alert alert-error hide">
            <button id="close_btn" class="close" data-dismiss="alert" style="top:30%;"></button>
            <span id="errormsg">Lütfen tüm alanları doldurun.</span>
        </div>
        <div class="control-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Kullanıcı Adı</label>
            <div class="controls">
                <div class="input-icon left">
                    <i class="icon-user"></i>
                    <input id="username" class="m-wrap placeholder-no-fix" type="text" autocomplete="off" placeholder="Kullanıcı Adı" name="username"/>
                </div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label visible-ie8 visible-ie9">Şifre</label>
            <div class="controls">
                <div class="input-icon left">
                    <i class="icon-lock"></i>
                    <input id="password" class="m-wrap placeholder-no-fix" type="password" autocomplete="off" placeholder="Şifre" name="password"/>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button id="loginbtn" type="submit" class="btn blue pull-right">
                Giriş <i class="m-icon-swapright m-icon-white"></i>
            </button>
        </div>
    </form>
    <!-- END LOGIN FORM -->
</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
    2016 &copy; Fermion by LabbiqX, Balyoz, Hakan
</div>
<!-- END COPYRIGHT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->   <script src="assets/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="assets/plugins/excanvas.min.js"></script>
<script src="assets/plugins/respond.min.js"></script>
<![endif]-->
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="assets/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/scripts/app.js" type="text/javascript"></script>
<script src="assets/scripts/login-soft.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jQuery(document).ready(function() {
        App.init();
        Login.init();
    });
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>