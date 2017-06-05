<?php

namespace ClientModule;

use Client\Components\Forms\IShopFormFactory;
use Client\Model\Shop;

/**
 * @author Petr Blazicek 2017
 */
class ShopPresenter extends BasePresenter
{

	/** @var  IShopFormFactory @inject */
	public $shopFormFactory;


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

	// actions
}