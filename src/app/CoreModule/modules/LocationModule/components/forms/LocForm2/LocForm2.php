<?php

namespace Location\Components\Forms;

use Core\Components\Forms\BSUIForm;
use Location\Model\LocationFacade;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IContainer;

/**
 * @author Petr Blazicek 2017
 */
class LocForm2 extends Control
{

	/** @var  LocationFacade */
	private $facade;


	/**
	 * LocForm2 constructor.
	 * @param LocationFacade $clientFacade
	 * @param IContainer|NULL $parent
	 * @param null $name
	 */
	public function __construct( LocationFacade $clientFacade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->facade = $clientFacade;
	}


	protected function createComponentForm()
	{
		$form = new BSUIForm();

		$form->addText( 'name', 'Name:' );

		$form->addText( 'surname', 'Surname:' );

		$form[ 'address' ] = new LocContainer();

		$form->addSubmit( 'save', 'Save' );
		$form->onSuccess[] = $this->process;

		return $form;
	}


	public function process( Form $form )
	{
		$data = $form->getValues( TRUE );

		print_r( $data );
		die;
	}


	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/LocForm2.latte' );
		$template->render();
	}
}