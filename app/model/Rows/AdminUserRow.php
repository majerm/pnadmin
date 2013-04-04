<?php

namespace PNAdmin;


/**
 * Users object.
 */
class AdminUserRow extends \Nette\Database\Table\ActiveRow
{


	/**
	 * Performs an authentication.
	 * @param string $password
	 * @return bool
	 */
	public function authenticate($password)
	{
		return crypt($password, $this->password_crypt) == $this->password_crypt;
	}

}
