<?php

namespace Core\Components\Forms;

use Nette\Application\UI\Control;

/**
 * Ancestor for Control based forms
 *
 * @author Petr Blazicek 2017
 */
class BaseControlForm extends Control
{

	protected $facade;

	protected $id;

	public $onSave;


	public function setId( $id )
	{
		$this->id = $id;
	}
}