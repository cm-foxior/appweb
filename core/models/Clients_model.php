<?php

defined('_EXEC') or die;

class Clients_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Clientes
	--------------------------------------------------------------------------- */
	public function getAllClients()
	{
		$query = $this->database->select('clients', '*', [
			'AND' => [
				'prospect' => false,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);
		return $query;
	}

	public function getClientById($id)
	{
		$query = $this->database->select('clients', '*', ['id_client' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newClient($name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalAddress, $type)
	{
        $today = date('Y-m-d');

        $query = $this->database->insert('clients', [
            'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'address' => $address,
            'fiscal_country' => $fiscalCountry,
            'fiscal_name' => $fiscalName,
            'fiscal_code' => $fiscalCode,
            'fiscal_address' => $fiscalAddress,
            'type' => $type,
            'registration_date' => $today,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

        return $query;
	}

    public function editClient($id, $name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalAddress, $type)
	{
        $query = $this->database->update('clients', [
			'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'address' => $address,
            'fiscal_country' => $fiscalCountry,
            'fiscal_name' => $fiscalName,
            'fiscal_code' => $fiscalCode,
            'fiscal_address' => $fiscalAddress,
            'type' => $type
        ], ['id_client' => $id]);

        return $query;
	}

	public function changeStatusClients($selection, $status)
    {
		$query = $this->database->update('clients', [
            'status' => $status
        ], ['id_client' => $selection]);

        return $query;
    }

	public function deleteClients($selection)
    {
		$query = $this->database->delete('clients', [
            'id_client' => $selection
        ]);

        return $query;
    }

	public function checkExistClient($id, $name, $action)
	{
		$query = $this->database->select('clients', '*', [
			'name' => $name
		]);

		if (!empty($query))
		{
			$errorName		 	= false;

			foreach ($query as $data)
			{
				if ($action == 'new' AND $email == $data['name'])
					$errorName = true;
				else if ($action == 'edit' AND $email == $data['name'] AND $id != $data['id_client'])
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
