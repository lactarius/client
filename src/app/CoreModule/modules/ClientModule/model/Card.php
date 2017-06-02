<?php

namespace Client\Model;

use Core\Model\CommonEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_card")
 *
 * @author Petr Blazicek 2017
 */
class Card extends CommonEntity
{

	const TYPE_TEMP = 1;
	const TYPE_BASE = 2;
	const TYPE_PREMIUM = 3;

	/**
	 * @ORM\Column(length=16)
	 * @var string
	 */
	private $number;

	/**
	 * @ORM\Column(type="smallint")
	 * @var int
	 */
	private $type;

	/**
	 * @ORM\ManyToOne(targetEntity="Client", inversedBy="cards")
	 * @var Client
	 */
	private $client;


	// getters & setters


	/**
	 * @return string
	 */
	public function getNumber ()
	{
		return $this->number;
	}


	/**
	 * @param string $number
	 * @return self (fluent interface)
	 */
	public function setNumber ( $number )
	{
		$this->number = $number;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getType ()
	{
		return $this->type;
	}


	/**
	 * @param int $type
	 * @return self (fluent interface)
	 */
	public function setType ( $type )
	{
		$this->type = $type;
		return $this;
	}


	/**
	 * @return Client
	 */
	public function getClient ()
	{
		return $this->client;
	}


	/**
	 * @param Client $client
	 * @return self (fluent interface)
	 */
	public function setClient ( $client )
	{
		$this->client = $client;
		return $this;
	}
}