<?php
/* ========================================
 MYSQL Class Initial Version
 By westi (2020-08-01)
 ------------------------------------------
 Description (Use Multiple Lines):
 Class for all MySQL Database access
 and associated functions
 -------------------------------------------
 Notes:


 Changelog:

*/

class mysql
{

	private static $instance = null;

	/**
	 * Opens the MySQL Database connection
	 */
	public static function get_connection(
		$host = MYSQL_HOST,
		$user = MYSQL_USERNAME,
		$pass = MYSQL_PASSWORD,
		$dbname = MYSQL_DATABASE,
		$port = MYSQL_PORT,
		$reconnect = false
	) {
		if (self::$instance === null || $reconnect) {
			self::$instance = @new mysqli($host, $user, $pass, $dbname, $port);
		}

		if (self::$instance->connect_error) {
			exit('Timeout trying to connect, please try again shortly');
		}

		return self::$instance;
	}

	/**
	 * Binds parameters using an array
	 *
	 * @param $stmt
	 * @param $params
	 * @return mixed
	 */
	public static function bind_param_array($stmt, $params) {
		ksort($params);

		$func_params[0] = '';

		foreach ($params as $k => $param){
			if (!in_array($param[0], array('i', 'd', 's', 'b'))){
				trigger_error("Parameter #{$k} has an invalid type", E_USER_WARNING);
				continue;
			}

			$func_params[0] .= $param[0];
			$func_params[] = &$params[$k][1];
		}

		$stmt_error = !$stmt && self::$instance->error;

		if ($stmt_error) {
			throw new invalid_sql_exception(self::$instance->error);
		}

		return call_user_func_array(array($stmt, 'bind_param'), $func_params);
	}

	public static function update_field_by_id (
		$table,
        $field,
		$value,
		$type,
		$id
	) {
		self::update_field($table, $field, $value, $type, 'idx', $id);
	}

	public static function update_field_by_name (
		$table,
		$field,
		$value,
		$type,
		$name
	) {
		self::update_field($table, $field, $value, $type, 'name', $name);
	}

	public static function update_field_by_code (
		$table,
		$field,
		$value,
		$type,
		$code
	) {
		self::update_field($table, $field, $value, $type, 'code', $code);
	}

	private static function update_field(
		$table,
		$field,
		$value,
		$type,
		$by,
		$by_clause
	) {
		// used to determine the data type of the where clause.
		// Add to this list as you see fit.
		switch ($by) {
			case 'idx':
				$type = $type.'i';
				break;
			case 'name':
			case 'code':
			$type = $type.'s';
				break;
		}


		$sql = <<<SQL
			UPDATE {$table}
			SET {$field} = ?
			WHERE `{$by}` = ?
SQL;

		$mysql = self::get_connection();
		$stmt = $mysql->prepare($sql);
		$stmt->bind_param($type, $value, $by_clause);
		$stmt->execute();
		$stmt->close();
	}
}
