<?php

namespace ACGrid;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;

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

	/** @var  bool */
	protected $filterable = FALSE;

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

		$form[ 'edit' ] = $this->createEditContainer();

		$form->addSubmit( 'saveRecord','Save' );
		$form->addSubmit( 'cancelRecord','Cancel' );
		$form->onSuccess[] = [ $this, 'saveRecord' ];

		return $form;
	}


	/**
	 * Edit container prototype
	 *
	 * @return Container
	 */
	public function createEditContainer()
	{
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
	 * Return column list
	 *
	 * @return array
	 */
	public function getColumnList()
	{
		return array_keys( $this->columns );
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


	// filters

	public function allowFilter()
	{
		$this->filterable = TRUE;
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
	 * Make grid editable
	 *
	 * @return self (fluent interface)
	 */
	public function allowEdit()
	{
		$this->editable = TRUE;
		return $this;
	}


	/**
	 * Dummy record can be added
	 *
	 * @return self (fluent interface)
	 */
	public function allowAdd()
	{
		$this->adding = TRUE;
		return $this;
	}


	/**
	 * Records can be erased
	 *
	 * @return self (fluent interface)
	 */
	public function allowRemove()
	{
		$this->removable = TRUE;
		return $this;
	}


	/**
	 * Any action?
	 *
	 * @return bool
	 */
	public function getActions()
	{
		return $this->adding || $this->editable || $this->removable;
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


	public function addRecord()
	{
	}


	public function setFilters( Form $form, array $values )
	{
	}


	/**
	public function saveRecord( Form $form, array $values )
	{
	}
	*/

	/**
	 * Remove record prototype
	 *
	 * @param $id
	 */
	public function removeRecord( $id )
	{
	}


	// data source


	/**
	 * Data source prototype
	 *
	 * @return array
	 */
	public function dataSource()
	{
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
	public function setTitle( $title )
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
	public function setFooter( $footer )
	{
		$this->acfooter = $footer;
		return $this;
	}


	/**
	 * Assemble javascript options
	 *
	 * @return string
	 */
	public function getJsOptions()
	{
		$opts = [
			'server'   => $this->link( 'server!' ),
			'editable' => $this->editable,
			'adding'   => $this->adding,
			'cols'     => $this->getColumnList(),
		];

		return json_encode( $opts );
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
		$template->action_width = $this->actionWidth;
		$template->actitle = $this->actitle;
		$template->acfooter = $this->acfooter;
		$template->cmd = self::CMD;
		$template->labels = $this->labels;
		$template->columns = $this->columns;
		$template->key = $this->key;
		$template->filterable = $this->filterable;
		$template->filters = $this->filters;
		$template->sort_dirs = $this->sortDirs;
		$template->sort_cols = $this->sortCols;
		$template->editable = $this->editable;
		$template->adding = $this->adding;
		$template->removable = $this->removable;
		$template->actions = $this->getActions();
		$template->js_opts = $this->getJsOptions();
		$template->data = $this->dataSource();
		$template->id = $this->id;

		$template->render();
	}
}