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

	/** @var  bool */
	protected $editable = FALSE;

	/** @var  string */
	protected $firstEdit;

	/** @var  callable */
	protected $dataSource;

	/** @var  callable */
	protected $saveData;

	/** @var  array */
	protected $defTemplates = [];


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


	/**
	 * @return $this
	 */
	public function makeEditable()
	{
		$this->editable = TRUE;
		return $this;
	}


	public function setFirstEdit( $name )
	{
		$this->firstEdit = $name;
	}


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


	public function setSaveData( $saveData )
	{
		Callback::check( $saveData );
		$this->saveData = $saveData;
	}


	/**
	 * @return array
	 */
	public function getData()
	{
		return Callback::invokeArgs( $this->dataSource, [ $this->filters, $this->sorts ] );
	}


	/**
	 * @param $path
	 * @return self (fluent interface)
	 */
	public function addDefTemplate( $path )
	{
		$this->defTemplates[] = $path;
		return $this;
	}


	public function getJsOptions()
	{
		$opts = [
			'server'   => $this->link( 'server!' ),
			'editable' => $this->editable,
			'cols'     => $this->getColumns(),
			'first'    => $this->firstEdit,
		];

		return json_encode( $opts );
	}


	public function handleServer()
	{
		$result = FALSE;
		$post = $this->presenter->getRequest()->getPost();

		$entity = Callback::invokeArgs( $this->saveData, [ $post ] );
		if ( $entity ) {
			$result = TRUE;
		}
		$this->presenter->payload->result = $result;
		$this->presenter->sendPayload();
	}


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
		$template->jsOpts = $this->getJsOptions();
		$template->data = $this->getData();
		$template->defTemplates = $this->defTemplates;

		$template->render();
	}
}