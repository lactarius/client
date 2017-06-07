<?php

namespace Client\Components\Grids;

use Client\Model\ShopFacade;
use Nette\Application\UI\Control;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Container;
use Nette\Forms\Form;
use Nextras\Datagrid\Datagrid;

/**
 * @author Petr Blazicek 2017
 */
class CommodityGrid extends Control
{

	/** @var ShopFacade */
	private $facade;


	public function __construct( ShopFacade $facade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->facade = $facade;
	}


	/**
	 * @return Datagrid
	 */
	protected function createComponentGrid()
	{
		$grid = new DataGridAdjust();
		$grid->addColumn( 'name', 'Name' );
		$grid->addColumn( 'info', 'Info' );
		$grid->addColumn( 'parent', 'Parent' );

		$grid->setRowPrimaryKey( 'id' );

		$grid->addCellsTemplate( __DIR__ . '/../bs3.latte' );
		$grid->addCellsTemplate( __DIR__ . '/def.latte' );

		$grid->setNewRecordCallback( $this->newRecord );

		$grid->setDataSourceCallback( $this->dataSource );

		$grid->setEditFormFactory( function ( $row ) {
			$form = new Container();
			$form->addHidden( 'id' );
			$form->addText( 'name', 'Name' )
				->addRule( Form::FILLED, 'Name is arbitrary.' )
				->setAttribute( 'autofocus' );
			$form->addText( 'info', 'Info' );
			$form->addText( 'parent', 'Parent' )
				->setDisabled();

			$form->setDefaults( $row ? $row->restoreData() : NULL );

			return $form;
		} );

		$grid->setEditFormCallback( $this->saveData );

		return $grid;
	}


	/**
	 * @param $filter
	 * @param $order
	 * @return array
	 */
	public function dataSource( $filter, $order )
	{
		$qb = $this->facade->getCommodityRepo()->createQueryBuilder( 'm' );

		return $qb->getQuery()->getResult();
	}


	public function newRecord()
	{
		$this->facade->newCommodity();
	}


	public function saveData( Form $form )
	{
		$data = $form->getValues( TRUE );
		print_r( $data );
		die;
		$this->flashMessage( 'Record saved.' );
		$this->redrawControl( 'flashes' );
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