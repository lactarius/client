<?php

namespace Location\Model;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class District
 *
 * @ORM\Entity
 * @ORM\Table(name="lc_district", options={"collate"="utf8_czech_ci"})
 *
 * @author Petr Blazicek 2016
 */
class District extends \Core\Model\SimpleEntity
{

	/**
	 * @ORM\Column(length=32, unique=true)
	 * @var string
	 */
	private $name;


	public function __construct( $name )
	{
		$this->setName( $name );
	}


	// getters & setters

	public function getName()
	{
		return $this->name;
	}


	public function setName( $name )
	{
		$this->name = $name;
		return $this;
	}
}
