<?php

namespace Client\Model;

use Doctrine\ORM\Mapping as ORM;
use Core\Model\SimpleEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_purchase_item")
 *
 * @author Petr Blazicek 2017
 */
class PurchaseItem extends SimpleEntity
{

	/**
	 * @ORM\Column(length=64)
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $unitPrice;

	/**
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $amount;

	/**
	 * @ORM\ManyToOne(targetEntity="Purchase", inversedBy="items")
	 * @var Purchase
	 */
	private $purchase;

	/**
	 * @ORM\ManyToOne(targetEntity="Commodity")
	 * @var Commodity
	 */
	private $commodity;

	/**
	 * @ORM\ManyToOne(targetEntity="Unit")
	 * @var Unit
	 */
	private $unit;

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
	 * @return int
	 */
	public function getUnitPrice()
	{
		return $this->unitPrice;
	}


	/**
	 * @param int $unitPrice
	 * @return self (fluent interface)
	 */
	public function setUnitPrice( $unitPrice )
	{
		$this->unitPrice = $unitPrice;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getAmount()
	{
		return $this->amount;
	}


	/**
	 * @param int $amount
	 * @return self (fluent interface)
	 */
	public function setAmount( $amount )
	{
		$this->amount = $amount;
		return $this;
	}


	/**
	 * @return Purchase
	 */
	public function getPurchase()
	{
		return $this->purchase;
	}


	/**
	 * @param Purchase $purchase
	 * @return self (fluent interface)
	 */
	public function setPurchase( $purchase )
	{
		$this->purchase = $purchase;
		return $this;
	}


	/**
	 * @return Commodity
	 */
	public function getCommodity()
	{
		return $this->commodity;
	}


	/**
	 * @param Commodity $commodity
	 * @return self (fluent interface)
	 */
	public function setCommodity( $commodity )
	{
		$this->commodity = $commodity;
		return $this;
	}


	/**
	 * @return Unit
	 */
	public function getUnit()
	{
		return $this->unit;
	}


	/**
	 * @param Unit $unit
	 * @return self (fluent interface)
	 */
	public function setUnit( $unit )
	{
		$this->unit = $unit;
		return $this;
	}
}