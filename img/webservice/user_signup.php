<?php

class ClaimApi extends Webservice {

    function validate() {
        global $message_cls, $wpdb;

        $this->email = tep_get_value_post("email", "Email", "require;email;");
        $this->device = tep_get_value_post("device");
        $this->firstname = tep_get_value_post("firstname", "FirstName", "require");
        $this->lastname = tep_get_value_post("lastname", "LastName", "require");
        $this->password = tep_get_value_post("password", "Password", "require;length[6];");
        //var_dump($this->firstname);
        //var_dump($this->lastname); exit;
        if ($this->email) {
            tep_unique_check(TABLE_USERS, array("email" => $this->email), "", "email", "Email");
        }
        if ($this->firstname == "" || $this->lastname == "") {
            $this->errorcode = ERRORCODE_USERNAME;
        }
        if ($message_cls->is_empty_error()) {
            $this->errorcode = SUCCESS_CODE;
        }
    }

    function run() {
        global $message_cls, $wpdb;
        $this->validate();
        if ($this->errorcode == SUCCESS_CODE) {
            $userinfo = array(
                "firstname" => $this->firstname,
                "lastname" => $this->lastname,
                "email" => $this->email,
                "password" => tep_encrypt_password($this->password),
                "createdate" => tep_now_datetime(),
                "status" => 1,
                "api_key" => tep_generate_api_key()
            );

            if ($wpdb->insert(TABLE_USERS, $userinfo) !== false) {
                $this->result = $userinfo;
            } else {
                $this->set_database_error();
            }
        }
        $this->json_result();
    }

}
