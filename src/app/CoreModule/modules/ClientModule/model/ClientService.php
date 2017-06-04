<?php

namespace Client\Model;

use Nette\Security\Passwords;

/**
 * @author Petr Blazicek 2017
 */
class ClientService
{

	/**
	 * @param Client $client
	 * @param $data
	 * @return Client
	 */
	public static function saveClient( Client $client, $data )
	{
		if ( isset( $data[ 'name' ] ) ) $client->setName( $data[ 'name' ] );
		if ( isset( $data[ 'surname' ] ) ) $client->setSurname( $data[ 'surname' ] );
		if ( isset( $data[ 'email' ] ) ) $client->setEmail( $data[ 'email' ] );
		if ( isset( $data[ 'password' ] ) ) $client->setPassword( Passwords::hash( $data[ 'password' ] ) );

		return $client;
	}


	/**
	 * @param Client $client
	 * @return array
	 */
	public static function restoreClient( Client $client )
	{
		return [
			'id'      => $client->getId(),
			'name'    => $client->getName(),
			'surname' => $client->getSurname(),
			'email'   => $client->getEmail(),
		];
	}


	/**
	 * @param Profile $profile
	 * @param $data
	 * @return Profile
	 */
	public static function saveProfile( Profile $profile, $data )
	{
		if ( isset( $data[ 'sex' ] ) ) $profile->setSex( $data[ 'sex' ] );
		if ( isset( $data[ 'phone' ] ) ) $profile->setPhone( $data[ 'phone' ] );
		if ( isset( $data[ 'photo' ] ) ) $profile->setPhoto( $data[ 'photo' ] );

		return $profile;
	}


	/**
	 * @param Profile $profile
	 * @return array
	 */
	public static function restoreProfile( Profile $profile )
	{
		return [
			'sex'   => $profile->getSex(),
			'phone' => $profile->getPhone(),
			'photo' => $profile->getPhoto(),
		];
	}
}