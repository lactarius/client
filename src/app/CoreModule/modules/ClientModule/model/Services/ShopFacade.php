<?php

namespace Client\Model;

use Core\Model\BaseFacade;
use Kdyby\Doctrine\EntityManager;

/**
 * @author Petr Blazicek 2017
 */
class ShopFacade extends BaseFacade
{

	/**
	 * ShopFacade constructor.
	 * @param EntityManager $em
	 */
	public function __construct( EntityManager $em )
	{
		parent::__construct( $em );
		$this->repo = $em->getRepository( Shop::class );
	}


	/**
	 * Save Shop data
	 *
	 * @param $data
	 * @return Shop|mixed
	 */
	public function saveShop( $data )
	{
		$shop = $this->prepareEntity( Shop::class, $data );
		$shop = ShopService::saveShop( $shop, $data );
		$this->saveAll( $shop );

		return $shop;
	}


	/**
	 * Return Shop data
	 *
	 * @param $mixed
	 * @return array
	 */
	public function restoreShop( $mixed )
	{
		// parameter can be Entity or ID
		$shop = $mixed instanceof Shop ? $mixed : $this->repo->find( $mixed );

		return ShopService::restoreShop($shop);
	}
}