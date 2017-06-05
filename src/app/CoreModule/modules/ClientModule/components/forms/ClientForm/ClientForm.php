<?php

namespace Client\Components\Forms;

use Client\Model\Card;
use Client\Model\ClientFacade;
use Core\Components\Forms\BSUIForm;
use Location\Components\Forms\LocContainer;
use Location\Model\LocationFacade;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IContainer;
use Nette\Utils\Html;

/**
 * @author Petr Blazicek 2017
 */
class ClientForm extends Control
{

	const PASSWORD_MIN_LENGTH = 6;

	private $password = FALSE;

	/** @var ClientFacade */
	private $clientFacade;

	/** @var LocationFacade */
	private $locationFacade;

	public $onSave;


	/**
	 * ClientForm constructor.
	 * @param ClientFacade $clientFacade
	 * @param IContainer|NULL $parent
	 * @param null $name
	 */
	public function __construct( ClientFacade $clientFacade, LocationFacade $locationFacade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->clientFacade = $clientFacade;
		$this->locationFacade = $locationFacade;
	}


	/**
	 * @return BSUIForm
	 */
	protected function createComponentForm()
	{
		$form = new BSUIForm();

		$form->addHidden( 'id' );

		$form->addText( 'name', 'Name:' )
			->addRule( Form::FILLED, 'Enter client\'s name, please.' )
			->setAttribute( 'autofocus' );

		$form->addText( 'surname', 'Surname:' )
			->addRule( Form::FILLED, 'Enter client\'s surname, please.' );

		$form->addText( 'email', 'E-mail:' )
			->addRule( Form::FILLED, 'E-mail is mandatory!' )
			->addRule( Form::EMAIL, 'Todle je e-mail? To je hnus a né e-mail!' );

		if ( $this->password ) {

			$form->addPassword( 'password', 'Password:' )
				->addRule( Form::FILLED, 'Some password, please.' )
				->addRule( Form::MIN_LENGTH, 'The password must have min. %d characters.', self::PASSWORD_MIN_LENGTH );

			$form->addPassword( 'password2', 'Confirm password' )
				->addRule( Form::EQUAL, 'Passwords are not equal', $form[ 'password' ] );
		}

		$form[ 'address' ] = new LocContainer();

		$form[ 'profile' ] = new ProfileContainer();

		$form->addSelect( 'card', 'Card', Card::TYPE )
			->setPrompt( 'Select card type' );

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

		$client = $this->clientFacade->saveClient( $data );

		if ( $client ) {

			$address = $this->locationFacade->getAddress( $data[ 'address' ] );
			$profile = $this->clientFacade->saveProfile( $data[ 'profile' ] );
			$card = $this->clientFacade->generateCard( $data[ 'card' ] );
			if ( $address ) $client->setAddress( $address );
			if ( $profile ) $client->setProfile( $profile );
			if ( $card ) $client->addCard( $card );

			$this->clientFacade->flush();

			$this->onSave( $client );
		}
	}


	/**
	 * Renders component
	 */
	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/../CommonMapForm.latte' );
		$template->formWidth = 4;
		$template->mapWidth = 6;
		$template->title = 'Client';
		$template->render();
	}
}