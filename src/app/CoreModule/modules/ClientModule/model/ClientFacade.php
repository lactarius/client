<?php

namespace Client\Model;

use Core\Model\BaseFacade;
use Kdyby\Doctrine\EntityManager;

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
}