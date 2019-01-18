<?php

namespace LzCalderaro;

class Authentication {

	/**
	 * Generate and write token on file.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public static function login() {

		echo "\n Please type your username: ";

		$handle   = fopen( 'php://stdin', 'r' );
		$username = trim( fgets( $handle ) );

		if ( empty( $username ) ) {
			echo 'Empty username';
			exit;
		}

		fclose( $handle );

		echo "\n Now type your password: ";

		$handle   = fopen( 'php://stdin', 'r' );
		$password = trim( fgets( $handle ) );

		if ( empty( $password ) ) {
			echo 'Empty password';
			exit;
		}

		fclose( $handle );

		$url_request = Config::get_config( 'path' ) . 'auth';
		$args        = [
			'auth' => [ $username, $password ],
		];

		$request = Request::do_request( $args, $url_request );

		if ( $request === false ) {
			return false;
		}

		$response = json_decode( $request );

		static::save_organizations( $response->organizations );
		static::write_token( $response->access_token );

		return $response;
	}

	/**
	 * Save all organizations
	 *
	 * @since  1.0.0
	 * @param  [type] $organizations [description]
	 * @return [type]                [description]
	 */
	public function save_organizations( $organizations ) {

		$organization_file = fopen( Config::get_config( 'organization_file' ), 'w' );
		$write             = fwrite( $organization_file, json_encode( $organizations ) );

		fclose( $organization_file );

		return $write;
	}

	/**
	 * Write token on file.
	 *
	 * @since  1.0.0
	 * @param  string $token
	 * @return bool
	 */
	public function write_token( $token ) {

		$token_file = fopen( Config::get_config( 'token_file' ), 'w' );
		$write      = fwrite( $token_file, $token );

		fclose( $token_file );

		return $write;
	}

	/**
	 * Get codeship token.
	 *
	 * @since  1.0.0
	 * @return string With valid token.
	 */
	public static function get_token() {

		$token = file_get_contents( Config::get_config( 'token_file' ) );

		if ( empty( $token ) || static::verify_token( $token ) === false ) {

			return static::login()->access_token;
		}

		return $token;
	}

	/**
	 * Verify Token.
	 *
	 * @since         1.0.0
	 * @param  string $token
	 * @return bool   If token is valid
	 */
	public function verify_token( $token ) {

		$organization = Config::get_config( 'organization' );
		$url_request  = Config::get_config( 'path' ) . "organizations/{$organization}/projects";
		$args         = [
			'headers' => [
				'Authorization' => "Bearer {$token}",
			],
		];

		$request = Request::do_request( $args, $url_request, 'GET' );

		return $request === false ? $request : true;
	}
}
