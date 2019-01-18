<?php

// Composer autoload
if ( file_exists( './vendor/autoload.php' ) ) {
	require_once './vendor/autoload.php';
}

// Load config
$dotenv = new Dotenv\Dotenv( __DIR__ );
$dotenv->load();


echo "Options: \n";
echo "1 - List all Organizations and projects\n";
echo "2 - Restart last Build\n";
echo 'Type your option: ';

$handle  = fopen( 'php://stdin', 'r' );
$chooice = trim( fgets( $handle ) );
$api     = new LzCalderaro\CodeshipAPI();

switch ( $chooice ) {
	case '1':
		$api->list_projects_and_organizations();
	break;

	case '2':
		$api->restart_last_build();
	break;

	default:
		echo 'Invalid Option';
	break;
}

fclose( $handle );
