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
 * - Soft delete timestamp saved
 *
 * @ORM\MappedSuperclass
 *
 * @author Petr Blazicek 2017
 */
class CommonEntity extends UpdatedEntity
{

	/**
	 * @ORM\Column(type="datetimetz", nullable=true)
	 * @var DateTime
	 */
	protected $deleted;


	/**
	 * @return DateTime
	 */
	public function getDeleted()
	{
		return $this->deleted;
	}


	/**
	 * @param DateTime $deleted
	 * @return self (fluent interface)
	 */
	public function setDeleted( $deleted )
	{
		$this->deleted = $deleted;
		return $this;
	}
}