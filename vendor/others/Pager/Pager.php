<?php

namespace ACPager;

use ACGrid\DataGrid;
use Kdyby\Doctrine\QueryBuilder;
use Nette\Application\UI\Control;
use Nette\Http\Session;
use Nette\Http\SessionSection;

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

	/** @var Session */
	protected $session;

	/** @var SessionSection */
	protected $section;

	/** @var DataGrid */
	protected $grid;

	/** @var string */
	protected $htmlId;

	/** @var QueryBuilder */
	protected $source;

	/** @persistent */
	protected $updated;

	/** @persistent */
	public $rowCount;

	/** @persistent */
	public $rowsPerPage;

	/** @persistent */
	public $currentPage;

	/** @persistent */
	public $buttons = [];

	/** @var array */
	protected $data;


	public function __construct()
	{
		//$this->session = $session;
		//$this->section = $session->getSection( $this->getUniqueId() );
	}


	// getters & setters


	/**
	 * @param QueryBuilder $source
	 * @return self (fluent interface)
	 */
	public function setSource( QueryBuilder $source ): Pager
	{
		$this->source = $source;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getRowCount(): int
	{
		return $this->rowCount;
	}


	/**
	 * @return int
	 */
	public function getRowsPerPage(): int
	{
		if ( !$this->rowsPerPage ) $this->rowsPerPage = self::DEFAULTS[ 'values' ][ 'rowsPerPage' ];
		return $this->rowsPerPage;
	}


	/**
	 * @param int $rowsPerPage
	 * @return self (fluent interface)
	 */
	public function setRowsPerPage( int $rowsPerPage ): Pager
	{
		$this->rowsPerPage = $rowsPerPage;
		return $this;
	}


	/**
	 * @return int
	 */
	public function getPageCount()
	{
		return intdiv( $this->getRowCount(), $this->getRowsPerPage() )
			+ ( $this->getRowCount() % $this->getRowsPerPage() > 0 ? 1 : 0 );
	}


	/**
	 * @return int
	 */
	public function getCurrentPage(): int
	{
		if ( !$this->currentPage ) $this->currentPage = 1;
		return $this->currentPage;
	}


	/**
	 * @param int $currentPage
	 * @return self (fluent interface)
	 */
	public function setCurrentPage( int $currentPage ): Pager
	{
		$this->currentPage = $currentPage;
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
	 * @return array
	 */
	public function getData(): array
	{
		if ( !$this->data ) $this->paginate();
		return $this->data;
	}


	/**
	 * @param array $data
	 * @return self (fluent interface)
	 */
	public function setData( array $data ): Pager
	{
		$this->data = $data;
		return $this;
	}


	// body


	/**
	 * Create substitute (joke...)
	 *
	 * @param DataGrid $grid
	 * @return self (fluent interface)
	 */
	public function agent( DataGrid $grid )
	{
		$this->grid = $grid;
		return $this;
	}


	/**
	 * @return self (fluent interface)
	 */
	public function invalidate()
	{
		$this->updated = FALSE;
		return $this;
	}


	public function calculateRowCount()
	{
		$qb = clone $this->source;
		$alias = $qb->getRootAliases()[ 0 ];

		$qb->resetDQLParts( [ 'select', 'orderBy' ] );
		$qb->select( "COUNT($alias.id)" );

		return $qb
			->getQuery()
			->getSingleScalarResult();
	}


	public function paginate()
	{
		// not fresh => reload data
		if ( !$this->updated ) {
			$this->rowCount = $this->calculateRowCount();
			$this->updated = TRUE;
		}

		file_put_contents( TEMP_DIR . '/sort.txt', var_export( $this->rowCount, TRUE ) );
		$qb = $this->source;
		$offset = ( $this->getCurrentPage() - 1 ) * $this->getRowsPerPage();
		$qb->setFirstResult( $offset )->setMaxResults( $this->getRowsPerPage() );

		$this->setData( $qb->getQuery()->getResult() );
	}


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