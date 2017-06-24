<?php

namespace ACPager;

use ACGrid\DataGrid;
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

	const DEFAULTS = [
		'values'  => [
			'rowsPerPage' => 10,
		],
		'buttons' => [
			'firstPage'  => FALSE,
			'lastPage'   => FALSE,
			'pageNumber' => FALSE,
			'pageSelect' => 0,
			'pageStep'   => 1,
		],
	];

	/** @var DataGrid */
	protected $grid;

	/** @var string */
	protected $htmlId;

	/** @var QueryBuilder */
	protected $builder;

	/** @var bool */
	protected $updated;

	/** @var int */
	protected $rowCount;

	/** @var int */
	protected $rowsPerPage;

	/** @var int */
	protected $currentPage;

	/** @var array */
	protected $buttons = [];


	public function __construct()
	{
	}


	// getters & setters


	// body

	public function hookUp(DataGrid $grid){
		$this->grid=$grid;
		return $this;
	}


	// design


	/**
	 * Which buttons will be visible
	 *
	 * @param bool $firstPage Show FIRST PAGE button
	 * @param bool $lastPage Show LAST PAGE button
	 * @param bool $pageNumber Show PAGE NUMBER (when used, page can be manually set)
	 * @param int $pageSelect Number of QUICK PAGE SELECT BUTTONS
	 * @param int $pageStep Step between QUICK PAGE SELECT numbers
	 * @return self (fluent interface)
	 */
	public function setButtons( $firstPage = FALSE, $lastPage = FALSE, $pageNumber = FALSE, $pageSelect = 0, $pageStep = 1 )
	{
		$this->buttons = [
			'firstPage'  => $firstPage,
			'lastPage'   => $lastPage,
			'pageNumber' => $pageNumber,
			'pageSelect' => $pageSelect,
			'pageStep'   => $pageStep,
		];
		return $this;
	}


	/**
	 * @return string
	 */
	protected function getJsOptions()
	{
		$options = [
			'server'  => $this->link( 'server!' ),
			'buttons' => $this->buttons,
		];

		return json_encode( $options );
	}


	// signal receiver


	public function handleServer()
	{

		$post = $this->presenter->getRequest()->getPost();
		$cmd = isset( $post[ 'cmd' ] ) ? $post[ 'cmd' ] : FALSE;
		$value = isset( $post[ 'value' ] ) ? $post[ 'value' ] : FALSE;

		$page = 1;
		switch ( $cmd ) {
			case 'first':
				$page = 1;
				break;
			case 'prev':
				$page = $this->getCurrentPage() - 1;
				break;
			case 'next':
				$page = $this->getCurrentPage() + 1;
				break;
			case 'last':
				$page = $this->getPageCount() - 1;
		}

		$this->setCurrentPage( $page );
		$this->paginate();
		//$this->grid->redrawControl( 'stencils' );
		//$this->grid->redrawControl( 'flashes' );
		$this->grid->redrawControl( 'grid' );

		$this->presenter->payload->result = TRUE;
		$this->presenter->sendPayload();
	}


	// render component

	public function render()
	{
		$this->htmlId = $this->getUniqueId();
		$this->buttons = $this->buttons ?: self::DEFAULTS[ 'buttons' ];

		$template = $this->template;
		$template->setFile( __DIR__ . '/pager.latte' );

		$template->rowCount = $this->getRowCount();
		$template->rowsPerPage = $this->getRowsPerPage();
		$template->pageCount = $this->getPageCount();
		$template->currentPage = $this->getCurrentPage();

		$template->pager = $this;
		$template->htmlId = $this->htmlId;
		$template->buttons = $this->buttons;
		$template->jsOptions = $this->getJsOptions();

		$template->render();
	}
}