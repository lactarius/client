<?php

namespace Client\Components\Forms;

use Client\Model\PurchaseFacade;
use Core\Components\Forms\BaseControlForm;
use Core\Components\Forms\BSUIForm;
use Nette\ComponentModel\IContainer;

/**
 * @author Petr Blazicek 2017
 */
class PurchaseForm extends BaseControlForm
{

	public function __construct( PurchaseFacade $facade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->facade = $facade;
	}

	protected function createComponentForm(){
		$form=new BSUIForm();

		$form->addSelect('client','Client:')
			->setPrompt('Select client, please.');

		$form->addText('note','Note:');
	}
}