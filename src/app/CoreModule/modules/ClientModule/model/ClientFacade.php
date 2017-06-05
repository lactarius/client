<?php

namespace Client\Model;

use Core\Model\BaseFacade;
use Kdyby\Doctrine\EntityManager;
use Nette\DateTime;
use Nette\Utils\Strings;

/**
 * @author Petr Blazicek 2017
 */
class ClientFacade extends BaseFacade
{

	/** @var \Kdyby\Doctrine\EntityRepository */
	private $repo;


	/**
	 * ClientFacade constructor.
	 * @param EntityManager $em
	 */
	public function __construct( EntityManager $em )
	{
		parent::__construct( $em );
		$this->repo = $em->getRepository( Client::class );
	}


	/**
	 * @return \Kdyby\Doctrine\EntityRepository
	 */
	public function getRepo()
	{
		return $this->repo;
	}


	/**
	 * Save Client data
	 *
	 * @param $data
	 * @return Client|mixed
	 */
	public function saveClient( $data )
	{
		$client = $this->prepareEntity( Client::class, $data );
		$client = ClientService::saveClient( $client, $data );
		$this->saveAll( $client );

		return $client;
	}


	/**
	 * @param $data
	 * @return Profile|mixed
	 */
	public function saveProfile( $data )
	{
		$profile = $this->prepareEntity( Profile::class, $data );
		$profile = ClientService::saveProfile( $profile, $data );
		$this->saveAll( $profile );

		return $profile;
	}

	// tools


	/**
	 * Generate new card of certain type
	 *
	 * @param $type
	 * @return Card
	 */
	public function generateCard( $type )
	{
		if ( !$type ) return NULL;

		$cardRepo = $this->repo->related( 'cards' );

		$now = new DateTime();
		$date = $now->format( 'Ymd' );

		$latestCard = $cardRepo->findOneBy( [], [ 'id' => 'DESC' ] );
		if ( $latestCard ) {
			$latestNumber = $latestCard->getNumber();
			$latestDate = Strings::substring( $latestNumber, 0, 8 );
			$latestCount = Strings::substring( $latestNumber, 8, 8 );
		}
		if ( !$latestCard || $date > $latestDate ) {
			$count = 1;
		} else {
			$count = $latestCount + 1;
		}

		$number = sprintf( '%s%08u', $date, $count );

		$card = new Card();
		$card->setNumber( $number )->setType( $type );
		$this->saveAll( $card );

		return $card;
	}
}