<?php
ini_set('display_errors', 1);

try {

	$jokesTable   = new \Ninja\DatabaseTable( $pdo, 'joke', 'id' );
	$authorsTable = new \Ninja\DatabaseTable( $pdo, 'author', 'id' );

	$result = $jokesTable->findAll();

	$jokes = [];

	foreach( $result as $joke ){
		$author = $authorsTable->findById( $joke['authorId'] );
		$jokes[] = [
			'id' 		=> $joke['id'],
			'joketext'	=> $joke['joketext'],
			'jokedate' => $joke['jokedate'],
			'name'		=> $author['name'],
			'email'		=> $author['email']
		];
	}



	$title = 'Joke list';

	$totalJokes = $jokesTable->total();

	ob_start();

	$output = '';

	include __DIR__ . '/../templates/jokes.html.php';

	$output = ob_get_clean();

} catch ( \PDOException $e) {

	$title = 'An error has occurred';

	$error = 'Databese error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();

}

include __DIR__ . '/../templates/layout.html.php';