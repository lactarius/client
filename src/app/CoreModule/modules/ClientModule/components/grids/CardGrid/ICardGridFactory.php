<?php

namespace Client\Components\Grids;

/**
 * @author Petr Blazicek 2017
 */
interface ICardGridFactory
{

	/** @return CardGrid */
	function create();
}