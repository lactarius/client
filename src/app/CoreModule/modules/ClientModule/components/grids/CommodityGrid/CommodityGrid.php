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
	public function __construct(ShopFacade $facade, IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );
		$this->facade=$facade;
	}

	protected function createComponentGrid(){
		$grid=new DataGrid();

		$grid->addColumn('name','Name');

		$grid->addColumn('info','Info');

		$grid->makeEditable();


	}

	public function dataSource($filters,$sorts){
		return $this->facade->getCommodityRepo()->findBy([]);
	}


}