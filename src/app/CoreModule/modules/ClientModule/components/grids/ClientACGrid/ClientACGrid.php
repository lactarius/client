<?php

namespace Client\Components\Grids;

use ACGrid\DataGrid;
use Client\Model\ClientFacade;
use Nette\Application\UI\Control;
use Nette\ComponentModel\IContainer;

/**
 * @author Petr Blazicek 2017
 */
class ClientACGrid extends Control
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
		$grid = new DataGrid();

		$grid->addDefTemplate( __DIR__ . '/def.latte' );

		$grid->addColumn( 'name', 'Name' );
		$grid->addColumn( 'surname', 'Surname' );

		$grid->makeEditable();

		$grid->setDataSource( $this->dataSource );

		$grid->setSaveData( $this->saveData );

		return $grid;
	}


	public function dataSource( $filter, $order )
	{
		return $this->facade->getRepo()->findBy( [], [ 'surname' => 'ASC' ] );
	}


	public function saveData( $data )
	{
		$client=$this->facade->saveClient( $data );
		if($client){
			
		}
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