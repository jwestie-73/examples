<?php

class api_register {

    private $api_token = '';
    private $device_id = '';
    private $error_code = 0;
    private $error = '';
    private $http_response = 0;

    public function __construct(string $device_id) {
        $this->device_id = $device_id;
        $this->api_token = general::get_safe_guid();

        if ($this->register()) {
            // success
            $this->http_response = 200;
        } else {
            // fail
            $this->http_response = 400;
            $this->api_token = ''; // reset token if fail
        }

    }

    /**
     * Registers the APP with the API
     *
     * @return bool False if success, True if there's an error.
     */
    private function register(): bool {
        $is_good = 1;
        $dt = new datetime();
        $reg_date = $dt->format(MYSQL_DATETIME);
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $sql = <<<SQL
            INSERT INTO `api_register` (`device_id`, `token_id`, `registered`, `register_ip`, `status`) 
            VALUES (?,?,?,?,0)
SQL;
        $mysql = mysql::get_connection();
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param("ssss", $this->device_id, $api_token, $reg_date, $ip_address);
        $stmt->execute();
        if ($stmt->error) {
            $this->error_code = $stmt->errno;
            $this->error = $stmt->error;
            $is_good = 0;
        }
        $stmt->close();

        return $is_good;
    }

    // public methods below this line ------------

    /**
     * Returns the API Token
     *
     * @return string
     */
    public function get_token(): string {
        return $this->api_token;
    }

    /**
     * Returns the error codes
     *
     * @return array
     */
    public function get_errors(): array {
        return [
            'error_code'    => $this->error_code,
            'error_message' => $this->error
        ];
    }

    /**
     * returns the HTTP response
     *
     * @return int
     */
    public function show_response(): int {
        return $this->http_response;
    }

}