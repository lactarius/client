<?php

namespace Client\Components\Grids;

use Client\Model\ClientFacade;
use Nette\Http\Session;
use TwiGrid\DataGrid;

/**
 * @author Petr Blazicek 2017
 */
class CardGrid extends DataGrid
{

	private $facade;


	public function __construct( ClientFacade $facade, Session $s )
	{
		parent::__construct( $s );
		$this->facade = $facade;
	}


	protected function build()
	{
		parent::build();

		$this->setTemplateFile(__DIR__.'/CardGrid.latte');

		$this->addColumn( 'number', 'Number' );
		$this->addColumn( 'type', 'Type' );

		$this->setPrimaryKey( 'id' );

		$this->setDataLoader( function () {
			return $this->facade->getRepo()->related( 'cards' )->findBy( [], [ 'number' => 'ASC' ] );
		} );
	}
}