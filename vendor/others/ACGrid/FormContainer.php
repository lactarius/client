<?php

namespace ACGrid;

use Nette\Forms\Container;

/**
 * @author Petr Blazicek 2017
 */
class FormContainer extends Container
{

	/** @var DataGrid */
	protected $grid;


	public function __construct( DataGrid $grid )
	{
		$this->grid = $grid;

		$this->addHidden( 'id' );
	}
}