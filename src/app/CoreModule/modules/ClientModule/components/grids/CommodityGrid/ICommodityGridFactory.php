<?php

namespace Client\Components\Grids;

/**
 * @author Petr Blazicek 2017
 */
interface ICommodityGridFactory
{

	/** @return CommodityGrid */
	function create();
}