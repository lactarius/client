<?php

namespace Client\Model;

use Core\Model\BaseFacade;
use Kdyby\Doctrine\EntityManager;

/**
 * @author Petr Blazicek 2017
 */
class ShopFacade extends BaseFacade
{

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $commodityRepo;


	/**
	 * ShopFacade constructor.
	 * @param EntityManager $em
	 */
	public function __construct( EntityManager $em )
	{
		parent::__construct( $em );
		$this->repo = $em->getRepository( Shop::class );
		$this->commodityRepo = $em->getRepository( Commodity::class );
	}


	/**
	 * @return \Kdyby\Doctrine\EntityRepository
	 */
	public function getCommodityRepo()
	{
		return $this->commodityRepo;
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

		return $shop ? ShopService::restoreShop( $shop ) : NULL;
	}


	public function saveCommodity( $data )
	{
		$commodity = $this->prepareEntity( Commodity::class, $data );
		$commodity = ShopService::saveCommodity( $commodity, $data );
		$this->saveAll( $commodity );

		return $commodity;
	}
}