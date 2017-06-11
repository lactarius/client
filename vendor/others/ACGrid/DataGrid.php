<?php

namespace ACGrid;

use Nette\Application\UI\Control;
use Nette\Forms\Form;
use Nette\InvalidArgumentException;
use Nette\Utils\Callback;

/**
 * @author Petr Blazicek 2017
 */
class DataGrid extends Control
{

	const CMD_ADD = 1;
	const CMD_EDIT = 2;
	const CMD_REMOVE = 3;

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

	// design

	/** @var  array */
	protected $stencils = [];


	// factories

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


	public function addRecord()
	{
	}


	public function editRecord( $id, $data )
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
	 * @param null $data
	 */
	public function handleServer( $cmd, $data = NULL )
	{
		switch ( $cmd ) {
			case self::CMD_ADD:
				$this->addRecord();
				break;
			case self::CMD_EDIT:
				break;
			case self::CMD_REMOVE:
				$this->removeRecord( $data );
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
		$template->columns = $this->columns;
		$template->key = $this->key;
		$template->editable = $this->editable;
		$template->adding = $this->adding;
		$template->jsOpts = $this->getJsOptions();
		$template->data = $this->dataSource();
		$template->stencils = $this->stencils;

		$template->render();
	}
}