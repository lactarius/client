<?php

namespace ClientModule\DI;

use Flame\Modules\Providers\IRouterProvider;
use Kdyby\Doctrine\DI\IEntityProvider;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\DI\CompilerExtension;

/**
 * @author Petr Blazicek 2017
 */
class ClientExtension extends CompilerExtension implements IEntityProvider, IRouterProvider
{

	public function getEntityMappings ()
	{
		return [ 'Client\Model' => __DIR__ . '/../model' ];
	}


	public function getRoutesDefinition ()
	{
		$routeList = new RouteList( 'Client' );
		$routeList[] = new Route( 'client/<presenter>/<action>[/<id>]',
			[
				'presenter' => 'Default',
				'action'    => 'default',
				'id'        => NULL,
			] );

		return $routeList;
	}
}