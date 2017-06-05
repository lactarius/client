<?php

namespace Client\Components\Forms;

/**
 * @author Petr Blazicek 2017
 */
interface IShopFormFactory
{

	/** @return ShopForm */
	function create();
}