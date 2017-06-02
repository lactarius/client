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
	 * @ORM\Column(length=60, options={"fixed"=true})
	 * @var string
	 */
	private $password;

	/**
	 * @ORM\Column(length=255)
	 * @var string
	 */
	private $roles;

	/**
	 * @ORM\OneToOne(targetEntity="Address")
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
	public function __construct ()
	{
		$this->cards = new ArrayCollection();
	}


	// getters & setters

}