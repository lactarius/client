<?php

namespace Client\Components\Grids;

use Client\Model\PurchaseFacade;
use Nette\Application\UI\Control;
use Nette\ComponentModel\IContainer;
use Nextras\Datagrid\Datagrid;

/**
 * @author Petr Blazicek 2017
 */
class PurchaseGrid extends Control
{

	private $facade;


	public function __construct( PurchaseFacade $facade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->facade = $facade;
	}


	protected function createComponentGrid()
	{
		$grid = new Datagrid();
		$grid->addColumn( 'client', 'Client' );
		$grid->addColumn( 'card', 'Card' );
		$grid->addColumn( 'shop', 'Shop' );
		$grid->addColumn( 'note', 'Note' );

		$grid->setRowPrimaryKey( 'id' );
	}


	public function dataSource( $filter, $order )
	{
		$qb = $this->facade->getRepo()->createQueryBuilder( 'p' );

		return $qb->getQuery()->getResult();
	}

	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/../commonGrid.latte' );
		$template->width = 6;
		$template->title = 'Commodity';

		$template->render();
	}
}