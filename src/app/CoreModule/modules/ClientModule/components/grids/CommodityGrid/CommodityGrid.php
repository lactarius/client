<?php

namespace Client\Components\Grids;

use ACGrid\DataGrid;
use ACGrid\IDataGrid;
use Client\Model\ShopFacade;
use Kdyby\Doctrine\QueryBuilder;

/**
 * @author Petr Blazicek 2017
 */
class CommodityGrid extends DataGrid implements IDataGrid
{

	/**
	 * CommodityGrid constructor.
	 * @param ShopFacade $facade
	 */
	public function __construct( ShopFacade $facade )
	{
		parent::__construct( $facade );
	}


	/**
	 * Grid builder
	 */
	protected function build()
	{
		$this->addStencil( __DIR__ . '/def.latte' );

		$this->addColumn( 'name' )->sort();
		$this->addColumn( 'info' )->sort();
		$this->addColumn( 'parent', 'Parent' );

		//$this->setRowsPerPage( 7 );

		$this->allowRemoving()->allowAdding();
		$this->setTitle()->setFooter();
	}


	/**
	 * Grid data source
	 *
	 * @param array $filter
	 * @param array $sorting
	 * @return QueryBuilder
	 */
	public function dataSource( $filter, $sorting )
	{
		// create QueryBuilder

		/** @var ShopFacade $facade */
		$facade = $this->facade;
		$qb = $facade->getCommodityRepo()->createQueryBuilder( 'c' );

		// filters
		foreach ( $filter as $col => $value ) {
			if ( $value ) $qb->andWhere( "c.$col LIKE :$col" )
				->setParameter( "$col", "$value%" );
		}

		// sort
		foreach ( $sorting as $col => $dir )
			$qb->addOrderBy( "c.$col", self::DIR[ $dir ] );

		return $qb;
	}


	/**
	 * Filter set / reset
	 *
	 * @param $submit
	 * @param $data
	 */
	public function setFilter( $submit, $data )
	{
		if ( $submit == 'setFilter' ) {

			$this->filtering = $data;
			$this->flashMessage( 'Filter set.' );
		} else {

			$this->filtering = [];
			$this->flashMessage( 'Filter reset.' );
		}

		$this->invalidatePager();
		$this->redrawControl( 'grid' );
	}


	/**
	 * Inline edit form process
	 *
	 * @param $submit
	 * @param $data
	 */
	public function saveRecord( $submit, $data )
	{
		if ( $submit == 'saveRecord' ) {

			$this->dataSnippet[] = $commodity = $this->facade->saveCommodity( $data, TRUE );
			if ( $commodity ) {
				$this->flashMessage( 'Commodity "' . $commodity->getName() . '" was successfully saved.' );
			} else {
				$this->flashMessage( 'Commodity was not saved.', 'error' );
			}
		}

		if ( $this->presenter->isAjax() ) {

			$this->redrawControl( 'stencils' );
			$this->redrawControl( 'flashes' );
			$this->redrawControl( 'data' );
		} else {
			$this->presenter->redirect( 'this', [ 'id' => NULL ] );
		}
	}


	/**
	 * Add new dummy record
	 */
	public function addRecord()
	{
		$this->filters = [];  // disable filtering
		$commodity = $this->facade->newCommodity();
		if ( $commodity ) {
			$this->flashMessage( 'New dummy record successfully created. Edit it!' );
			$this->setId( $commodity->getId() );
			if ( $this->presenter->isAjax() ) {
				$this->redrawControl( 'grid' );
			} else {
				$this->redirect( 'this' );
			}
		}
	}


	/**
	 * Remove record
	 *
	 * @param $id
	 */
	public function removeRecord( $id )
	{
		$res = $this->facade->removeCommodity( $id, TRUE );
		if ( $res ) {
			$this->flashMessage( 'Record removed.' );
			if ( $this->presenter->isAjax() ) {
				$this->redrawControl( 'grid' );
			} else {
				$this->redirect( 'this' );
			}
		}
	}
}