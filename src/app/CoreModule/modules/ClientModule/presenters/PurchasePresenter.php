<?php

namespace ClientModule;

use Client\Components\Grids\IPurchaseGridFactory;

/**
 * @author Petr Blazicek 2017
 */
class PurchasePresenter extends BasePresenter
{

	/** @var  IPurchaseGridFactory @inject */
	public $purchaseGridFactory;


	// factories

	protected function createComponentPurchaseGrid()
	{
		return $this->purchaseGridFactory->create();
	}


	// actions

	public function actionReceive( $data )
	{
	}
}