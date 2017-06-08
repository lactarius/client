<?php

namespace ACGrid;

use Nette\Object;

/**
 * @author Petr Blazicek 2017
 */
class Column extends Object
{

	/** @var  string */
	protected $name;

	/** @var  string */
	protected $label;


	/**
	 * Column constructor.
	 * @param $name
	 * @param $label
	 */
	public function __construct( $name, $label )
	{
		$this->name = $name;
		$this->label = $label;
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
}