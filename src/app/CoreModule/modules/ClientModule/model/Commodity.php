<?php

namespace Client\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Core\Model\SimpleEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cl_commodity")
 *
 * @author Petr Blazicek 2017
 */
class Commodity extends SimpleEntity
{

	/**
	 * @ORM\Column(length=64)
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\Column(length=128, nullable=true)
	 * @var string
	 */
	private $info;

	/**
	 * @ORM\OneToMany(targetEntity="Commodity", mappedBy="parent")
	 * @var ArrayCollection|Commodity
	 */
	private $children;

	/**
	 * @ORM\ManyToOne(targetEntity="Commodity", inversedBy="children")
	 * @var Commodity
	 */
	private $parent;


	/**
	 * Commodity constructor.
	 */
	public function __construct()
	{
		$this->children = new ArrayCollection();
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
	 * @return Commodity|ArrayCollection
	 */
	public function getChildren()
	{
		return $this->children;
	}


	/**
	 * @param Commodity $child
	 * @return self (fluent interface)
	 */
	public function addChild( $child )
	{
		$this->children->add( $child );
		$child->setParent( $this );
		return $this;
	}


	/**
	 * @param Commodity $child
	 * @return self (fluent interface)
	 */
	public function removeChild( $child )
	{
		$this->children->removeElement( $child );
		$child->setParent( NULL );
		return $this;
	}


	/**
	 * @return Commodity
	 */
	public function getParent()
	{
		return $this->parent;
	}


	/**
	 * @param Commodity $parent
	 * @return self (fluent interface)
	 */
	public function setParent( $parent )
	{
		$this->parent = $parent;
		return $this;
	}
}