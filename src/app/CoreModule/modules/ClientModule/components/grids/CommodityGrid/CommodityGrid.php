<?php

namespace Client\Components\Grids;

use ACGrid\DataGrid;
use Client\Model\ShopFacade;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Container;

/**
 * @author Petr Blazicek 2017
 */
class CommodityGrid extends DataGrid
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


	protected function build()
	{
		$this->addStencil( __DIR__ . '/def.latte' );

		$this->addColumn( 'name', 'Name' );
		$this->addColumn( 'info', 'Info' );

		$this->allowAdd()
			->allowEdit()
			->allowRemove();
	}


	public function dataSource()
	{
		return $this->facade->getCommodityRepo()->findBy( [] );
	}


	public function createEditContainer()
	{
		$container = new Container();

		$container->addHidden( 'id' );
		$container->addText( 'name' )
			->setAttribute( 'autofocus' );
		$container->addText( 'info' );

		if ( $this->id ) $container->setDefaults( $this->facade->restoreCommodity( $this->id ) );

		return $container;
	}


	public function saveRecord( Form $form )
	{
		if ( $form[ 'save' ]->isSubmittedBy() ) {
			$data = $form->getValues( TRUE );
			$commodity = $this->facade->saveCommodity( $data[ 'inner' ], TRUE );
			if ( $commodity ) {
				$this->flashMessage( 'Commodity <strong>' . $commodity->getName() . '</strong> was successfully saved.' );
			} else {
				$this->flashMessage( 'Commodity was not saved.', 'error' );
			}
		}

		if ( $this->presenter->isAjax() ) {
			$this->redrawControl( 'flashes' );
			$this->redrawControl( 'rows' );
		} else {
			$this->presenter->redirect( 'this', [ 'id' => NULL ] );
		}
	}


	public function addRecord()
	{
		$commodity = $this->facade->newCommodity();
		if ( $commodity ) {
			$this->flashMessage( 'New dummy record successfully created. Edit it!' );
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