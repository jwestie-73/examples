<?php

class general
{
	/**
	 * Formats the display for a boolean to show either Yes or No
	 *
	 * @param $input
	 * @return string
	 */
	public static function formatbool($input) {
		return ($input)
			? 'Yes'
			: 'No';
	}

	/**
	 * Creates a 36-character GUID.
	 *
	 * @return string
	 */
	public static function getGUID(): string {
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			return self::get_safe_guid();
		}
	}

	/**
	 * Creates a web-safe GUID
	 *
	 * @return string The GUID
	 */
	public static function get_safe_guid(): string {
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = substr($charid, 0, 8).$hyphen
			.substr($charid, 8, 4).$hyphen
			.substr($charid,12, 4).$hyphen
			.substr($charid,16, 4).$hyphen
			.substr($charid,20,12);

		return $uuid;
	}

	/**
	 * Validates an email address
	 *
	 * @param string $email
	 * @return bool
	 */
	public static function IsEmail(string $email): bool {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * Ensures that a string is sanitized
	 *
	 * @param string $input
	 * @return string
	 */
    public static function sanitize(string $input): string {
        $output = ltrim(rtrim($input));
        return htmlentities($output, ENT_QUOTES);
    }

	/**
	 * Ensures that a name is capitalised and there are no strange characters
	 *
	 * @param string $input
	 * @return string
	 */
    public static function name(string $input): string {
        $output = ltrim(rtrim($input));
        return htmlentities(ucwords($output), ENT_QUOTES);
		// Need to cater for the following name conventions
        // McDonald
	    // MacDonald
	    // O'Brien
    }


}