<?php
// session_start();

$page_title = "Add New User";
$page_slug = "users_";
$page_sub_slug = "usersnew";
require ('library/admin_application_top.php');

$action = (isset($_POST['action']) ? $_POST['action'] : '');

$msg = "";
$success_msg = "";
$email = "";


if (tep_not_null($action) && $action == "process") {
    $email = tep_get_value_post("email", "Email", "require;email");
    $firstname = tep_get_value_post("firstname", "FirstName", "require;");
    $lastname = tep_get_value_post("lastname", "LastName", "require;");
    $new_password = tep_get_value_post("new_password", "New Password", "require;length[6]");
    $re_password = tep_get_value_post("re_password", "Repeat Password", "equals[new_password];");

    if ($email) {
        tep_unique_check(TABLE_USERS, array("email" => $email), "", "email", "Email");
    }

    if ($message_cls->is_empty_error()) {
        $userinfo = array(
            "email" => $email,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "password" => tep_encrypt_password($new_password),
            "createdate" => tep_now_datetime(),
            "status" => 1,
            "api_key" => tep_generate_api_key()
        );

        if ($wpdb->insert(TABLE_USERS, $userinfo) !== false) {
            $success_msg = "You have successfully added new user.";
        } else {
            $message_cls->set_error("update_process", "Failed add new user.");
        }
    }
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
            ?>
            <script>
                $(function () {
                    setTimeout(function () {
                        location.href = 'usersall.php';
                    }, 1500);
                })
            </script>
            <?php
        }
        ?>

        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Input User Profile</h2>
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
                        $bsForm->add_element("firstname", BSFORM_TEXT, "", "First Name");
                        $bsForm->add_element("lastname", BSFORM_TEXT, "", "Last Name");
                        $bsForm->add_element("email", BSFORM_TEXT, $email);
                        $bsForm->add_element("new_password", BSFORM_PASSWORD, "", "New Password", false);
                        $bsForm->add_element("re_password", BSFORM_PASSWORD, "", "Repeat Password", false);

                        echo $bsForm->generate();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
require ('views/footer.php');
