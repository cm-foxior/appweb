<?php

defined('_EXEC') or die;

class Subscriptions_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function newSubscription($business, $email, $type)
	{
		$generalSettings = json_encode([]);

		$salesSettings = json_encode([
			'main_coin' => 'MXN',
			'apply_discounds' => true,
			'sell_fractions' => false,
			'sell_extras' => false,
			'sale_ticket_print' => true,
			'sale_ticket_totals_breakdown' => false,
			'sale_ticket_legends' => '',
			'iva_rate' => 16,
			'usd_rate' => 17
		]);

		$pdisSettings = json_encode([]);
		$subscriptionDate = date('Y-m-d');
		$cutoffDate = strtotime ('+1 month', strtotime ($subscriptionDate));
		$cutoffDate = date ('Y-m-d', $cutoffDate);

		if ($type == 'basic')
		{
			$type = '1';
			$price = null;
			$status = false;
		}
		else if ($type == 'standard')
		{
			$type = '2';
			$price = null;
			$status = false;
		}
		else if ($type == 'premium')
		{
			$type = '3';
			$price = null;
			$status = false;
		}
		else if ($type == 'test')
		{
			$type = '4';
			$price = 0.0;
			$status = true;
		}

		$query = $this->database->insert('subscriptions', [
			'business' => $business,
			'email' => $email,
			'generals_settings' => $generalSettings,
			'sales_settings' => $salesSettings,
			'pdis_settings' => $pdisSettings,
			'subscription_date' => $subscriptionDate,
			'cutoff_date' => $cutoffDate,
			'type' => $type,
			'price' => $price,
			'status' => $status
		]);

		return $query;
	}

	public function newUser($name, $username, $password, $typeSubscription, $idSubscription)
	{
		$today = date('Y-m-d');

		if ($typeSubscription == 'basic' OR $typeSubscription == 'standard' OR $typeSubscription == 'premium')
			$status = false;
		else if ($typeSubscription == 'test')
			$status = true;

		$query = $this->database->insert('users', [
			'name' => $name,
			'username' => $username,
			'password' => $password,
			'level' => '10',
			'status' => $status,
			'registration_date' => $today,
			'representative' => true,
			'id_subscription' => $idSubscription
		]);

		return $query;
	}

	public function checkExistSubscriptionAndUser($email, $username)
	{
		$subscriptions = $this->database->select('subscriptions', '*', ['email' => $email]);
		$users = $this->database->select('users', '*', ['username' => $username]);

		if (!empty($subscriptions) OR !empty($users))
		{
			$errorEmail = false;
			$errorUsername = false;

			foreach ($subscriptions as $subscription)
			{
				if ($email == $subscription['email'])
					$errorEmail = true;
			}

			foreach ($users as $user)
			{
				if ($username == $user['username'])
					$errorUsername = true;
			}

			if ($errorEmail == true OR $errorUsername == true)
				return ['status' => true, 'errors' => ['errorEmail' => $errorEmail, 'errorUsername' => $errorUsername]];
			else
				return ['status' => false];
		}
		else
			return ['status' => false];
	}
}
