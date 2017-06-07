<?php

namespace Client\Model;

use Core\Model\BaseFacade;
use Kdyby\Doctrine\EntityManager;

/**
 * @author Petr Blazicek 2017
 */
class PurchaseFacade extends BaseFacade
{

	public function __construct( EntityManager $em )
	{
		parent::__construct( $em );
		$this->repo=$em->getRepository(Purchase::class);
	}


	public function top10(){
		$itemRepo=$this->repo->related('items');

		$clients=$this->repo->

		$qb=$itemRepo->createQueryBuilder('i');

		$qb->select('SUM(i.unitPrice')
	}
}