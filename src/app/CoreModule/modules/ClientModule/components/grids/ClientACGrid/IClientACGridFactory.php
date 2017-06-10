<?php

namespace Client\Components\Grids;

/**
 * @author Petr Blazicek 2017
 */
interface IClientACGridFactory
{

	/** @return ClientACGrid */
	function create();
}