<?php

defined('_EXEC') or die;

class Providers_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Proveedores
	--------------------------------------------------------------------------- */
	public function getAllProviders()
	{
		$query = $this->database->select('providers', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getProviderById($id)
	{
		$query = $this->database->select('providers', '*', ['id_provider' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newProvider($name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalAddress)
	{
		$today = date('Y-m-d');

		$query = $this->database->insert('providers', [
			'name' => $name,
			'email' => $email,
			'phone_number' => $phoneNumber,
			'address' => $address,
			'fiscal_country' => $fiscalCountry,
			'fiscal_name' => $fiscalName,
			'fiscal_code' => $fiscalCode,
			'fiscal_address' => $fiscalAddress,
			'registration_date' => $today,
			'id_subscription' => Session::getValue('id_subscription')
		]);

		return $query;
	}

    public function editProvider($id, $name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalAddress)
	{
		$query = $this->database->update('providers', [
			'name' => $name,
			'email' => $email,
			'phone_number' => $phoneNumber,
			'address' => $address,
			'fiscal_country' => $fiscalCountry,
			'fiscal_name' => $fiscalName,
			'fiscal_code' => $fiscalCode,
			'fiscal_address' => $fiscalAddress
		], ['id_provider' => $id]);

		return $query;
	}

	public function changeStatusProviders($selection, $status)
    {
		$query = $this->database->update('providers', [
            'status' => $status
        ], ['id_provider' => $selection]);

        return $query;
    }

	public function deleteProviders($selection)
    {
		$query = $this->database->delete('providers', [
            'id_provider' => $selection
        ]);

        return $query;
    }

	public function checkExistProvider($id, $name, $fiscalName, $fiscalCode, $action)
	{

		if (isset($fiscalName) AND !isset($fiscalCode))
		{
			$query	= $this->database->select('providers', '*', [
				'AND' => [
					'OR' => [
						'name' => $name,
						'fiscal_name' => $fiscalName
					],
					'id_subscription' => Session::getValue('id_subscription')
				]
			]);
		}
		else if (isset($fiscalCode) AND !isset($fiscalName))
		{
			$query	= $this->database->select('providers', '*', [
				'OR' => [
					'name' => $name,
					'fiscal_code' => $fiscalCode
				]
			]);
		}
		else if (isset($fiscalCode) AND isset($fiscalName))
		{
			$query	= $this->database->select('providers', '*', [
				'OR' => [
					'name' => $name,
					'fiscal_name' => $fiscalName,
					'fiscal_code' => $fiscalCode
				]
			]);
		}
		else if (!isset($fiscalCode) AND !isset($fiscalName))
			$query	= $this->database->select('providers', '*', ['name' => $name]);

		if (!empty($query))
		{
			$errorName       = false;
			$errorFiscalName = false;
			$errorFiscalCode = false;

			foreach ($query as $data)
			{
				if ($action == 'new' AND $name == $data['name'])
					$errorName = true;
				else if ($action == 'edit' AND $name == $data['name'] AND $id != $data['id_provider'])
					$errorName = true;

				if ($action == 'new' AND isset($fiscalName) AND $fiscalName == $data['fiscal_name'])
					$errorFiscalName = true;
				else if ($action == 'edit' AND isset($fiscalName) AND $fiscalName == $data['fiscal_name'] AND $id != $data['id_provider'])
					$errorFiscalName = true;

				if ($action == 'new' AND isset($fiscalCode) AND $fiscalCode == $data['fiscal_code'])
					$errorFiscalCode = true;
				else if ($action == 'edit' AND isset($fiscalCode) AND $fiscalCode == $data['fiscal_code'] AND $id != $data['id_provider'])
					$errorFiscalCode = true;
			}

			if ($errorName == true OR $errorFiscalName == true OR $errorFiscalCode == true)
				return ['status' => true, 'errors' => ['errorName' => $errorName, 'errorFiscalName' => $errorFiscalName, 'errorFiscalCode' => $errorFiscalCode]];
			else
				return ['status' => false];
		}
		else
			return ['status' => false];
	}

	/* Países
	--------------------------------------------------------------------------- */
	public function getAllCountries()
	{
		$query = $this->database->select('settings_countries', '*', ['ORDER' => 'name ASC']);
		return $query;
	}
}
