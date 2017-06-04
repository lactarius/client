<?php

namespace Location\Components\Forms;

use Nette\ComponentModel\IContainer;
use Nette\Forms\Container;

/**
 * @author Petr Blazicek 2017
 */
class LocContainer extends Container
{

	// search input ID
	const AUTOCOMPLETE_ID = 'lc_autocomplete';


	/**
	 * LocContainer constructor.
	 * @param IContainer|NULL $parent
	 * @param null $name
	 */
	public function __construct( IContainer $parent = NULL, $name = NULL )
	{
		parent::__construct( $parent, $name );

		$this->addHidden( 'formatted' );

		$this->addHidden('lat');

		$this->addHidden('lng');

		$this->addText( 'place', 'Searched place:' )
			->setAttribute( 'id', self::AUTOCOMPLETE_ID );

		$this->addText( 'street', 'Street:' );

		$this->addText( 'reg_nr', 'Registration number:' );

		$this->addText( 'house_nr', 'House number:' );

		$this->addText( 'postal', 'Postal code:' );

		$this->addText( 'district', 'District:' );

		$this->addText( 'part', 'Part:' );

		$this->addText( 'city', 'City:' );

		$this->addText( 'region', 'Region:' );

		$this->addText( 'country', 'Country:' );
	}
}