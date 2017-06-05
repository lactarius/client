<?php

namespace Client\Model;

use Doctrine\Common\Collections\ArrayCollection;
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
	 * @ORM\Column(length=255, nullable=true)
	 * @var string
	 */
	private $url;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string
	 */
	private $info;

	/**
	 * @ORM\ManyToOne(targetEntity="Location\Model\Address")
	 * @var Address
	 */
	private $address;

	/**
	 * @ORM\ManyToMany(targetEntity="Commodity")
	 * @ORM\JoinTable(name="cl_shops_commodities",
	 *     joinColumns={@ORM\JoinColumn(name="shop_id", referencedColumnName="id")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="commodity_id", referencedColumnName="id")}
	 *     )
	 * @var Commodity|ArrayCollection
	 */
	private $commodities;


	public function __construct()
	{
		$this->commodities = new ArrayCollection();
	}

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


	/**
	 * @return Commodity|ArrayCollection
	 */
	public function getCommodities()
	{
		return $this->commodities;
	}


	/**
	 * @param Commodity $commodity
	 * @return self (fluent interface)
	 */
	public function addCommodity( $commodity )
	{
		if ( !$this->commodities->contains( $commodity ) ) {
			$this->commodities->add( $commodity );
		}
		return $this;
	}


	/**
	 * @param Commodity $commodity
	 * @return self (fluent interface)
	 */
	public function removeCommodity( $commodity )
	{
		$this->commodities->removeElement( $commodity );
		return $this;
	}
}