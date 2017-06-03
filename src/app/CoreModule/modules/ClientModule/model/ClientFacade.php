<?php

namespace Client\Model;

use Core\Model\BaseFacade;
use Kdyby\Doctrine\EntityManager;

/**
 * @author Petr Blazicek 2017
 */
class ClientFacade extends BaseFacade
{

	/**
	 * ClientFacade constructor.
	 * @param EntityManager $em
	 */
	public function __construct( EntityManager $em )
	{
		parent::__construct( $em );
	}
}