<?php

namespace Client\Components\Forms;

use Client\Model\ShopFacade;
use Core\Components\Forms\BaseControlForm;
use Core\Components\Forms\BSUIForm;
use Location\Components\Forms\LocContainer;
use Location\Model\LocationFacade;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Form;

/**
 * @author Petr Blazicek 2017
 */
class ShopForm extends BaseControlForm
{

	/** @var LocationFacade */
	private $locationFacade;


	/**
	 * ShopForm constructor.
	 * @param ShopFacade $facade
	 * @param LocationFacade $locationFacade
	 * @param IContainer|NULL $parent
	 * @param null $name
	 */
	public function __construct( ShopFacade $facade, LocationFacade $locationFacade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->facade = $facade;
		$this->locationFacade = $locationFacade;
	}


	protected function createComponentForm()
	{
		$form = new BSUIForm();

		$form->addHidden( 'id' );

		$form->addText( 'name', 'Name:' )
			->addRule( Form::FILLED, 'Please enter the name of the shop.' )
			->setAttribute( 'autofocus' );

		$form->addText( 'url', 'URL:' );

		$form->addTextArea( 'info', 'Info:', 60, 4 );

		$form[ 'address' ] = new LocContainer();

		$form->addSubmit( 'save', 'Save' );
		$form->onSuccess[] = $this->process;

		return $form;
	}


	public function process( Form $form )
	{
		$data = $form->getValues();

		print_r($data);die;
	}


	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/../CommonMapForm.latte' );
		$template->formWidth = 4;
		$template->mapWidth = 6;
		$template->title = 'Shop';

		$template->render();
	}
}