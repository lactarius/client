<?php

namespace Client\Components\Grids;

use Client\Model\Commodity;
use Nette\Utils\Callback;
use Nextras\Datagrid\Datagrid;

/**
 * @author Petr Blazicek 2017
 */
class DataGridAdjust extends Datagrid
{

	/** @var  mixed */
	protected $newRecordCallback;


	public function setNewRecordCallback( $newRecordCallback )
	{
		Callback::check( $newRecordCallback );
		$this->newRecordCallback = $newRecordCallback;
	}


	public function handleNew()
	{
		Callback::invokeArgs( $this->newRecordCallback );
		$this->flashMessage( 'New record added. Edit it!' );
		$this->invalidateControl( 'rows' );
	}


	public function handleGridForm()
	{
		echo 'xx';
	}
}