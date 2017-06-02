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
}