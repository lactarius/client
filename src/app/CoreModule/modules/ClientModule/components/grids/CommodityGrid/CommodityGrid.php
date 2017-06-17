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
		if ( count( $this->dataSnippet ) ) {
			return $this->dataSnippet;
		}

		// normal data
		$qb = $this->facade->getCommodityRepo()->createQueryBuilder( 'c' );

		// filters
		foreach ( $this->filters as $col => $filter ) {
			if ( $filter ) {
				$qb->andWhere( "c.$col LIKE :$col" )
					->setParameter( "$col", "$filter%" );
			}
		}

		// sort
		foreach ( $this->sortCols as $sortCol ) {
			$qb->addOrderBy( "c.$sortCol", self::DIR[ $this->sortDirs[ $sortCol ] ] );
		}

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

		$container->addText( 'name' )
			->setAttribute( 'autofocus' );
		$container->addText( 'info' );

		if ( count( $this->filters ) ) $container->setDefaults( $this->filters );

		return $container;
	}


	/**
	 * Edit definition form
	 *
	 * @return Container
	 */
	public function createEditContainer()
	{
		$container = new Container();

		$container->addHidden( 'id' )
			->setAttribute( 'autofocus' );
		$container->addText( 'name' );
		$container->addText( 'info' );
		$container->addSelect( 'parent' );

		if ( $this->id ) {
			$container[ 'parent' ]->setItems( $this->facade->parentSelect( $this->id ) );
			$container->setDefaults( $this->facade->restoreCommodity( $this->id ) );
		}

		return $container;
	}


	/**
	 * @param SubmitButton $button
	 */
	public function setFilters( SubmitButton $button )
	{
		$form = $button->getForm();
		if ( $form[ 'set_filters' ]->isSubmittedBy() ) {
			$data = $form->getValues( TRUE );
			$this->filters = $data[ 'filter' ];
			$this->flashMessage( 'Filters set.' );
		} else {
			$this->filters = [];
			$this->flashMessage( 'Filters reset.' );
		}
		if ( $this->presenter->isAjax() ) {
			$this->redrawControl( 'grid' );
		} else {
			$this->presenter->redirect( 'this' );
		}
	}


	/**
	 * @param SubmitButton $button
	 */
	public function saveRecord( SubmitButton $button )
	{
		$form = $button->getForm();
		if ( $form[ 'save' ]->isSubmittedBy() ) {
			$data = $form->getValues( TRUE );
			$this->dataSnippet[] = $commodity = $this->facade->saveCommodity( $data[ 'inner' ], TRUE );
			if ( $commodity ) {
				$this->flashMessage( 'Commodity "' . $commodity->getName() . '" was successfully saved.' );
			} else {
				$this->flashMessage( 'Commodity was not saved.', 'error' );
			}
		}

		if ( $this->presenter->isAjax() ) {
			$this->redrawControl( 'grid' );
		} else {
			$this->presenter->redirect( 'this', [ 'id' => NULL ] );
		}
	}


	public function addRecord()
	{
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