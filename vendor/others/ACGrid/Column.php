<?php

namespace ACGrid;

use Nette\Object;

/**
 * @author Petr Blazicek 2017
 */
class Column extends Object
{

	const TYPE_TEXT = 1;
	const TYPE_LIST = 2;

	/** @var  DataGrid */
	protected $grid;

	/** @var  string */
	protected $name;

	/** @var  string */
	protected $label;

	/** @var  int */
	protected $type;

	/** @var int */
	protected $sort = DataGrid::SORT_NOT_SORTABLE;


	/**
	 * Column constructor.
	 * @param $name
	 * @param null $label
	 * @param int $type
	 */
	public function __construct( $name, $label = NULL, $type = self::TYPE_TEXT )
	{
		$this->name = $name;
		$this->label = $label;
		$this->type = $type;
	}


	/**
	 * @return DataGrid
	 */
	public function getGrid(): DataGrid
	{
		return $this->grid;
	}


	/**
	 * @param DataGrid $grid
	 * @return self (fluent interface)
	 */
	public function setGrid( DataGrid $grid ): Column
	{
		$this->grid = $grid;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $name
	 * @return self (fluent interface)
	 */
	public function setName( $name )
	{
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}


	/**
	 * @param string $label
	 * @return self (fluent interface)
	 */
	public function setLabel( $label )
	{
		$this->label = $label;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @param int $type
	 * @return self (fluent interface)
	 */
	public function setType( $type )
	{
		$this->type = $type;
		return $this;
	}


	/**
	 * @return int
	 */
	private function getPersistOrder()
	{
		if ( isset( $this->grid->sortDirs[ $this->name ] ) ) $this->sort = $this->grid->sortDirs[ $this->name ];
		return $this->sort;
	}


	/**
	 * @param $direction
	 * @return self (fluent interface)
	 */
	private function setPersistOrder( $direction )
	{
		$this->sort = $this->grid->sortDirs[ $this->name ] = $direction;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getOrder()
	{
		return $this->getPersistOrder();
	}


	/**
	 * Add ordering capability to Column
	 *
	 * @param int $direction
	 * @return self (fluent interface)
	 */
	public function order( $direction = DataGrid::SORT_OFF )
	{
		$this->getPersistOrder();
		if ( !is_int( $this->sort ) ) {
			$this->setPersistOrder( $direction );
		}
		return $this;
	}
}