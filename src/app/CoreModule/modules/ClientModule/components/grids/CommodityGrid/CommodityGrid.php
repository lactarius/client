<?php

namespace Client\Components\Grids;

use ACGrid\DataGrid;
use Client\Model\ShopFacade;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;
use Nette\Http\Session;

/**
 * @author Petr Blazicek 2017
 */
class CommodityGrid extends DataGrid
{

	public function __construct( ShopFacade $facade, Session $session )
	{
		parent::__construct( $session );
		/** @var ShopFacade $facade */
		$this->facade = $facade;
	}


	protected function build()
	{
		$this->addStencil( __DIR__ . '/def.latte' );

		$this->addColumn( 'name', 'Name' )->order();
		$this->addColumn( 'info', 'Info' )->order();
		$this->addColumn( 'parent', 'Parent' );

		$this->allowFilter()->allowAdd()->allowEdit()->allowRemove();

		$this->setTitle( 'Commodity' );
		$this->setFooter( 'ACGrid 2017' );
	}


	/**
	 * Grid data source
	 *
	 * @return array
	 */
	public function dataSource()
	{
		// snippet prepared?
		if ( count( $this->dataSnippet ) ) return $this->dataSnippet;
		// normal data
		$qb = $this->facade->getCommodityRepo()->createQueryBuilder( 'c' );
		// filters
		foreach ( $this->filters as $col => $filter ) {
			if ( $filter ) $qb->andWhere( "c.$col LIKE :$col" )
				->setParameter( "$col", "$filter%" );
		}
		// sort
		foreach ( $this->sortCols as $sortCol )
			$qb->addOrderBy( "c.$sortCol", self::DIR[ $this->sortDirs[ $sortCol ] ] );

		return $qb->getQuery()->getResult();
	}


	/**
	 * Filter definition form
	 *
	 * @return Container
	 */
	public function createFilterContainer()
	{
		$container = new Container();

		$container->addText( 'name' );
		$container->addText( 'info' );

		$container->setDefaults( $this->filters );

		return $container;
	}


	public function setFilter( $submit, $data )
	{
		if ( $submit == 'setFilter' ) {

			$this->filters = $data;
			$this->flashMessage( 'Filter set.' );
		} else {

			$this->filters = array_fill_keys( array_keys( $this->filters ), '' );
			$this->flashMessage( 'Filter reset.' );
		}

		$this->redrawControl( 'grid' );
	}


	/**
	 * Edit definition form
	 *
	 * @return Container
	 */
	public function createEditContainer()
	{
		$container = new Container();

		$container->addHidden( 'id' );
		$container->addText( 'name' )
			->setAttribute( 'autofocus' );
		$container->addText( 'info' );
		$container->addSelect( 'parent', NULL, $this->facade->parentSelect() );

		if ( $this->id ) {
			$container->setDefaults( $this->facade->restoreCommodity( $this->id ) );
		}

		return $container;
	}


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


	public function processForm( Form $form )
	{
		$button = $form->isSubmitted();
		if ( $button ) {
			$submit = $button->getName();
			$data = $form->getValues( TRUE );
			//file_put_contents( TEMP_DIR . '/sort.txt', var_export( $data, TRUE ) );

			if ( $submit == 'setFilter' || $submit == 'resetFilter' ) $this->setFilter( $submit, $data[ 'filter' ] );
			if ( $submit == 'saveRecord' || $submit == 'cancelRecord' ) $this->saveRecord( $submit, $data[ 'edit' ] );
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