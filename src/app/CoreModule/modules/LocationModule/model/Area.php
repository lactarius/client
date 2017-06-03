<?php

namespace Location\Model;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class Area
 *
 * @ORM\Entity
 * @ORM\Table(name="lc_area", options={"collate"="utf8_czech_ci"})
 *
 * @author Petr Blazicek 2016
 */
class Area extends \Core\Model\SimpleEntity
{

	/**
	 * @ORM\ManyToOne(targetEntity="Country", fetch="LAZY")
	 * @var Country
	 */
	private $country;

	/**
	 * @ORM\ManyToOne(targetEntity="Region", cascade={"all"}, fetch="LAZY")
	 * @var Region
	 */
	private $region;

	/**
	 * @ORM\ManyToOne(targetEntity="City", cascade={"all"}, fetch="LAZY")
	 * @var City
	 */
	private $city;

	/**
	 * @ORM\ManyToOne(targetEntity="Postal", cascade={"all"}, fetch="LAZY")
	 * @var Postal
	 */
	private $postal;

	/**
	 * @ORM\ManyToOne(targetEntity="District", cascade={"all"}, fetch="LAZY")
	 * @var District
	 */
	private $district;

	/**
	 * @ORM\ManyToOne(targetEntity="Part", cascade={"all"}, fetch="LAZY")
	 * @var Part
	 */
	private $part;

	/**
	 * @ORM\ManyToOne(targetEntity="Street", cascade={"all"}, fetch="LAZY")
	 * @var Street
	 */
	private $street;


	// getters & setters


	/**
	 * @return Country
	 */
	public function getCountry()
	{
		return $this->country;
	}


	/**
	 * @param Country $country
	 * @return self (fluent interface)
	 */
	public function setCountry( $country )
	{
		$this->country = $country;
		return $this;
	}


	/**
	 * @return Region
	 */
	public function getRegion()
	{
		return $this->region;
	}


	/**
	 * @param Region $region
	 * @return self (fluent interface)
	 */
	public function setRegion( $region = NULL )
	{
		$this->region = $region;
		return $this;
	}


	/**
	 * @return City
	 */
	public function getCity()
	{
		return $this->city;
	}


	/**
	 * @param City $city
	 * @return self (fluent interface)
	 */
	public function setCity( $city )
	{
		$this->city = $city;
		return $this;
	}


	/**
	 * @return Postal
	 */
	public function getPostal()
	{
		return $this->postal;
	}


	/**
	 * @param Postal $postal
	 * @return self (fluent interface)
	 */
	public function setPostal( $postal = NULL )
	{
		$this->postal = $postal;
		return $this;
	}


	/**
	 * @return District
	 */
	public function getDistrict()
	{
		return $this->district;
	}


	/**
	 * @param District $district
	 * @return self (fluent interface)
	 */
	public function setDistrict( $district = NULL )
	{
		$this->district = $district;
		return $this;
	}


	/**
	 * @return Part
	 */
	public function getPart()
	{
		return $this->part;
	}


	/**
	 * @param Part $part
	 * @return self (fluent interface)
	 */
	public function setPart( $part = NULL )
	{
		$this->part = $part;
		return $this;
	}


	/**
	 * @return Street
	 */
	public function getStreet()
	{
		return $this->street;
	}


	/**
	 * @param Street $street
	 * @return self (fluent interface)
	 */
	public function setStreet( $street = NULL )
	{
		$this->street = $street;
		return $this;
	}
}
