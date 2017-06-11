<?php

namespace Client\Components\Grids;

use ACGrid\DataGrid;
use Client\Model\ShopFacade;
use Nette\Application\UI\Control;
use Nette\ComponentModel\IContainer;

/**
 * @author Petr Blazicek 2017
 */
class CommodityGrid extends Control
{

	/** @var ShopFacade */
	private $facade;


	/**
	 * CommodityGrid constructor.
	 * @param ShopFacade $facade
	 * @param IContainer|NULL $parent
	 * @param null $name
	 */
	public function __construct( ShopFacade $facade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->facade = $facade;
	}


	protected function createComponentGrid()
	{
		$grid = new DataGrid();

		$grid->addColumn( 'name', 'Name' );

		$grid->addColumn( 'info', 'Info' );

		$grid->setDataSource( $this->dataSource );

		$grid->setAddNew( $this->addNew );

		$grid->setRemove( $this->remove );

		return $grid;
	}


	public function dataSource( $filters, $sorts )
	{
		return $this->facade->getCommodityRepo()->findBy( [] );
	}


	public function addNew()
	{
		$commodity = $this->facade->newCommodity();
		$this->flashMessage( 'Proxy Commodity ' . $commodity->name . ' added. Edit it immediatelly!' );
		$this->redirect( 'this' );
	}


	public function remove( $id )
	{
		$commodity = $this->facade->getCommodityRepo()->find( $id );
		$name = $commodity->name;
		$this->facade->removeCommodity( $commodity, TRUE );
		$this->flashMessage( 'Commodity ' . $name . ' removed.' );
		$this->redirect( 'this' );
	}


	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/template.latte' );
		$template->width = 6;
		$template->title = 'Client';

		$template->render();
	}
}