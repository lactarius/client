<?php

namespace ACGrid;

use Kdyby\Doctrine\QueryBuilder;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\InvalidArgumentException;

/**
 * @author Petr Blazicek 2017
 */
abstract class DataGrid extends Control
{

	const CMD = [
		'ADD'        => 1,
		'EDIT'       => 2,
		'REMOVE'     => 3,
		'ORDER'      => 4,
		'RESET_SORT' => 5,
		'PG_FIRST'   => 10,
		'PG_PREV'    => 11,
		'PG_NEXT'    => 12,
		'PG_LAST'    => 13,
		'PG_QUICK'   => 14,
	];

	const DIR = [ 1 => 'ASC', 2 => 'DESC' ];

	protected $facade;


	// labels

	protected $labels = [
		'new'           => 'New',
		'edit'          => 'Edit',
		'save_record'   => 'Save',
		'cancel_record' => 'Cancel',
		'remove'        => 'Remove',
		'reset_sort'    => 'Reset',
		'set_filter'    => 'Filter',
		'reset_filter'  => 'Reset',
	];

	// pager

	const ROWS_PER_PAGE = 5;

	const PAGER_BUTTONS = [
		'first'      => FALSE,
		'last'       => FALSE,
		'quick'      => 0,
		'quick_step' => 1,
		'number'     => TRUE,
	];

	// data structure

	/** @var Column|array */
	protected $columns = [];

	/** @persistent */
	public $filtering = [];

	/** @persistent */
	public $sortable = [];

	/** @persistent */
	public $sorting = [];

	/** @var mixed */
	protected $key = 'id';

	// editing

	/** @var bool */
	protected $editable = FALSE;

	/** @var bool */
	protected $adding = FALSE;

	/** @var bool */
	protected $removable = FALSE;

	/** @var mixed */
	protected $id;

	/** @var array */
	protected $dataSnippet = [];

	// pager

	/** @persistent */
	public $currentPage;

	/** @persistent */
	public $rowCount;

	/** @persistent */
	public $rowsPerPage;

	/** @var bool */
	protected $updated;

	// design

	/** @var array */
	protected $stencils = [];

	/** @var int */
	protected $actionWidth = 2;

	/** @var array */
	protected $pagerButtons;

	/** @var string */
	protected $actitle;

	/** @var string */
	protected $acfooter;


	/**
	 * DataGrid constructor.
	 */
	public function __construct( $facade )
	{
		$this->facade = $facade;
		$this->build();
	}


	// factories


	/**
	 * Edit form factory
	 *
	 * @return Form
	 */
	protected function createComponentEditForm()
	{
		$form = new Form();
		$form->getElementPrototype()->class( 'ajax' );

		if ( $this->isFiltering() ) {
			$form[ 'filter' ] = new FilterForm( $this );
			$form->addSubmit( 'setFilter', 'Filter' );
			$form->addSubmit( 'resetFilter', 'Reset' );
		}

		if ( $this->isEditing() ) {
			$form[ 'edit' ] = new EditForm( $this );
			$form->addSubmit( 'saveRecord', 'Save' );
			$form->addSubmit( 'cancelRecord', 'Cancel' );
		}

		$form->onSubmit[] = [ $this, 'processForm' ];

		return $form;
	}


	/**
	 * Filter & Edit forms hack
	 *
	 * @param Form $form
	 */
	public function processForm( Form $form )
	{
		$button = $form->isSubmitted();
		if ( $button ) {
			$submit = $button->getName();
			$data = $form->getValues( TRUE );

			if ( $submit == 'setFilter' || $submit == 'resetFilter' ) $this->setFilter( $submit, $data[ 'filter' ] );
			if ( $submit == 'saveRecord' || $submit == 'cancelRecord' ) $this->saveRecord( $submit, $data[ 'edit' ] );
		}
	}


	// getters & setters


	/**
	 * @return mixed
	 */
	public function getFacade()
	{
		return $this->facade;
	}


	/**
	 * Add new grid column
	 *
	 * @param $name
	 * @param null $label
	 * @return Column
	 */
	public function addColumn( $name, $label = NULL )
	{
		$this->columns[ $name ] = $column = new Column( $name, $label );
		return $column->setGrid( $this );
	}


	/**
	 * Set primary key
	 *
	 * @param mixed $key
	 * @return self (fluent interface)
	 */
	public function setKey( $key )
	{
		$this->key = $key;
		return $this;
	}


	// sorting


	/**
	 * Numeric order of sorting column
	 *
	 * @param string $col
	 * @return int
	 */
	public function getColumnOrder( $col )
	{
		return array_search( $col, array_keys( $this->sorting ) );
	}


	/**
	 * ASC / DESC direction => put on sorting list
	 * Other => remove
	 *
	 * @param string $col
	 * @param int $dir
	 * @return self (fluent interface)
	 */
	public function setColumnOrder( $col, $dir )
	{
		if ( $dir > 0 ) $this->sorting[ $col ] = $dir;
		else unset( $this->sorting[ $col ] );
		return $this;
	}


	/**
	 * Switch sort direction
	 *
	 * @param $col
	 * @return self (fluent interface)
	 */
	public function switchSortDirection( $col )
	{
		$dir = $this->sortable[ $col ];
		$newDir = $dir == Column::SORT_DESC ? Column::SORT_OFF : $dir + 1;
		$this->sortable[ $col ] = $newDir;
		$this->setColumnOrder( $col, $newDir );
		return $this;
	}


	/**
	 * Reset sorting
	 *
	 * @return self (fluent interface)
	 */
	public function resetSort()
	{
		$this->sorting = [];
		return $this;
	}


	// editing


	/**
	 * Dummy record can be added
	 *
	 * @return self (fluent interface)
	 */
	public function allowAdding()
	{
		$this->adding = TRUE;
		return $this;
	}


	/**
	 * Records can be erased
	 *
	 * @return self (fluent interface)
	 */
	public function allowRemoving()
	{
		$this->removable = TRUE;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function isSorting()
	{
		return count( $this->sortable ) > 0;
	}


	/**
	 * @return bool
	 */
	public function isFiltering()
	{
		return class_exists( 'ACGrid\FilterForm' );
	}


	/**
	 * @return bool
	 */
	public function isAdding()
	{
		return $this->adding;
	}


	/**
	 * @return bool
	 */
	public function isRemoving()
	{
		return $this->removable;
	}


	/**
	 * @return bool
	 */
	public function isEditing()
	{
		return class_exists( 'ACGrid\EditForm' );
	}


	/**
	 * @return bool
	 */
	public function hasActions()
	{
		return $this->isSorting() || $this->isFiltering() || $this->isAdding()
			|| $this->isRemoving() || $this->isEditing();
	}


	/**
	 * Editing ID getter
	 *
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * Editing ID setter
	 *
	 * @param $id
	 * @return self (fluent interface)
	 */
	public function setId( $id )
	{
		$this->id = $id;
		return $this;
	}

	// data


	/**
	 * @return int
	 */
	public function getCurrentPage()
	{
		if ( !$this->currentPage ) $this->currentPage = 1;
		return $this->currentPage;
	}


	/**
	 * @param mixed $currentPage
	 * @return self (fluent interface)
	 */
	public function setCurrentPage( $currentPage )
	{
		$this->currentPage = $currentPage;
		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getRowCount()
	{
		if ( !$this->rowCount ) $this->setRowCount( $this->calculateRowCount() );
		return $this->rowCount;
	}


	/**
	 * @param mixed $rowCount
	 * @return self (fluent interface)
	 */
	public function setRowCount( $rowCount )
	{
		$this->rowCount = $rowCount;
		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getRowsPerPage()
	{
		if ( !$this->rowsPerPage ) $this->rowsPerPage = self::ROWS_PER_PAGE;
		return $this->rowsPerPage;
	}


	/**
	 * @param mixed $rowsPerPage
	 * @return self (fluent interface)
	 */
	public function setRowsPerPage( $rowsPerPage )
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
			+ ( $this->getRowCount() % $this->getRowsPerPage() ? 1 : 0 );
	}


	// pager


	/**
	 * @return self (fluent interface)
	 */
	public function invalidatePager()
	{
		$this->updated = FALSE;
		return $this;
	}


	/**
	 * @return bool
	 */
	public function isUpdated()
	{
		return $this->updated;
	}


	/**
	 * @return self (fluent interface)
	 */
	public function setUpdated()
	{
		$this->updated = TRUE;
		return $this;
	}


	/**
	 * @return int
	 */
	protected function calculateRowCount()
	{
		$dqb = clone $this->dataSource( $this->filtering, $this->sorting );
		$alias = $dqb->getRootAliases()[ 0 ];
		$dqb->resetDQLParts( [ 'select', 'orderBy' ] );

		$dqb->select( "COUNT($alias.id)" );

		return (int)$dqb->getQuery()->getSingleScalarResult();
	}


	/**
	 * @return array
	 */
	protected function getData()
	{
		if ( !$this->isUpdated() ) {
			$this->getRowCount();
			$this->setUpdated();
		}

		$dqb = clone $this->dataSource( $this->filtering, $this->sorting );

		$dqb->setFirstResult( ( $this->getCurrentPage() - 1 ) * $this->getRowsPerPage() );
		$dqb->setMaxResults( $this->getRowsPerPage() );

		return $dqb->getQuery()->getResult();
	}


	/**
	 * Data source prototype
	 *
	 * @param array $filter
	 * @param array $sorting
	 * @return QueryBuilder
	 */
	public function dataSource( $filter, $sorting )
	{
		return new QueryBuilder();
	}

	// design


	/**
	 * Button label getter
	 *
	 * @param $name
	 * @return string
	 */
	public function getLabel( $name )
	{
		return $this->labels[ $name ];
	}


	/**
	 * Button label setter
	 *
	 * @param $name
	 * @param $label
	 * @return self (fluent interface)
	 */
	public function setLabel( $name, $label )
	{
		$this->labels[ $name ] = $label;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getPagerButtons()
	{
		if ( !$this->pagerButtons ) $this->pagerButtons = self::PAGER_BUTTONS;
		return $this->pagerButtons;
	}


	/**
	 * Customize pager look
	 *
	 * @param bool $first
	 * @param bool $last
	 * @param int $quick
	 * @param int $quickStep
	 * @param bool $number
	 * @return self (fluent interface)
	 */
	public function setPagerButtons( $first = FALSE,
	                                 $last = FALSE,
	                                 $quick = 0,
	                                 $quickStep = 1,
	                                 $number = TRUE
	)
	{
		// quick buttons only odd number, 3 to 15
		if ( $quick > 0 && $quick < 3 || $quick > 15 )
			throw new InvalidArgumentException( "Define 3 to 15 quick buttons." );
		if ( $quick % 2 === 0 )
			throw new InvalidArgumentException( "Define only odd number of quick buttons." );

		$this->pagerButtons = [
			'first'      => $first,
			'last'       => $last,
			'quick'      => $quick,
			'quick_step' => $quickStep,
			'number'     => $number,
		];
		return $this;
	}


	/**
	 * @param int $page
	 * @return self (fluent interface)
	 */
	public function switchPage( $page = 1 )
	{
		$this->setCurrentPage( $page );
		$this->redrawControl( 'grid' );
		return $this;
	}

	// template / JS


	/**
	 * Grid builder
	 */
	protected function build()
	{
	}


	/**
	 * Add definition layer
	 *
	 * @param $path
	 * @return self (fluent interface)
	 */
	public function addStencil( $path )
	{
		$this->stencils[] = $path;
		return $this;
	}


	/**
	 * Action column width
	 *
	 * @param $width
	 * @return self (fluent interface)
	 */
	public function setActionWidth( $width )
	{
		$this->actionWidth = $width;
		return $this;
	}


	/**
	 * Emergency title
	 *
	 * @param $title
	 * @return self (fluent interface)
	 */
	public function setTitle( $title = '<em>ACGrid</em>' )
	{
		$this->actitle = $title;
		return $this;
	}


	/**
	 * Emergency footer
	 *
	 * @param $footer
	 * @return self (fluent interface)
	 */
	public function setFooter( $footer = '<strong>ACGrid 2017</strong>' )
	{
		$this->acfooter = $footer;
		return $this;
	}

	// signal receivers


	/**
	 * Signal handler
	 *
	 * @param $cmd
	 * @param null $id
	 */
	public function handleServer( $cmd, $id = NULL )
	{
		switch ( $cmd ) {
			case self::CMD[ 'ADD' ]:
				$this->addRecord();
				break;
			case self::CMD[ 'EDIT' ]:
				$this->setId( $id );
				$this->redrawControl( 'grid' );
				break;
			case self::CMD[ 'REMOVE' ]:
				$this->removeRecord( $id );
				break;
			case self::CMD[ 'ORDER' ]:
				$this->switchSortDirection( $id );
				$this->redrawControl( 'grid' );
				break;
			case self::CMD[ 'RESET_SORT' ]:
				$this->resetSort();
				$this->redrawControl( 'grid' );
				break;
			case self::CMD[ 'PG_FIRST' ]:
				$this->switchPage( 1 );
				break;
			case self::CMD[ 'PG_PREV' ]:
				$this->switchPage( $this->getCurrentPage() - 1 );
				break;
			case self::CMD[ 'PG_NEXT' ]:
				$this->switchPage( $this->getCurrentPage() + 1 );
				break;
			case self::CMD[ 'PG_LAST' ]:
				$this->switchPage( $this->getPageCount() );
				break;
			case self::CMD[ 'PG_QUICK' ]:
				$this->switchPage( $id );
				break;
		}
	}

	// render component


	/**
	 * Render DataGrid
	 */
	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/dataGrid.latte' );

		$template->grid = $this;
		$template->stencils = $this->stencils;
		$template->actionWidth = $this->actionWidth;
		$template->actitle = $this->actitle;
		$template->acfooter = $this->acfooter;
		$template->cmd = self::CMD;
		$template->labels = $this->labels;
		$template->columns = $this->columns;
		$template->key = $this->key;
		$template->isSorting = $this->isSorting();
		$template->isFiltering = $this->isFiltering();
		$template->isAdding = $this->isAdding();
		$template->isRemoving = $this->isRemoving();
		$template->isEditing = $this->isEditing();
		$template->hasActions = $this->hasActions();
		$template->sortable = $this->sortable;
		$template->sorting = $this->sorting;
		$template->filtering = $this->filtering;
		$template->id = $this->id;
		$template->pgbtn = $this->getPagerButtons();
		$template->currentPage = $this->getCurrentPage();
		$template->rowsPerPage = $this->getRowsPerPage();
		$template->pageCount = $this->getPageCount();

		if ( count( $this->dataSnippet ) ) $template->data = $this->dataSnippet;
		else $template->data = $this->getData();

		$template->render();
	}
}