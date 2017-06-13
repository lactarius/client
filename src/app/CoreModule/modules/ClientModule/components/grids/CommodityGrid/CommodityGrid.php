<?php

namespace Client\Components\Grids;

use ACGrid\DataGrid;
use Client\Model\ShopFacade;
use Nette\ComponentModel\IContainer;
use Nette\Forms\Form;

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
		$this->addColumn( 'name', 'Name' );

		$this->addColumn( 'info', 'Info' );
	}


	public function dataSource()
	{
		return $this->facade->getCommodityRepo()->findBy( [] );
	}


	protected function createComponentEditForm()
	{
		$form = new Form();

		$form->setMethod( 'get' );

		$form->addHidden( 'id' );

		$form->addText( 'name' )
			->addRule( Form::EMAIL, 'Hooovno..' )
			->setAttribute( 'autofocus' );

		$form->addText( 'info' );

		$form->addSubmit( 'save', 'Save' );
		$form->onSubmit[] = $this->saveRecord;

		if ( $this->id ) $form->setDefaults( $this->facade->restoreCommodity( $this->id ) );

		return $form;
	}


	public function saveRecord( Form $form )
	{
		$data = $form->getValues( TRUE );
		file_put_contents( TEMP_DIR . '/data.txt', var_export( $data, TRUE ) );

		$commodity = $this->facade->saveCommodity( $data, TRUE );
		if ( $commodity ) {
			$this->flashMessage( 'Commodity ' . $commodity->name . ' was successfully updated.' );
			$this->presenter->redirect( 'this', [ 'id' => NULL ] );
		}
	}


	public function addRecord()
	{
		$commodity = $this->facade->newCommodity();
		if ( $commodity ) {
			$this->flashMessage( 'New dummy record successfully created. Edit it!' );
			$this->redirect( 'this' );
		}
	}


	public function removeRecord( $id )
	{
		$res = $this->facade->removeCommodity( $id, TRUE );
		if ( $res ) {
			$this->flashMessage( 'Record removed.' );
			$this->redirect( 'this' );
		}
	}
}