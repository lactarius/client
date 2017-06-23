<?php

namespace ACGrid;

use Kdyby\Doctrine\QueryBuilder;

/**
 * @author Petr Blazicek 2017
 */
interface IDataGrid
{
	/**
	 * @param string $name
	 * @param string|NULL $label
	 * @return Column
	 */
	function addColumn( $name, $label = NULL );


	/**
	 * DataGrid source
	 *
	 * @param array $filter
	 * @param array $sorting
	 * @return QueryBuilder
	 */
	function dataSource( $filter, $sorting );
}