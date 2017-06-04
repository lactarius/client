<?php

namespace ClientModule;

use Client\Components\Forms\IClientFormFactory;
use Client\Model\Client;

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
		$control->onSave[] = function ( Client $client ) {
			$this->flashMessage( 'Client ' . $client->getFullname() . ' was successfully saved.' );
			$this->redirect( 'Default:' );
		};

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