<?php

namespace PNAdmin;

use \Nette\Application\Routers\RouteList,
	\Nette\Application\Routers\Route;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
		$router[] = new Route('sign/<action>', 'Sign:default');
		$router[] = new Route('grid-operations', 'Table:gridOperations');
		$router[] = new Route('<table>[/<action>[/<id>]]', 'Table:default');
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}

}
