<?php

namespace Client\Model;

use Doctrine\ORM\Mapping as ORM;
use Core\Model\SimpleEntity;
use Location\Model\Address;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_shop")
 *
 * @author Petr Blazicek 2017
 */
class Shop extends SimpleEntity
{

	/**
	 * @ORM\Column(length=32)
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(length=255)
	 * @var string
	 */
	private $url;

	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	private $info;

	/**
	 * @ORM\ManyToOne(targetEntity="Location\Model\Address")
	 * @var Address
	 */
	private $address;

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
	public function getUrl()
	{
		return $this->url;
	}


	/**
	 * @param string $url
	 * @return self (fluent interface)
	 */
	public function setUrl( $url )
	{
		$this->url = $url;
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


	/**
	 * @return Address
	 */
	public function getAddress()
	{
		return $this->address;
	}


	/**
	 * @param Address $address
	 * @return self (fluent interface)
	 */
	public function setAddress( $address )
	{
		$this->address = $address;
		return $this;
	}
}