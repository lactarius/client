<?php

namespace ACPager;

use Kdyby\Doctrine\QueryBuilder;
use Nette\Application\UI\Control;

/**
 * @author Petr Blazicek 2017
 */
class Pager extends Control
{
	const CMD_FIRST = 1;
	const CMD_PREVIOUS = 2;
	const CMD_NEXT = 3;
	const CMD_LAST = 4;
	const CMD_PAGE = 5;
	const CMD_SEND = 9;

	/** @persistent */
	public $rowCount;

	/** @persistent */
	public $pageCount;

	/** @persistent */
	public $currentPage;


	public function __construct()
	{
	}


	/**
	 * Create substitute (joke...)
	 *
	 * @return $this
	 */
	public function agent()
	{
		return $this;
	}


	public function source( QueryBuilder $qb )
	{
	}


	public function paginate( $page = 1 )
	{
		return [];
	}


	// signal receiver

	public function handleServer( $cmd, $value )
	{
		switch ( $cmd ) {
			case 1:
				break;
		}
	}


	// render component

	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/pager.latte' );

		$template->pager = $this;

		$template->render();
	}
}