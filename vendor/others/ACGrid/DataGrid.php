<?php

namespace ACGrid;

use Nette\Application\UI\Control;
use Nette\Utils\Callback;

/**
 * @author Petr Blazicek 2017
 */
class DataGrid extends Control
{


	/** @var  Column|array */
	protected $columns = [];

	/** @var  mixed */
	protected $key = 'id';

	/** @var  array */
	protected $filters = [];

	/** @var  array */
	protected $sorts = [];

	// editing

	/** @var  bool */
	protected $editable = FALSE;

	/** @var  string */
	protected $firstEdit;

	/** @var  callable */
	protected $saveData;

	/** @var bool */
	protected $adding = FALSE;

	/** @var  callable */
	protected $addNew;

	/** @var  callable */
	protected $dataSource;

	/** @var  array */
	protected $defTemplates = [];

	// design


	/**
	 * @param $name
	 * @param null $label
	 * @param int $type
	 * @return self (fluent interface)
	 */
	public function addColumn( $name, $label = NULL, $type = Column::TYPE_TEXT )
	{
		$this->columns[] = new Column( $name, $label, $type );
		if ( count( $this->columns ) === 1 && $type === Column::TYPE_TEXT ) $this->firstEdit = $name;
		return $this;
	}


	public function getColumns()
	{
		foreach ( $this->columns as $column ) {
			$cols[ $column->name ] = $column->type;
		}

		return $cols;
	}


	/**
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->key;
	}


	/**
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
	 * @return self (fluent interface)
	 */
	public function makeEditable()
	{
		$this->editable = TRUE;
		return $this;
	}


	/**
	 * @param $name
	 * @return self (fluent interface)
	 */
	public function setFirstEdit( $name )
	{
		$this->firstEdit = $name;
		return $this;
	}


	/**
	 * @param $saveData
	 * @return $this
	 */
	public function setSaveData( $saveData )
	{
		Callback::check( $saveData );
		$this->saveData = $saveData;
		return $this;
	}


	/**
	 * @return self (fluent interface)
	 */
	public function makeAdding()
	{
		$this->adding = TRUE;
		return $this;
	}


	/**
	 * @param $addNew
	 * @return self (fluent interface)
	 */
	public function setAddNew( $addNew )
	{
		Callback::check( $addNew );
		$this->addNew = $addNew;
		return $this;
	}

	// data source


	/**
	 * @param $dataSource
	 * @return self (fluent interface)
	 */
	public function setDataSource( $dataSource )
	{
		Callback::check( $dataSource );
		$this->dataSource = $dataSource;
		return $this;
	}


	/**
	 * @return array
	 */
	public function getData()
	{
		return Callback::invokeArgs( $this->dataSource, [ $this->filters, $this->sorts ] );
	}

	// template / JS


	/**
	 * @param $path
	 * @return self (fluent interface)
	 */
	public function addDefTemplate( $path )
	{
		$this->defTemplates[] = $path;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getJsOptions()
	{
		$opts = [
			'server'   => $this->link( 'server!' ),
			'editable' => $this->editable,
			'adding'   => $this->adding,
			'cols'     => $this->getColumns(),
			'first'    => $this->firstEdit,
		];

		return json_encode( $opts );
	}


	// signal receivers

	public function handleServer()
	{
		$result = FALSE;
		$post = $this->presenter->getRequest()->getPost();

		switch ( $post[ 'cmd' ] ) {
			case 'save':
				$entity = Callback::invokeArgs( $this->saveData, [ $post ] );
				break;
			case 'new':
				$entity = Callback::invoke( $this->addNew );
				break;
		};

		if ( $entity ) $result = TRUE;
		$this->presenter->payload->result = $result;
		$this->presenter->sendPayload();
	}

	// render component


	/**
	 * Render DataGrid
	 */
	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/dataGrid.latte' );
		$template->columns = $this->columns;
		$template->cols = $this->getColumns();
		$template->key = $this->key;
		$template->editable = $this->editable;
		$template->adding = $this->adding;
		$template->jsOpts = $this->getJsOptions();
		$template->data = $this->getData();
		$template->defTemplates = $this->defTemplates;

		$template->render();
	}
}