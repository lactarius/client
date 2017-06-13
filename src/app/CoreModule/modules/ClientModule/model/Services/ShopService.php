<?php

namespace Client\Model;

/**
 * @author Petr Blazicek 2017
 */
class ShopService
{

	/**
	 * @param Shop $shop
	 * @param $data
	 * @return Shop
	 */
	public static function saveShop( Shop $shop, $data )
	{
		if ( !empty( $data[ 'name' ] ) ) $shop->setName( $data[ 'name' ] );
		if ( isset( $data[ 'url' ] ) ) $shop->setUrl( $data[ 'url' ] );
		if ( isset( $data[ 'info' ] ) ) $shop->setInfo( $data[ 'info' ] );

		return $shop;
	}


	/**
	 * @param Shop $shop
	 * @return array
	 */
	public static function restoreShop( Shop $shop )
	{
		return [
			'id'   => $shop->getId(),
			'name' => $shop->getName(),
			'url'  => $shop->getUrl(),
			'info' => $shop->getInfo(),
		];
	}


	/**
	 * @param Commodity $commodity
	 * @param $data
	 * @return Commodity
	 */
	public static function saveCommodity( Commodity $commodity, $data )
	{
		if ( !empty( $data[ 'name' ] ) ) $commodity->setName( $data[ 'name' ] );
		if ( isset( $data[ 'info' ] ) ) $commodity->setInfo( $data[ 'info' ] );
		if ( isset( $data[ 'parent' ] ) ) $commodity->setParent( $data[ 'parent' ] );

		return $commodity;
	}


	/**
	 * @param Commodity $commodity
	 * @return array
	 */
	public static function restoreCommodity( Commodity $commodity )
	{
		return [
			'id'     => $commodity->getId(),
			'name'   => $commodity->getName(),
			'info'   => $commodity->getInfo(),
			'parent' => $commodity->getParent() ? $commodity->getParent()->getId() : NULL,
		];
	}
}