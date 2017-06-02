<?php

namespace ClientModule;


/**
 * Class BasePresenter
 *
 * @author Petr Blazicek 2017
 */
abstract class BasePresenter extends \CoreModule\BasePresenter
{


	public function beforeRender()
	{
		parent::beforeRender();
		$this->template->title = 'Client';
	}

}
