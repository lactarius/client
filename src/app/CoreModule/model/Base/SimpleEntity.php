<?php

namespace Core\Model;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Strings;
use Nette\Object;


/**
 * Entity ancestor
 *
 * - Identified
 *
 * @ORM\MappedSuperclass
 *
 * @author Petr Blazicek 2017
 */
abstract class SimpleEntity extends Object
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="bigint")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
}
