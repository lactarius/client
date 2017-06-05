<?php

namespace Client\Components\Grids;

use Client\Model\ClientFacade;
use Nette;
use TwiGrid\DataGrid;

/**
 * @author Petr Blazicek 2017
 */
class ClientGrid extends DataGrid
{

	/** @var ClientFacade */
	private $facade;


	public function __construct( ClientFacade $facade, Nette\Http\Session $s )
	{
		parent::__construct( $s );
		$this->facade = $facade;
	}


	protected function build()
	{
		parent::build();

		$this->setTemplateFile( __DIR__ . '/ClientGrid.latte' );

		$this->addColumn( 'name', 'Name' )->setSortable();
		$this->addColumn( 'surname', 'Surname' )->setSortable();
		$this->addColumn( 'email', 'E-mail' );

		$this->setPrimaryKey( 'id' );

		$this->setDataLoader( $this->dataLoader );
	}


	public function dataLoader( ClientGrid $grid, array $filters, array $order )
	{
		$qb = $this->facade->getRepo()->createQueryBuilder('c');

		// order
		foreach ( $order as $column => $dir ) {
			$qb->orderBy( "c.$column", $dir > 0 ? 'DESC' : 'ASC' );
		}

		return $qb->getQuery()->getResult();
	}
}