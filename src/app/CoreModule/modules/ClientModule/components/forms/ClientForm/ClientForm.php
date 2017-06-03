<?php

namespace Client\Components\Forms;

use Client\Model\ClientFacade;
use Core\Components\Forms\BSUIForm;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IContainer;

/**
 * @author Petr Blazicek 2017
 */
class ClientForm extends Control
{

	const PASSWORD_MIN_LENGTH = 6;

	private $facade;


	/**
	 * ClientForm constructor.
	 * @param ClientFacade $facade
	 * @param IContainer|NULL $parent
	 * @param null $name
	 */
	public function __construct( ClientFacade $facade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->facade = $facade;
	}


	/**
	 * @return BSUIForm
	 */
	protected function createComponentForm()
	{
		$form = new BSUIForm();

		$form->addText( 'name', 'Name:' )
			->addRule( Form::FILLED, 'Enter client\'s name, please.' )
			->setAttribute( 'autofocus' );

		$form->addText( 'surname', 'Surname:' )
			->addRule( Form::FILLED, 'Enter client\'s surname, please.' );

		$form->addText( 'email', 'E-mail:' )
			->addRule( Form::FILLED, 'E-mail is mandatory!' )
			->addRule( Form::EMAIL, 'Todle je e-mail? To je hnus a né e-mail!' );

		$form->addPassword( 'password', 'Password:' )
			->addRule( Form::FILLED, 'Some password, please.' )
			->addRule( Form::MIN_LENGTH, 'The password must have min. %d characters.', self::PASSWORD_MIN_LENGTH );

		$form->addSubmit( 'save', 'Save' );
		$form->onSuccess[] = $this->process;

		return $form;
	}


	/**
	 * @param Form $form
	 */
	public function process( Form $form )
	{
		$data = $form->getValues( TRUE );

		print_r( $data );
		die;
	}


	/**
	 * Renders component
	 */
	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/../CommonForm.latte' );
		$template->width = 2;
		$template->title = 'Client';
		$template->render();
	}
}