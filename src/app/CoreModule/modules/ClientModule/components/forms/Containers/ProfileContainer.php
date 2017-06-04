<?php

namespace Client\Components\Forms;

use Client\Model\Profile;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Container;
use Nette\Forms\Form;

/**
 * @author Petr Blazicek 2017
 */
class ProfileContainer extends Container
{

	public function __construct( IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );

		$this->addRadioList( 'sex', 'Gender:', [ Profile::SEX_MALE => 'Male', Profile::SEX_FEMALE => 'Female' ] )
			->addRule( Form::FILLED, 'Some gender, please.' );

		$this->addText( 'phone', 'Phone:' );

		$this->addText( 'photo', 'Photo' );
	}
}