<?php

namespace ClientModule;

use Client\Components\Forms\IClientFormFactory;
use Client\Components\Grids\IClientACGridFactory;
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
			$this->redirect( 'Customer:' );
		};

		return $control;
	}


	// actions

	public function actionEdit( $id = NULL )
	{
	}


	public function renderEdit( $id = NULL )
	{
	}


	public function renderCard()
	{
		$this->template->title = 'Cards';
		$this->template->width = 4;
	}
}