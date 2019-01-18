<?php

namespace LzCalderaro;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Request {


	/**
	 * Do request.
	 *
	 * @since  1.0.0
	 * @param  array $args
	 * @param  string $url_request
	 * @param  string $method
	 * @return object|bool
	 */
	public static function do_request( $args, $url_request, $method = 'POST' ) {

		if ( empty( $args ) || empty( $url_request ) ) {
			return false;
		}

		try {

			$client   = new \GuzzleHttp\Client();
			$response = $client->request( $method, $url_request, $args );

			return $response->getBody();

		} catch ( RequestException $e ) {

			echo Psr7\str( $e->getRequest() );

			if ( $e->hasResponse() ) {
				echo Psr7\str( $e->getResponse() );
			}

			return false;
		}

	}
}
