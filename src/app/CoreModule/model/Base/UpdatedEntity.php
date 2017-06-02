<?php

namespace Core\Model;

use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\DateTime;

/**
 * Entity ancestor
 *
 * - Identified
 * - Create timestamp saved
 * - Update timestamp saved
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 *
 * @author Petr Blazicek 2017
 */
class UpdatedEntity extends CreatedEntity
{

	/**
	 * @ORM\Column(type="datetimetz", nullable=true)
	 * @var DateTime
	 */
	protected $updated;


	/**
	 * @return DateTime
	 */
	public function getUpdated()
	{
		return $this->updated;
	}


	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate()
	{
		$this->updated = new DateTime();
	}
}