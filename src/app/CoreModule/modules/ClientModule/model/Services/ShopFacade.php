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


	/**
	 * Create / update Commodity
	 *
	 * @param $data
	 * @param bool $write
	 * @return Commodity|mixed
	 */
	public function saveCommodity( $data, $write = FALSE )
	{
		$commodity = $this->prepareEntity( Commodity::class, $data );
		$commodity = ShopService::saveCommodity( $commodity, $data );
		$this->saveAll( $commodity, $write );

		return $commodity;
	}


	/**
	 * Create blank Commodity
	 *
	 * @return Commodity|null
	 */
	public function newCommodity()
	{
		if ( $this->commodityRepo->findOneBy( [ 'name' => '@@@@@' ] ) ) return NULL;
		$parents = $this->commodityRepo->findPairs( [], 'name', [ 'id' => 'ASC', 'name' => 'ASC' ] );

		$commodity = new Commodity();
		$commodity->setName( '@@@@@' );
		$commodity->setInfo( '@@@@@' );

		$this->saveAll( $commodity, TRUE );

		return $commodity;
	}


	/**
	 * Remove Commodity
	 *
	 * @param $mixed
	 * @param bool $write
	 * @return bool
	 */
	public function removeCommodity( $mixed, $write = FALSE )
	{
		$commodity = $mixed instanceof Commodity ? $mixed : $this->commodityRepo->find( $mixed );
		$id = $commodity->id;
		$this->em->remove( $commodity );
		if ( $write ) $this->flush();
		return !$this->commodityRepo->find( $id );
	}
}