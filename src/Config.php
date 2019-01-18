<?php

namespace Log;

class Config {

	/**
	 * Return config value.
	 *
	 * @param  string $key
	 * @return string|bool With value or false.
	 */
	public static function get_config( $key ) {

		$config = array(
			'username'          => getenv( 'USERNAME' ),
			'password'          => getenv( 'PASSWORD' ),
			'organization'      => getenv( 'ORGANIZATION_UUID' ),
			'project'           => getenv( 'PROJECT_UUID' ),
			'path'              => getenv( 'CODESHIP_API_URL' ),
			'token_file'        => '.token',
			'organization_file' => '.organization',
		);

		return $config[ $key ] ?? false;
	}
}
