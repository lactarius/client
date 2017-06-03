<?php

namespace Client\Components\Forms;

use Client\Model\ClientFacade;
use Nette\Application\UI\Form;
use Nette\Object;

/**
 * @author Petr Blazicek 2017
 */
class UnitForm extends Object
{
	private $facade;


	/**
	 * UnitForm constructor.
	 * @param ClientFacade $facade
	 */
	public function __construct(ClientFacade $facade)
	{
		$this->facade=$facade;
	}

	public function create(){
		$form=new Form();

		$form->addText('name','Name')
			->setRequired('Enter Unit name, please')
			->setAttribute('autofocus');

		$form->addText('info','Info');

		$form->addSubmit('save','Save');
		$form->onSuccess[]=$this->process;
	}

	public function process(Form $form){

	}
}