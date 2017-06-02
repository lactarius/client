<?php

namespace Client\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Core\Model\CreatedEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_purchase")
 *
 * @author Petr Blazicek 2017
 */
class Purchase extends CreatedEntity
{

	/**
	 * @ORM\Column(length=255)
	 * @var string
	 */
	private $note;

	/**
	 * @ORM\ManyToOne(targetEntity="Client", inversedBy="purchases")
	 * @var Client
	 */
	private $client;

	/**
	 * @ORM\ManyToOne(targetEntity="Card")
	 * @var Card
	 */
	private $card;

	/**
	 * @ORM\ManyToOne(targetEntity="Shop")
	 * @var Shop
	 */
	private $shop;

	/**
	 * @ORM\OneToMany(targetEntity="PurchaseItem", mappedBy="purchase")
	 * @var PurchaseItem|ArrayCollection
	 */
	private $items;

	// getters & setters


	/**
	 * @return string
	 */
	public function getNote()
	{
		return $this->note;
	}


	/**
	 * @param string $note
	 * @return self (fluent interface)
	 */
	public function setNote( $note )
	{
		$this->note = $note;
		return $this;
	}


	/**
	 * @return Client
	 */
	public function getClient()
	{
		return $this->client;
	}


	/**
	 * @param Client $client
	 * @return self (fluent interface)
	 */
	public function setClient( $client )
	{
		$this->client = $client;
		return $this;
	}


	/**
	 * @return Card
	 */
	public function getCard()
	{
		return $this->card;
	}


	/**
	 * @param Card $card
	 * @return self (fluent interface)
	 */
	public function setCard( $card )
	{
		$this->card = $card;
		return $this;
	}


	/**
	 * @return Shop
	 */
	public function getShop()
	{
		return $this->shop;
	}


	/**
	 * @param Shop $shop
	 * @return self (fluent interface)
	 */
	public function setShop( $shop )
	{
		$this->shop = $shop;
		return $this;
	}


	/**
	 * @return PurchaseItem|ArrayCollection
	 */
	public function getItems()
	{
		return $this->items;
	}


	/**
	 * @param mixed $item
	 * @return self (fluent interface)
	 */
	public function addItem( $item )
	{
		$this->items->add( $item );
		$item->setPurchase( $this );
		return $this;
	}


	/**
	 * @param mixed $item
	 * @return self (fluent interface)
	 */
	public function removeItem( $item )
	{
		$this->items->removeElement( $item );
		return $this;
	}
}