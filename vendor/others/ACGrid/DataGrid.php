<?php

namespace ACGrid;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

/**
 * @author Petr Blazicek 2017
 */
class DataGrid extends Control
{

	const CMD = [
		'ADD'        => 1,
		'EDIT'       => 2,
		'REMOVE'     => 3,
		'ORDER'      => 4,
		'RESET_SORT' => 5,
	];

	const SESSION_SECTION = 'acgrid';

	const SORT_NOT_SORTABLE = NULL;
	const SORT_OFF = 0;
	const SORT_ASC = 1;
	const SORT_DESC = 2;

	const DIR = [ 1 => 'ASC', 2 => 'DESC' ];

	/** @var mixed */
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

	// data structure

	/** @var  Column|array */
	protected $columns = [];

	/** @persistent */
	public $filters = [];

	/** @persistent */
	public $sortCols = [];

	/** @persistent */
	public $sortDirs = [];

	/** @var  mixed */
	protected $key = 'id';

	// editing

	/** @var  bool */
	protected $editable = FALSE;

	/** @var bool */
	protected $adding = FALSE;

	/** @var bool */
	protected $removable = FALSE;

	/** @var  mixed */
	protected $id;

	/** @var array */
	protected $dataSnippet = [];

	// design

	/** @var  array */
	protected $stencils = [];

	/** @var  int */
	protected $actionWidth = 2;

	/** @var  string */
	protected $actitle;

	/** @var  string */
	protected $acfooter;


	/**
	 * DataGrid constructor.
	 */
	public function __construct()
	{
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
		}

		if ( $this->isEditing() ) {
			$form[ 'edit' ] = new EditForm( $this );
		}

		if ( $this->isFiltering() ) {
			$form->addSubmit( 'setFilter', 'Filter' );
			$form->addSubmit( 'resetFilter', 'Reset' );
		}

		if ( $this->isEditing() ) {
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
			//file_put_contents( TEMP_DIR . '/sort.txt', var_export( $data, TRUE ) );

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

	// data


	/**
	 * Add new grid column
	 *
	 * @param $name
	 * @param null $label
	 * @param int $type
	 * @return Column
	 */
	public function addColumn( $name, $label = NULL, $type = Column::TYPE_TEXT )
	{
		$column = new Column( $name, $label, $type );
		$column->setGrid( $this );
		$this->columns[ $name ] = $column;
		return $column;
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
	 * Order of sorting
	 *
	 * @param $col
	 * @param $dir
	 * @return self (fluent interface)
	 */
	public function setSort( $col, $dir )
	{
		$order = array_search( $col, $this->sortCols );
		if ( $dir && !is_int( $order ) ) $this->sortCols[] = $col;
		if ( $dir == self::SORT_OFF && is_int( $order ) ) {
			unset( $this->sortCols[ $order ] );
			$this->sortCols = array_values( $this->sortCols );
		}

		return $this;
	}


	/**
	 * Switch sort direction
	 *
	 * @param $col
	 * @return self (fluent interface)
	 */
	public function switchSort( $col )
	{
		$dir = $this->sortDirs[ $col ];
		$dir = $dir == self::SORT_DESC ? self::SORT_OFF : $dir + 1;
		$this->sortDirs[ $col ] = $dir;
		$this->setSort( $col, $dir );
		return $this;
	}


	/**
	 * Reset sorting
	 *
	 * @return self (fluent interface)
	 */
	public function resetSort()
	{
		$this->sortCols = [];
		$this->sortDirs = array_fill_keys( array_keys( $this->sortDirs ), self::SORT_OFF );
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
		return count( $this->sortDirs ) > 0;
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
				$this->switchSort( $id );
				$this->redrawControl( 'grid' );
				break;
			case self::CMD[ 'RESET_SORT' ]:
				$this->resetSort();
				$this->redrawControl( 'grid' );
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
		$template->sortDirs = $this->sortDirs;
		$template->sortCols = $this->sortCols;
		$template->filters = $this->filters;
		$template->data = $this->dataSource();
		$template->id = $this->id;

		$template->render();
	}
}