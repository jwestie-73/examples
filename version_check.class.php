<?php

class version_check {

	/*
	 * This class is currently inconplete and requires work.
	 */

    private string $os = '';
    private string $version = '';
    private int $response = 0;
    private array $values = [
        0       => 'Current Version',
        1       => 'Supported Version, please consider updating the app',
        900     => 'Unsupported or Test version',
        999     => 'This version of the App is obsolete.  You must upgrade to continue'
    ];
    private string $sql_error = '';
    private int $sql_error_code = 0;
    private int $http_response = 0;

    public function __construct(array $data) {
        $this->set_data($data);
    }

    /** Sets the OS and Version values from the supplied data
     * @param array $data The input data read from the post values
     */
    private function set_data(array $data): void {
        $this->os = strtoupper($data['os_type']);
        $this->version = $data['app_version'];

    }

    /** returns the number of rows that correspond to the selected OS and Version
     */
    private function count_version(): void {
        $sql = <<<SQL
            SELECT COUNT(*) FROM `app_ver`
            WHERE `aggregated` = ?
            AND `os` = ?
SQL;
        $mysql = mysql::get_connection();
        $stmt = $mysql->prepare($sql);
        $stmt->bind_param("ss", $this->version, $this->os);
        $stmt->execute();

        if ($stmt->errno) {
            // Dump the error message and data
            $error_message = <<<ERROR
                SQL ERROR {$stmt->errno}: {$stmt->error}<br>
                SQL: {$sql}<br>
                Version: {$this->version}<br>
                OS: {$this->os}
ERROR;
            // Populate the class variables
            $this->sql_error = $stmt->error;
            $this->sql_error_code = $stmt->errno;
            $this->http_response = 400;

            //Audit the error
            $this->event_data = audit_log::create_event_data(5000,2,'SQL', 'SQL ERROR', 'API SECURITY CHECK', $error_message);
            new audit_log(audit_log::add_data($this->event_data, $this->api_data));
            return;
        }

        $stmt->fetch();
        $stmt->bind_result($count);
        $stmt->close();

        //todo:  Incomplete - requires finishing

        if ($count == 0) {

        }


    }

    /** INCOMPLETE - matches the current version
     *
     */
    private function match_version(): void {
        $sql = <<<SQL
            SELECT `version_check` FROM `app_ver`
            WHERE `aggregated` = ?
            AND `os` = ?
SQL;

    }


    // ------------ Public methods below this line

    public function check(): int {
        return $this->response;
    }

    public function message(): string {
        return $this->values[$this->response];
    }


    /*

    $api_data = [
    'device_id'     => $data->device_id,
    'app_token'     => $data->app_token,
    'os_type'       => $data->platform,
    'os_version'    => $data->platformVersion,
    'app_version'   => $data->appVersion
];

          */


}