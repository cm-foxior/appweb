<?php

defined('_EXEC') or die;

class Boxcuts_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getAllBoxCuts()
    {
        $query = $this->database->select('box_cuts', '*');
        return $query;
    }

    public function getAllBoxCutsByBranchOffice($branchOffice)
    {
        $query = $this->database->select('box_cuts', '*', ['id_branch_office' => $branchOffice]);
        return $query;
    }

    public function getAllBoxCutsBySellerAndBranchOffices($seller, $branchOffice)
    {
        $query = $this->database->select('box_cuts', '*', [
			'AND' => [
				'id_user' => $seller,
				'id_branch_office' => $branchOffice
			]
		]);

        return $query;
    }

	/* Gastos adicionales
    --------------------------------------------------------------------------- */
	public function getAllExpenses()
    {
        $query = $this->database->select('box_cuts_expenses', '*');
        return $query;
    }

	public function getExpenseById($id)
	{
		$query = $this->database->select('box_cuts_expenses', '*', ['id_box_cut_expense' => $id]);
		return !empty($query) ? $query[0] : '';
	}

    public function getAllExpensesByBranchOffice($branchOffice)
    {
        $query = $this->database->select('box_cuts_expenses', '*', ['id_branch_office' => $branchOffice]);
        return $query;
    }

	public function newExpense($dateTime, $total, $description, $branchOffice)
	{
        $query = $this->database->insert('box_cuts_expenses', [
            'date_time' => $dateTime,
            'total' => $total,
            'description' => $description,
            'id_branch_office' => $branchOffice
        ]);

        return $query;
	}

    public function editExpense($idExpense, $dateTime, $total, $description, $branchOffice)
	{
        $query = $this->database->update('box_cuts_expenses', [
			'date_time' => $dateTime,
            'total' => $total,
            'description' => $description,
            'id_branch_office' => $branchOffice
        ], ['id_box_cut_expense' => $idExpense]);

        return $query;
	}

    /*
    --------------------------------------------------------------------------- */
    public function getAllSellers()
	{
		$query = $this->database->select('users', '*', [
            'AND' => [
                'level' => ['10', '9', '7'],
                'status' => true
            ]
        ]);

		return $query;
	}

    public function getUserById($id)
	{
		$query = $this->database->select('users', '*', ['id_user' => $id]);
		return !empty($query) ? $query[0] : '';
	}

    public function getAllBranchOffices()
	{
		$query = $this->database->select('branch_offices', '*', ['status' => true]);
		return $query;
	}

    public function getBranchOfficeById($id)
	{
		$query = $this->database->select('branch_offices', '*', ['id_branch_office' => $id]);
		return !empty($query) ? $query[0] : '';
	}
}
