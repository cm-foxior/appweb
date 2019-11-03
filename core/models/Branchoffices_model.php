<?php

defined('_EXEC') or die;

class Branchoffices_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Sucursales
	--------------------------------------------------------------------------- */
	public function getAllBranchOffices()
	{
		$query = $this->database->select('branch_offices', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getBranchOfficeById($id)
	{
		$query = $this->database->select('branch_offices', '*', ['id_branch_office' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newBranchOffice($name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalRegime, $fiscalAddress)
	{
        $today = date('Y-m-d');

        $query = $this->database->insert('branch_offices', [
            'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'address' => $address,
            'fiscal_country' => $fiscalCountry,
            'fiscal_name' => $fiscalName,
            'fiscal_code' => $fiscalCode,
            'fiscal_regime' => $fiscalRegime,
            'fiscal_address' => $fiscalAddress,
            'registration_date' => $today,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

        return $query;
	}

    public function editBranchOffice($id, $name, $email, $phoneNumber, $address, $fiscalCountry, $fiscalName, $fiscalCode, $fiscalRegime, $fiscalAddress)
	{
        $query = $this->database->update('branch_offices', [
			'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'address' => $address,
            'fiscal_country' => $fiscalCountry,
            'fiscal_name' => $fiscalName,
            'fiscal_code' => $fiscalCode,
            'fiscal_regime' => $fiscalRegime,
            'fiscal_address' => $fiscalAddress
        ], ['id_branch_office' => $id]);

        return $query;
	}

	public function changeStatusBranchOffices($selection, $status)
    {
		$query = $this->database->update('branch_offices', [
            'status' => $status
        ], ['id_branch_office' => $selection]);

        return $query;
    }

	public function deleteBranchOffices($selection)
    {
		$query = $this->database->delete('branch_offices', [
            'id_branch_office' => $selection
        ]);

        return $query;
    }

	public function checkExistBranchOffice($id, $name, $action)
	{
		$query = $this->database->select('branch_offices', '*', [
			'AND' => [
				'name' => $name,
				'id_subscription' => Session::getValue('id_subscription')
			]
		]);

		if ($action == 'new' AND !empty($query))
			return true;
		else if ($action == 'edit' AND !empty($query) AND $id != $query[0]['id_branch_office'])
			return true;
		else
			return false;
	}
}
