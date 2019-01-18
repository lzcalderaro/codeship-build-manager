<?php

namespace LzCalderaro;

use LzCalderaro\Authentication;

class CodeshipAPI {

	/**
	 * Restart last build.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function restart_last_build() {

		$organization = Config::get_config( 'organization' );
		$project      = Config::get_config( 'project' );

		$build = new Builds( $organization, $project );
		$uuid  = $build->get_last_uuid_build();
		$build->restart_build( $uuid );
	}

	/**
	 * List all projects and organizations UUIDS.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function list_projects_and_organizations() {

		if ( file_exists( Config::get_config( 'organization_file' ) ) ) {
			$organizations = file_get_contents( Config::get_config( 'organization_file' ) );
		}

		if ( empty( $organizations ) ) {
			$organizations = Authentication::login()->organizations;
		}

		if ( ! is_array( $organizations ) ) {
			$organizations = json_decode( $organizations );
		}

		foreach ( $organizations as $organization ) {

			echo "- Organization name: {$organization->name}\n";
			echo "- Organization uuid: {$organization->uuid}\n";

			$projects = $this->get_projects( $organization->uuid );

			if ( $projects === false ) {
				echo "No projects \n";
				continue;
			}

			foreach ( $projects->projects as $project ) {
				echo "--- Project name: {$project->name}\n";
				echo "--- Project uuid: {$project->uuid}\n";
			}

		}
	}

	/**
	 * Get Projects.
	 *
	 * @since  1.0.0
	 * @param  string $organization_uuid
	 * @return object|bool With projects list or false.
	 */
	private function get_projects( $organization_uuid ) {

		$token = Authentication::get_token();

		$url_request = Config::get_config( 'path' ) . "organizations/{$organization_uuid}/projects";
		$args        = [
			'headers' => [
				'Authorization' => "Bearer {$token}",
			],
		];

		$request = Request::do_request( $args, $url_request, 'GET' );

		if ( $request === false ) {
			return false;
		}

		return json_decode( $request );

	}
}
