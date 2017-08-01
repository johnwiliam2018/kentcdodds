<?php

class ClaimApi extends Webservice {

    function validate() {
        global $message_cls, $wpdb;

        $this->device = tep_get_value_post("device");
        $this->email = tep_get_value_post("email", "Email", "require;email;");
        $this->password = tep_get_value_post("password", "Password", "require;");

        if ($message_cls->is_empty_error()) {
            $this->user = $wpdb->get_row("SELECT * FROM " . TABLE_USERS . " WHERE `email` = '{$this->email}'");
            if ($this->user) {
                if (tep_validate_password($this->password, $this->user->password)) {
                    if ($this->user->status == 1) {
                        $this->errorcode = SUCCESS_CODE;
                    } else {
                        $this->errorcode = ERRORCODE_SECURITY;
                        $message_cls->set_error("user", "Your account has blocked.");
                    }
                } else {
                    $this->errorcode = ERRORCODE_PASSWORD;
                    $message_cls->set_error("password", "Your password is not validate.");
                }
            } else {
                $this->errorcode = ERRORCODE_EMAIL;
                $message_cls->set_error("email", "Your email is not validate.");
            }
        }
    }

    function run() {
        global $wpdb;
        $this->validate();
        if ($this->errorcode == SUCCESS_CODE) {
            $wpdb->update(TABLE_USERS, array("last_actived" => tep_now_datetime()), array("id" => $this->user->id));

            $wpdb->insert(TABLE_USER_VISIT_LOGS, array(
                "user_id" => $this->user->id,
                "logined_day" => date('Y-m-d'),
                "logined_time" => date('H:i:s'),
                "logined_device" => $this->device
            ));

            $this->status = TRUE;
            $this->result[] = array(
                "userid" => $this->user->id,
                "firstname" => $this->user->firstname,
                "lastname" => $this->user->lastname,
                "email" => $this->user->email,
                "api_key" => $this->user->api_key
            );
        }
        $this->json_result();
    }

}
