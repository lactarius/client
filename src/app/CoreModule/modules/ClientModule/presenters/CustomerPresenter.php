<?php

namespace ClientModule;

use Client\Components\Forms\IClientFormFactory;

/**
 * @author Petr Blazicek 2017
 */
class CustomerPresenter extends BasePresenter
{

	/** @var  IClientFormFactory @inject */
	public $clientFormFactory;

	// factories


	/**
	 * @return \Client\Components\Forms\ClientForm
	 */
	protected function createComponentClientForm()
	{
		$control = $this->clientFormFactory->create();
		return $control;
	}


	// actions

	public function actionDefault()
	{
	}


	public function renderDefault()
	{
	}


	public function actionClient( $id = NULL )
	{
	}


	public function renderClient( $id = NULL )
	{
	}
}