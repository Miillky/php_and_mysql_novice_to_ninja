<?php

namespace Ijdb\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Joke {

	private $authorsTable;
	private $jokesTable;
	private $categoriesTable;
	private $authentication;

	public function __construct( DatabaseTable $jokesTable, DatabaseTable $authorsTable, DatabaseTable $categoriesTable, Authentication $authentication ){

		$this->jokesTable 	   = $jokesTable;
		$this->authorsTable    = $authorsTable;
		$this->categoriesTable = $categoriesTable;
		$this->authentication  = $authentication;
	}

	public function list(){

		$jokes = $this->jokesTable->findAll();

		$title = 'Joke list';

		$totalJokes = $this->jokesTable->total();

		$author = $this->authentication->getUser();

		return [
			'template'  => 'jokes.html.php',
			'title' 	=> $title,
			'variables' => [
				'totalJokes' => $totalJokes,
				'jokes'		 => $jokes,
				'userId' 	 => $author->id ?? null
			]
		];
	}

	public function home(){

		$title = 'Internet Joke Database';

		return [ 'template' => 'home.html.php', 'title' => $title ];
	}

	public function delete(){

		$author = $this->authentication->getUSer();

		if( isset( $_GET['id'] ) ){

			$joke = $this->jokesTable->findById( $_GET['id'] );

			if( $joke->authorId != $author->id ){
				return;
			}

		}

		$this->jokesTable->delete( $_POST['id'] );

		header('location: /joke/list');

	}

	public function saveEdit(){

		$author = $this->authentication->getUser();

		$joke 			  = $_POST['joke'];
		$joke['jokedate'] = new \DateTime();

		$jokeEntity = $author->addJoke( $joke );

		foreach( $_POST['category'] as $categoryId ){
			$jokeEntity->addCategory( $categoryId );
		}

		header('location: /joke/list');

	}

	public function edit(){

		$author 	= $this->authentication->getUser();
		$categories = $this->categoriesTable->findAll();

		if ( isset( $_GET['id'] ) ) {

			$joke = $this->jokesTable->findById( $_GET['id'] );

		}

		return [
			'template'  => 'editjoke.html.php',
			'title'     => 'Edit joke',
			'variables' => [
								'joke' 	 	 => $joke ?? null,
								'userId' 	 => $author->id ?? null,
								'categories' => $categories
						   ]
		];

	}
}
