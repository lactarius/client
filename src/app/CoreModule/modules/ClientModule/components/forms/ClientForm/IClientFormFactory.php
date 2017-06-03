<?php

namespace Client\Components\Forms;

/**
 * @author Petr Blazicek 2017
 */
interface IClientFormFactory
{

	/** @return ClientForm */
	function create();
}