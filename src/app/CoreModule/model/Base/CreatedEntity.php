<?php

namespace Core\Model;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;

/**
 * Entity ancestor
 *
 * - Identified
 * - Create timestamp saved
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 *
 * @author Petr Blazicek 2017
 */
class CreatedEntity extends SimpleEntity
{

	/**
	 * @ORM\Column(type="datetimetz")
	 * @var DateTime
	 */
	protected $created;


	/**
	 * @return DateTime
	 */
	public function getCreated()
	{
		return $this->created;
	}


	/**
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->created = new DateTime();
	}
}