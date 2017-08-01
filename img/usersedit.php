<?php
$page_title = "Edit User";
$page_slug = "users_";
$page_sub_slug = "usersall";
require ('library/admin_application_top.php');

$userid = (isset($_GET['id']) ? $_GET['id'] : '');
if ($userid == '') {
    tep_redirect('usersnew.php');
}
$action = (isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : ""));

$msg = "";

$success_msg = "";

$userinfo = $wpdb->get_row("SELECT * FROM " . TABLE_USERS . " WHERE `id`='" . $userid . "'");
$firstname = $userinfo->firstname;
$lastname = $userinfo->lastname;
$email = $userinfo->email;

if (tep_not_null($action) && $action == "process") {

    $email = tep_get_value_post("email", "Email", "require;email");
    $firstname = tep_get_value_post("firstname", "FirstName", "require;");
    $lastname = tep_get_value_post("lastname", "LastName", "require;");
    $password = tep_get_value_post("old_password", "Old Password", "length[6]");
    $new_password = tep_get_value_post("new_password", "New Password", "length[6]");
    $re_password = tep_get_value_post("re_password", "Repeat Password", "equals[new_password];");


    if ($email) {
        tep_unique_check(TABLE_USERS, array("email" => $email), "`id`<>" . $userid, "email", "Email");
    }
    if (!tep_validate_password($password, $userinfo->password)) {
        $message_cls->set_error("password", "Password update failed.");
    } else if (tep_validate_password($new_password, $userinfo->password)) {
        $message_cls->set_error("password", "Password update failed.");
    }

    if ($message_cls->is_empty_error()) {
        $_userinfo = array(
            "email" => $email,
            "firstname" => $firstname,
            "lastname" => $lastname,
            'password' => tep_encrypt_password($new_password),
            "modifiedate" => tep_now_datetime()
        );

        if ($wpdb->update(TABLE_USERS, $_userinfo, array("id" => $userid)) !== false) {
            $success_msg = "Password update successful.";
        } else {
            $message_cls->set_error("update_process", "Failed update user's profile.");
        }
    }
} elseif ($action == 'apikey') {
	$userinfo -> api_key = tep_generate_api_key();
    $wpdb->update(TABLE_USERS, array("api_key" => $userinfo->api_key), array("id" => $userid));

    $success_msg = "New generated api key.";
}

require ('views/header.php');
?>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="page-title">
            <div class="title_left">
                <h3><?php echo $page_title ?></h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <?php
        if ($success_msg != '') {
            tep_show_msg($success_msg);
        }
        ?>

        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Input User Profile: @<?php echo $userid; ?></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?php
                        if (!$message_cls->is_empty_error()) {
                            echo $message_cls->get_all_message(true);
                        }

                        $bsForm = new bs_FORM("newUser", "", "post", false);
                        $bsForm->add_element("action", BSFORM_HIDDEN, "process");
                        $bsForm->add_element("", BSFORM_HTML, "<p>Input user information</p>");
                        $bsForm->add_element("firstname", BSFORM_TEXT, $firstname, "First Name");
                        $bsForm->add_element("lastname", BSFORM_TEXT, $lastname, "Last Name");
                        $bsForm->add_element("email", BSFORM_TEXT, $email);
                        $bsForm->add_element("old_password", BSFORM_PASSWORD, "", "Old Password");
                        $bsForm->add_element("new_password", BSFORM_PASSWORD, "", "New Password", false);
                        $bsForm->add_element("re_password", BSFORM_PASSWORD, "", "Repeat Password", false);

                        echo $bsForm->generate();
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>API Key</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="item">
                            <a href="#" id="show_apikey"><i class="fa fa-eye"></i> Show</a>
                        </div>
                        <div class="item" id="apikey" style="display: none;">
                            <label><?php echo $userinfo->api_key; ?></label>
                            &nbsp;&nbsp;&nbsp;
                            <button class="btn btn-info btn-sm" id="new_generate_password">New Generate</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

</div>

<script>
    $(function () {
        $("#show_apikey").click(function () {
            $i = $(this).find("i.fa");
            if ($i.hasClass("fa-eye")) {
                $i.removeClass("fa-eye").addClass("fa-eye-slash");
                $("#apikey").show();
            } else {
                $i.removeClass("fa-eye-slash").addClass("fa-eye");
                $("#apikey").hide();
            }
        })

        $("#new_generate_password").click(function () {
            location.href = "usersedit.php?id=<?php echo $userid; ?>&action=apikey";
        })
    })
</script>

<?php include_once 'views/table.js.php' ?>

<?php
require ('views/footer.php');
