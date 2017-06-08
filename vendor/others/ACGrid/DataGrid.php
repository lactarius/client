<?php

namespace ACGrid;

use Kdyby\Doctrine\EntityRepository;
use Nette\Application\UI\Control;

/**
 * @author Petr Blazicek 2017
 */
class DataGrid extends Control
{


	/** @var  EntityRepository */
	protected $repo;

	/** @var  Column|array */
	protected $columns;

	/** @var  array */
	protected $data = [];


	/**
	 * @param EntityRepository $repo
	 * @return self (fluent interface)
	 */
	public function setRepo( EntityRepository $repo )
	{
		$this->repo = $repo;
		return $this;
	}


	/**
	 * @param $name
	 * @param null $label
	 */
	public function addColumn( $name, $label = NULL )
	{
		$this->columns[] = new Column( $name, $label );
	}


	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}


	/**
	 * @param array $data
	 * @return self (fluent interface)
	 */
	public function setData( $data )
	{
		$this->data = $data;
		return $this;
	}


	/**
	 * Render DataGrid
	 */
	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/dataGrid.latte' );
		$template->columns = $this->columns;
		$template->data = $this->data;

		$template->render();
	}
}