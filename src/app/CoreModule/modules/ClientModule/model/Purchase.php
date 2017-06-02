<?php

namespace Client\Model;

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
	private $shop;
	private $card;
}