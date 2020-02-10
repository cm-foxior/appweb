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
		$query	= $this->database->select('providers', '*', [
			'name' => $name
		]);

		if (!empty($query))
		{
			$errorName = false;

			foreach ($query as $data)
			{
				if ($action == 'new' AND $name == $data['name'])
					$errorName = true;
				else if ($action == 'edit' AND $name == $data['name'] AND $id != $data['id_provider'])
					$errorName = true;
			}

			if ($errorName == true)
				return ['status' => true, 'errors' => ['errorName' => $errorName]];
			else
				return ['status' => false];
		}
		else
			return ['status' => false];
	}
}
