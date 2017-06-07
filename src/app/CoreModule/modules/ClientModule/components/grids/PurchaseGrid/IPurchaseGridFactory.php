<?php

namespace Client\Components\Grids;

/**
 * @author Petr Blazicek 2017
 */
interface IPurchaseGridFactory
{

	/** @return PurchaseGrid */
	function create();
}