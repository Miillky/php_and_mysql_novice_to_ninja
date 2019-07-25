<?php

try {

	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../classes/DatabaseTable.php';
	include __DIR__ . '/../controllers/RegisterController.php';

	$jokesTable   		= new DatabaseTable( $pdo, 'joke', 'id' );
	$authorsTable 		= new DatabaseTable( $pdo, 'author', 'id' );
	$registerController = new RegisterController( $authorsTable );

	$action = $_GET['action'] ?? 'home';

	if( $action == strtolower( $action ) ){

		$page = $registerController->$action();

	} else {

		http_response_code( 301 );
		header( 'location: index.php?action=' . strtolower( $action ) );

	}

	$title = $page['title'];

	if( isset( $page['variables'] ) ){

		$output = loadTemplate( $page['template'], $page['variables'] );

	} else {

		$output = loadTemplate( $page['template'] );

	}

} catch ( PDOException $e ){

	$title = 'An error has occurred';

	$output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();

}

include __DIR__ . '/../templates/layout.html.php';