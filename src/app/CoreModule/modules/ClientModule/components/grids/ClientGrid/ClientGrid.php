<?php

namespace Client\Components\Grids;

use Client\Model\ClientFacade;
use Nette\Application\UI\Control;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Container;
use Nextras\Datagrid\Datagrid;

/**
 * @author Petr Blazicek 2017
 */
class ClientGrid extends Control
{

	/** @var ClientFacade */
	private $facade;


	public function __construct( ClientFacade $facade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->facade = $facade;
	}


	protected function createComponentGrid()
	{
		$grid = new Datagrid();
		$grid->addColumn( 'name', 'Name' )->enableSort();
		$grid->addColumn( 'surname', 'Surname' )->enableSort( Datagrid::ORDER_ASC );
		$grid->addColumn( 'email', 'E-mail' );

		$grid->addCellsTemplate( __DIR__ . '/../bs3.latte' );
		$grid->addCellsTemplate( __DIR__ . '/def.latte' );

		$grid->setFilterFormFactory( $this->filterFormFactory );

		$grid->setDataSourceCallback( $this->dataSource );

		return $grid;
	}


	public function filterFormFactory()
	{
		$form = new Container();

		$form->addText( 'name' );
		$form->addText( 'surname' );

		return $form;
	}


	public function dataSource( $filter, $order )
	{
		$qb = $this->facade->getRepo()->createQueryBuilder( 'c' );

		foreach ( $filter as $column => $value ) {
			$qb->andWhere( "c.$column LIKE :$column" )
				->setParameter( "$column", "$value%" );
		}

		if ( $order ) {
			$col = $order[ 0 ];
			$dir = $order[ 1 ];
			$qb->orderBy( "c.$col", $dir );
		}

		return $qb->getQuery()->getResult();
	}


	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/../commonGrid.latte' );
		$template->width = 6;
		$template->title = 'Client';

		$template->render();
	}
}