<?php

namespace Client\Model;

use Doctrine\ORM\Mapping as ORM;
use Core\Model\SimpleEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_profile")
 *
 * @author Petr Blazicek 2017
 */
class Profile extends SimpleEntity
{

	const SEX_MALE = 1;
	const SEX_FEMALE = 2;

	/**
	 * @ORM\Column(type="smallint")
	 * @var int
	 */
	private $sex;

	/**
	 * @ORM\Column(length=32)
	 * @var string
	 */
	private $phone;

	/**
	 * @ORM\Column(length=255)
	 * @var string
	 */
	private $photo;


	/**
	 * @return int
	 */
	public function getSex ()
	{
		return $this->sex;
	}


	// getters & setters


	/**
	 * @param int $sex
	 * @return self (fluent interface)
	 */
	public function setSex ( $sex )
	{
		$this->sex = $sex;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getPhone ()
	{
		return $this->phone;
	}


	/**
	 * @param string $phone
	 * @return self (fluent interface)
	 */
	public function setPhone ( $phone )
	{
		$this->phone = $phone;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getPhoto ()
	{
		return $this->photo;
	}


	/**
	 * @param string $photo
	 * @return self (fluent interface)
	 */
	public function setPhoto ( $photo )
	{
		$this->photo = $photo;
		return $this;
	}
}