<?php

namespace Ninja;

class EntryPoint {

	private $route;
	private $method;
	private $routes;

	public function __construct( string $route, string $method, \Ninja\Routes $routes ){

		$this->route  = $route;
		$this->method = $method;
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

		$routes 		= $this->routes->getRoutes();
		$authentication = $this->routes->getAuthentication();

		if( isset( $routes[$this->route]['login'] ) && isset( $routes[$this->route]['login'] ) && !$authentication->isloggedIn() ){

			header( 'location: /login/error');

		} else if ( isset( $routes[$this->route]['permissions'] ) && !$this->routes->checkPermission( $routes[$this->route]['permissions'] ) ) {

			header('location: /login/permissionerror');

		} else {

			$controller = $routes[$this->route][$this->method]['controller'];
			$action 	= $routes[$this->route][$this->method]['action'];

			$page  = $controller->$action();

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

			echo $this->loadTemplate( 'layout.html.php', [ 'loggedIn' => $authentication->isLoggedIn(), 'output' => $output, 'title' => $title ] );

		}

	}

}