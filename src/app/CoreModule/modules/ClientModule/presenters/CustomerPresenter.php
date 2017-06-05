<?php

namespace ClientModule;

use Client\Components\Forms\IClientFormFactory;
use Client\Components\Grids\ICardGridFactory;
use Client\Model\Client;

/**
 * @author Petr Blazicek 2017
 */
class CustomerPresenter extends BasePresenter
{

	/** @var  IClientFormFactory @inject */
	public $clientFormFactory;

	/** @var  ICardGridFactory @inject */
	public $cardGridFactory;

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


	/**
	 * @return \Client\Components\Grids\CardGrid
	 */
	protected function createComponentCardGrid()
	{
		return $this->cardGridFactory->create();
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


	public function renderCard()
	{
		$this->template->title = 'Cards';
		$this->template->width = 4;
	}
}