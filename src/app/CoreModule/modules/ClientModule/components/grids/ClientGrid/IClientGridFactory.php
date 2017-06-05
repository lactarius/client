<?php

namespace Client\Components\Grids;

/**
 * @author Petr Blazicek 2017
 */
interface IClientGridFactory
{

	/** @return ClientGrid */
	function create();
}