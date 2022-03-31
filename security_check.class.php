<?php

class security_check
{

    private string $device_id = '';
    private string $api_token = '';
    private array $api_data = [];
    private array $event_data = [];
    private bool $authorised = false;
    private string $sql_error = '';
    private int $sql_error_code = 0;
    private int $http_response = 0;

    public function __construct($data) {
        $this->api_data = $data;
        $this->device_id = $data['device_id'];
        $this->api_token = $data['app_token'];
        $this->check_register();
    }

    /**
     * Checks the device_id and api token against the register
     *
     * @return void
     */
    private function check_register() {
        $sql = <<<SQL
            SELECT COUNT(*) FROM `api_register` 
            WHERE `device_id` = ?
            AND `token_id` = ?
            AND `status` = 0
SQL;

        $mysql = mysql::get_connection();
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param("ss", $this->device_id, $this->api_token);
        $stmt->execute();

        // if we have errors
        if ($stmt->errno) {
            // Dump the error message and data
            $error_message = <<<ERROR
                SQL ERROR {$stmt->errno}: {$stmt->error}<br>
                SQL: {$sql}<br>
                Device ID: {$this->device_id}<br>
                TOKEN: {$this->api_token}
ERROR;
            // Populate the class variables
            $this->sql_error = $stmt->error;
            $this->sql_error_code = $stmt->errno;
            $this->http_response = 400;
            $this->authorised = false; // IMPORTANT: SQL ERRORS MUST FAIL AUTHORISATION
            //Audit the error
            $this->event_data = audit_log::create_event_data(5000,2,'SQL', 'SQL ERROR', 'API SECURITY CHECK', $error_message);
            new audit_log(audit_log::add_data($this->event_data, $this->api_data));
            return;
        }

        $stmt->bind_result($entities);
        $stmt->fetch();
        $stmt->close();

        // Set authorisation
        $this->authorised = ($entities == 1);
        // Set Response code
        $this->http_response = ($entities == 1)
            ? 200
            : 403;

        // Set up for success or error
        if ($this->authorised) {
            $this->event_data = audit_log::create_event_data(3001,0, 'API','SUCCESS','SECURITY CHECK', 'API Authentication Success');
        } else {
            $this->event_data = audit_log::create_event_data(3002, 1,'API','FAIL','SECURITY CHECK', 'API Authentication Failed');
        }

        // Audit the security check.
        new audit_log(audit_log::add_data($this->event_data, $this->api_data));
    }


    // ----------- Public methods below this post.

    /**
     * Returns a boolean to show if the device is authorised
     *
     * @return bool
     */
    public function IsAuthorised(): bool {
        return $this->authorised;
    }

    /**
     * Returns any SQL error messages
     *
     * @return string
     */
    public function SQLError(): string {
        return $this->sql_error;
    }

    /**
     * Returns any SQL error codes
     *
     * @return int
     */
    public function SQLErrorCode(): int {
        return $this->sql_error_code;
    }

    /**
     * Returns the response code.
     *
     * @return int
     */
    public function get_response(): int {
        return $this->http_response;
    }

}