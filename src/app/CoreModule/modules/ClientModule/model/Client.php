<?php

namespace Client\Model;

use Core\Model\CommonEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Location\Model\Address;
use Nette\Security\IIdentity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_client")
 *
 * @author Petr Blazicek 2017
 */
class Client extends CommonEntity implements IIdentity
{

	/**
	 * @ORM\Column(length=32)
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(length=32)
	 * @var string
	 */
	private $surname;

	/**
	 * @ORM\Column(length=32)
	 * @var string
	 */
	private $email;

	/**
	 * @ORM\Column(length=60, options={"fixed"=true}, nullable=true)
	 * @var string
	 */
	private $password;

	/**
	 * @ORM\Column(length=255, nullable=true)
	 * @var string
	 */
	private $roles;

	/**
	 * @ORM\ManyToOne(targetEntity="Location\Model\Address")
	 * @var Address
	 */
	private $address;

	/**
	 * @ORM\OneToOne(targetEntity="Profile")
	 * @var Profile
	 */
	private $profile;

	/**
	 * @ORM\OneToMany(targetEntity="Card", mappedBy="client")
	 * @var Card|ArrayCollection
	 */
	private $cards;

	/**
	 * @ORM\OneToMany(targetEntity="Purchase", mappedBy="client")
	 * @var Purchase|ArrayCollection
	 */
	private $purchases;


	/**
	 * Client constructor.
	 */
	public function __construct()
	{
		$this->cards = new ArrayCollection();
		$this->purchases = new ArrayCollection();
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
	public function getSurname()
	{
		return $this->surname;
	}


	/**
	 * @param string $surname
	 * @return self (fluent interface)
	 */
	public function setSurname( $surname )
	{
		$this->surname = $surname;
		return $this;
	}


	/**
	 * Return Client full name
	 *
	 * @return string
	 */
	public function getFullname()
	{
		return $this->getName() . ' ' . $this->getSurname();
	}


	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}


	/**
	 * @param string $email
	 * @return self (fluent interface)
	 */
	public function setEmail( $email )
	{
		$this->email = $email;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}


	/**
	 * @param string $password
	 * @return self (fluent interface)
	 */
	public function setPassword( $password )
	{
		$this->password = $password;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getRoles()
	{
		return $this->roles;
	}


	/**
	 * @param string $roles
	 * @return self (fluent interface)
	 */
	public function setRoles( $roles )
	{
		$this->roles = $roles;
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
	 * @return Profile
	 */
	public function getProfile()
	{
		return $this->profile;
	}


	/**
	 * @param Profile $profile
	 * @return self (fluent interface)
	 */
	public function setProfile( $profile )
	{
		$this->profile = $profile;
		return $this;
	}


	/**
	 * @return Card|ArrayCollection
	 */
	public function getCards()
	{
		return $this->cards;
	}


	/**
	 * @param Card $card
	 * @return self (fluent interface)
	 */
	public function addCard( $card )
	{
		$this->cards->add( $card );
		$card->setClient( $this );
		return $this;
	}


	/**
	 * @param Card $card
	 * @return self (fluent interface)
	 */
	public function removeCard( $card )
	{
		$this->cards->removeElement( $card );
		return $this;
	}


	/**
	 * @return Purchase|ArrayCollection
	 */
	public function getPurchases()
	{
		return $this->purchases;
	}


	/**
	 * @param Purchase $purchase
	 * @return self (fluent interface)
	 */
	public function addPurchase( $purchase )
	{
		$this->purchases->add( $purchase );
		$purchase->setClient( $this );
		return $this;
	}


	/**
	 * @param Purchase $purchase
	 * @return self (fluent interface)
	 */
	public function removePurchase( $purchase )
	{
		$this->purchases->removeElement( $purchase );
		return $this;
	}
}