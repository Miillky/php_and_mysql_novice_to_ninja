<?php

namespace Ninja;

class EntryPoint {

	private $route;
	private $routes;

	public function __construct( $route, $routes ){

		$this->route  = $route;
		$this->routes = $routes;
		$this->checkUrl();

	}

	private function checkUrl(){

		if( $this->route !== strtolower( $this->route ) ){

			http_response_code(301);
			header( 'loaction: ' . strtolower( $this->route ) );

		}

	}

	private function loadTemplate( $templateFileName, $variables = [] ){

		/*** Creates a variable from array key ***/
		extract( $variables );

		ob_start();

		include __DIR__ . '/../../templates/' . $templateFileName;

		return ob_get_clean();

	}

	public function run(){

		$page = $this->routes->callAction( $this->route );

		$title = $page['title'];

		/***
		* If varible alredy exists it wont overwrite with $page['variable'] because of function scope.
		* Thats why we use loadTemplate function, to not overwrite global variables with $page['variables']
		* key that is then extracted from array key into a variable
		***/
		if( isset( $page['variables'] ) ){

			$output = $this->loadTemplate( $page['template'], $page['variables'] );

		}	else {

			$output = $this->loadTemplate( $page['template'] );

		}

		include __DIR__ . '/../../templates/layout.html.php';

	}
}