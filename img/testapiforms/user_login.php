<?php

require ('../library/admin_application_top.php');

echo "<h2>Call URL: [web server address]/webservice.php</h2>";

$api = tep_get_value_post("api");

$bsForm = new bs_FORM($api, "webservice.php", "post", false);
$bsForm->set_target("testApiResult");
$bsForm -> add_element("device", BSFORM_TEXT, "test");
$bsForm -> add_element("api", BSFORM_TEXT, $api, "API");
$bsForm -> add_element("email", BSFORM_TEXT, "", "Email");
$bsForm -> add_element("password", BSFORM_PASSWORD, "", "Password");
$bsForm -> add_element("", BSFORM_HTML, "<hr/>");

echo $bsForm -> generate();