<?php

namespace ACGrid;

use Client\Model\ShopFacade;

/**
 * @author Petr Blazicek 2017
 */
class EditForm extends FormContainer
{

	/** @var ShopFacade */
	private $facade;


	public function __construct( DataGrid $grid )
	{
		parent::__construct( $grid );
		$this->facade = $grid->getFacade();

		$this->addText( 'name' );
		$this->addText( 'info' );
		$this->addSelect( 'parent', NULL, $this->facade->parentSelect( $this->grid->getId() ) );

		if ( $this->grid->getId() )
			$this->setDefaults( $this->grid->getFacade()->restoreCommodity( $this->grid->getId() ) );
	}
}