<?php

namespace ACGrid;


/**
 * @author Petr Blazicek 2017
 */
class FilterForm extends FormContainer
{

	public function __construct( DataGrid $grid )
	{
		parent::__construct( $grid );

		$this->addText( 'name' );
		$this->addText( 'info' );

		$this->setDefaults( $this->grid->filtering );
	}
}