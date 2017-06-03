<?php

namespace ClientModule;

use Client\Components\Forms\IClientFormFactory;

/**
 * Class DefaultPresenter
 *
 * @author Petr Blazicek 2017
 */
class DefaultPresenter extends BasePresenter
{

	/** @var  IClientFormFactory @inject */
	public $clientFormFactory;


	/**
	 * @return \Client\Components\Forms\ClientForm
	 */
	protected function createComponentClientForm()
	{
		$control = $this->clientFormFactory->create();
		return $control;
	}
}
