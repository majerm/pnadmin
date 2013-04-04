<?php

namespace PNAdmin;

use Nette\DI\Container;
use \Nette\Security,
	\Nette\Utils\Strings;


/**
 * Users authenticator.
 */
class Authenticator extends \Nette\Object implements Security\IAuthenticator
{
	/** @var \Nette\DI\Container */
	private $container;



	public function __construct(Container $container)
	{
		$this->container = $container;
	}



	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{

		list($username, $password) = $credentials;

		$user = $this->container->admin->get(array('login' => $username));
		if (!$user) {
			throw new Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		}

		if (!$user->authenticate($password)) {
			throw new Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}

		return new Security\Identity($user->id, $user->role, $user->toArray());
	}

}
