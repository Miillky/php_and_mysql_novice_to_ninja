<?php
ini_set('display_errors', 1);

function loadTemplate( $templateFileName, $variables = [] ){

	/*** Creates a variable from array key ***/
	extract( $variables );

	ob_start();

	include __DIR__ . '/../templates/' . $templateFileName;

	return ob_get_clean();

}

try {

	include __DIR__ . '/../includes/DatabaseConnection.php';
	include __DIR__ . '/../classes/DatabaseTable.php';

	$jokesTable   		= new DatabaseTable( $pdo, 'joke', 'id' );
	$authorsTable 		= new DatabaseTable( $pdo, 'author', 'id' );

	$action = $_GET['action'] ?? 'home';

	$route = ltrim( strtok( $_SERVER['REQUEST_URI'], '?' ), '/' );

	if( $route == strtolower( $route ) ){

		if( $route === 'joke/list' ){

			include __DIR__ . '/../controllers/JokeController.php';

			$controller = new JokeController( $jokesTable, $authorsTable );

			$page = $controller->list();

		} elseif ( $route === 'joke/home' ){

			include __DIR__ . '/../controllers/JokeController.php';

			$controller = new JokeController( $jokesTable, $authorsTable );

			$page = $controller->home();

		} elseif( $route === 'joke/edit' ){

			include __DIR__ . '/../controllers/JokeController.php';

			$controller = new JokeController( $jokesTable, $authorsTable );

			$page = $controller->edit();

		} elseif( $route === 'joke/delete' ){

			include __DIR__ . '/../controllers/JokeController.php';

			$controller = new JokeController( $jokesTable, $authorsTable );

			$page = $controller->delete();

		} elseif( $route === 'register' ){

			include __DIR__ . '/../controllers/RegisterController.php';

			$controller = new RegisterController( $authorsTable );

			$page = $controller->showForm();

		}

	} else {

		http_response_code(301);
		header( 'location: index.php?route=' . strtolower( $route ) );

	}

	$title 	= $page['title'];

	/***
	* If varible alredy exists it wont overwrite with $page['variable'] because of function scope.
	* Thats why we use loadTemplate function, to not overwrite global variables with $page['variables']
	* key that is then extracted from array key into a variable
	***/
	if( isset( $page['variables'] ) ){

		$output = loadTemplate( $page['template'], $page['variables'] );

	}	else {

		$output = loadTemplate( $page['template'] );

	}

} catch ( PDOException $e ){

	$title  = 'An error has occurred';
	$output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();

}

include  __DIR__ . '/../templates/layout.html.php';