<?php

namespace ACGrid;

use Kdyby\Doctrine\QueryBuilder;

/**
 * @author Petr Blazicek 2017
 */
interface IDataGrid
{

	const SOURCE_ACTION_DATA = 1;
	const SOURCE_ACTION_SNIPPET = 2;
	const SOURCE_ACTION_COUNT = 3;


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
	 * @param int $action
	 * @return QueryBuilder
	 */
	function dataSource( $filter, $sorting, $action = self::SOURCE_ACTION_DATA );
}