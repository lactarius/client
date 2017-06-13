<?php

namespace ClientModule;

use Client\Components\Forms\IShopFormFactory;
use Client\Components\Grids\ICommodityGridFactory;
use Client\Model\Shop;

/**
 * @author Petr Blazicek 2017
 */
class ShopPresenter extends BasePresenter
{

	/** @var  IShopFormFactory @inject */
	public $shopFormFactory;

	/** @var  ICommodityGridFactory @inject */
	public $commodityGridFactory;

	// factories


	/**
	 * @return \Client\Components\Forms\ShopForm
	 */
	protected function createComponentShopForm()
	{
		$control = $this->shopFormFactory->create();
		$control->onSave[] = function ( Shop $shop ) {
			$this->flashMessage( 'Shop ' . $shop->getName() . ' was successfully saved.' );
			$this->redirect( 'Default:' );
		};

		return $control;
	}


	/**
	 * @return \Client\Components\Grids\CommodityGrid
	 */
	protected function createComponentCommodityGrid()
	{
		return $this->commodityGridFactory->create();
	}


	// actions

	public function actionEdit( $id = NULL )
	{
		$this[ 'shopForm' ]->setId( $id );
	}


	public function actionCommodity( $id = NULL )
	{
		$this[ 'commodityGrid' ]->setId( $id );
	}
}