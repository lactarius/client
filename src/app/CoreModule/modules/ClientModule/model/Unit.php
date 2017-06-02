<?php

namespace Client\Model;

use Doctrine\ORM\Mapping as ORM;
use Core\Model\SimpleEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_unit")
 *
 * @author Petr Blazicek 2017
 */
class Unit extends SimpleEntity
{

	/**
	 * @ORM\Column(length=16)
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(length=64, nullable=true)
	 * @var string
	 */
	private $info;


	// getters & setters


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
	public function getInfo()
	{
		return $this->info;
	}


	/**
	 * @param string $info
	 * @return self (fluent interface)
	 */
	public function setInfo( $info )
	{
		$this->info = $info;
		return $this;
	}
}