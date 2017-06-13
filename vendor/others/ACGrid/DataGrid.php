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
		'ADD'    => 1,
		'EDIT'   => 2,
		'REMOVE' => 3,
	];

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

	/** @var  array */
	protected $sorts = [];

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

	// design

	/** @var  array */
	protected $stencils = [];


	// factories

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


	public function createEditContainer()
	{
	}

	// content


	/**
	 * Add new grid column
	 *
	 * @param $name
	 * @param null $label
	 * @param int $type
	 * @return $this
	 */
	public function addColumn( $name, $label = NULL, $type = Column::TYPE_TEXT )
	{
		$this->columns[] = new Column( $name, $label, $type );
		return $this;
	}


	/**
	 * Return column list [ name => type ]
	 *
	 * @return array
	 */
	public function getColumnList()
	{
		foreach ( $this->columns as $column ) {
			$cols[ $column->name ] = $column->type;
		}

		return $cols;
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


	public function removeRecord( $id )
	{
	}


	// data source

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
		$template->cmd = self::CMD;
		$template->labels = $this->labels;
		$template->columns = $this->columns;
		$template->key = $this->key;
		$template->editable = $this->editable;
		$template->adding = $this->adding;
		$template->removable = $this->removable;
		$template->actions = $this->getActions();
		$template->jsOpts = $this->getJsOptions();
		$template->data = $this->dataSource();
		$template->id = $this->id;

		$template->render();
	}
}