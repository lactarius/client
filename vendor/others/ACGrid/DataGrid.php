<?php

namespace ACGrid;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Http\Session;

/**
 * @author Petr Blazicek 2017
 */
class DataGrid extends Control
{

	const CMD = [
		'ADD'    => 1,
		'EDIT'   => 2,
		'REMOVE' => 3,
		'ORDER'  => 4,
	];

	const SORT_OFF = 0;
	const SORT_ASC = 1;
	const SORT_DESC = 2;

	/** @var Session */
	protected $session;


	// labels

	protected $labels = [
		'new'    => 'New',
		'edit'   => 'Edit',
		'remove' => 'Remove',
	];

	// structure

	/** @var  Column|array */
	protected $columns = [];

	/** @var  array */
	protected $filters = [];

	/**
	 * @var array
	 */
	public $sortingCols = [];

	/**
	 * @var array
	 */
	public $sortingDirs = [];

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


	public function __construct( Session $session )
	{
		$this->session = $session;
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

		$form[ 'inner' ] = $this->createEditContainer();

		$form->addSubmit( 'save', 'Save' );
		$form->addSubmit( 'cancel', 'Cancel' );
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


	/**
	 * @return Session
	 */
	public function getSession(): Session
	{
		return $this->session;
	}


	// content


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

	// sorting


	/**
	 * Add new sortable column
	 *
	 * @param $col
	 * @param $dir
	 * @return self (fluent interface)
	 */
	public function addOrder( $col, $dir )
	{
		$this->sortingCols[] = $col;
		$this->sortingDirs[ $col ] = $dir;
		return $this;
	}


	/**
	 * Sorting direction switch
	 *
	 * @param $col
	 * @return self (fluent interface)
	 */
	public function switchOrder( $col )
	{
		$dir = $this->sortingDirs[ $col ];
		$dir = $dir === self::SORT_DESC ? 0 : $dir++;
		$this->sortingDirs[ $col ] = $dir;
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


	public function saveRecord( Form $form )
	{
	}


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
		/** // when a snippet is prepared
		if(count($this->dataSnippet)){
			return $this->dataSnippet;
		}
		    // normal data
		return $database->getData();
		*/
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
	 * Grid width
	 *
	 * @param $width
	 * @return self (fluent interface)
	 */
	public function setWidth( $width )
	{
		$this->width = $width;
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
				$this->switchOrder( $id );
				$this->redrawControl( 'header' );
		}
	}

	// render component


	/**
	 * Render DataGrid
	 */
	public function render()
	{
		$this->build();

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
		$template->sort_cols = $this->sortingCols;
		$template->sort_dirs = $this->sortingDirs;
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