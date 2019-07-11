<?php

try {

	$pdo 	= new PDO( 'mysql:host=localhost;dbname=ijdb;charset=utf8', 'ijdbuser', '25011990' );
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = 'SELECT `joketext` FROM `joke`';
	$result = $pdo->query($sql);

	foreach( $result as $row )
		$jokes[] = $row['joketext'];

	$title = 'Joke list';

	ob_start();

	$output = '';

	include __DIR__ . '/../templates/jokes.html.php';

	$output = ob_get_clean();

} catch ( PDOException $e) {

	$title = 'An error has occurred';

	$error = 'Databese error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();

}

include __DIR__ . '/../templates/layout.html.php';