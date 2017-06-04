<?php

namespace Core\Components\Forms;

use Nette\ComponentModel\IContainer;
use Nette\Forms\Container;
use Nette\Forms\Form;

/**
 * Base form container for composing forms
 *
 * @author Petr Blazicek 2017
 */
abstract class BaseContainer extends Container
{

	public function __construct( IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
	}


	protected function attached( $obj )
	{
		parent::attached( $obj );
		if ( $obj instanceof Form ) {
			$this->currentGroup = $this->form->currentGroup;
			$this->configure();
		}
	}


	abstract protected function configure();
}