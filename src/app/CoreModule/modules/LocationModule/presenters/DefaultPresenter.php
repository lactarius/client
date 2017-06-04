<?php

namespace LocationModule;

use Location\Components\Forms\ILocForm2Factory;


/**
 * Class DefaultPresenter
 *
 * @author Petr Blazicek 2016
 */
class DefaultPresenter extends BasePresenter
{

	/**
	 * public $geoFormFactory;
	 *
	 *
	 * protected function createComponentLocForm()
	 * {
	 * $control = $this->geoFormFactory->create();
	 * $control->setShort( FALSE );
	 * return $control;
	 * }
	 */

	/** @var  ILocForm2Factory @inject */
	public $locForm2Factory;


	protected function createComponentLocForm2()
	{
		$control = $this->locForm2Factory->create();
		return $control;
	}
}
