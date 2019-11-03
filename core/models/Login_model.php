<?php

defined('_EXEC') or die;

class Login_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function findUser( $user )
	{
		$return = $this->database->select('users', [
			'id_user (id)',
			'name',
			'email',
			'phone_number',
			'username',
			'password',
			'level',
			'status',
			'registration_date',
			'avatar',
			'id_subscription',
			'id_branch_office'
		], [
			'username' => $user,
			'LIMIT' => 1
		]);

		return ( isset($return[0]) && !empty($return[0]) ) ? $return[0] : null;
	}

}
