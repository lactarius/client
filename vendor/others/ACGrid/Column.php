<?php

namespace ACGrid;

use Nette\Object;

/**
 * @author Petr Blazicek 2017
 */
class Column extends Object
{

	const TYPE_TEXT = 1;
	const TYPE_SELECT = 2;

	/** @var  string */
	protected $name;

	/** @var  string */
	protected $label;

	/** @var  int */
	protected $type;


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
}