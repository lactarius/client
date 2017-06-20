<?php

namespace ACGrid;

use Nette\SmartObject;

/**
 * @author Petr Blazicek 2017
 */
class Column
{

	use SmartObject;

	const SORT_NOT_SORTABLE = NULL;
	const SORT_OFF = 0;
	const SORT_ASC = 1;
	const SORT_DESC = 2;

	/** @var DataGrid */
	protected $grid;

	/** @var string */
	protected $name;

	/** @var string */
	protected $label;

	/** @var int */
	protected $sorting;


	/**
	 * Column constructor.
	 * @param $name
	 * @param null $label
	 */
	public function __construct( $name, $label = NULL )
	{
		$this->name = $name;
		$this->label = $label;
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
	 * @return bool
	 */
	public function isSortable()
	{
		if ( isset( $this->grid->sortable[ $this->name ] ) ) {
			$this->sorting = $this->grid->sortable[ $this->name ];
			return TRUE;
		}
		return FALSE;
	}


	/**
	 * @return bool|int
	 */
	public function getSorting()
	{
		return $this->isSortable() ? $this->sorting : FALSE;
	}


	/**
	 * @param int $sorting
	 * @return self (fluent interface)
	 */
	public function sort( int $sorting = self::SORT_OFF ): Column
	{
		$this->grid->sortable[ $this->name ] = $this->sorting = $sorting;
		return $this;
	}
}