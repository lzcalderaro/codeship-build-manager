<?php

namespace LzCalderaro;

class Builds {

	/**
	 * Basic variables to get builds.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $project;
	public $organization;

	/**
	 * Construct method.
	 *
	 * @since 1.0.0
	 * @param string $organization Organization UUID.
	 * @param string $project      Project UUID
	 */
	public function __construct( $organization, $project ) {

		$this->project      = $project;
		$this->organization = $organization;
	}

	/**
	 * Get last uuid from build.
	 *
	 * @since  1.0.0
	 * @return string With last uuid.
	 */
	public function get_last_uuid_build() {

		$token = Authentication::get_token();

		$url_request = Config::get_config( 'path' ) . "organizations/{$this->organization}/projects/{$this->project}/builds";
		$args        = [
			'headers' => [
				'Authorization' => "Bearer {$token}",
			],
		];

		$request = Request::do_request( $args, $url_request, 'GET' );

		if ( $request === false ) {
			return false;
		}

		$builds = json_decode( $request );
		return $builds->builds[0]->uuid;
	}

	/**
	 * Restart Build.
	 *
	 * @since         1.0.0
	 * @return string With success or error message.
	 */
	public function restart_build( $uuid ) {

		if ( empty( $uuid ) ) {
			return false;
		}

		$token       = Authentication::get_token();
		$url_request = Config::get_config( 'path' ) . "organizations/{$this->organization}/projects/{$this->project}/builds/{$uuid}/restart";
		$args        = [
			'headers' => [
				'Authorization' => "Bearer {$token}",
			],
		];

		$request = Request::do_request( $args, $url_request );

		if ( $request === false ) {
			echo 'Error on restart build';
			exit;
		}

		echo 'Build restarted with success';
	}
}
