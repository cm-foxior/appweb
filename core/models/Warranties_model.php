<?php

defined('_EXEC') or die;

class Warranties_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllWarranties()
	{
		$query = $this->database->select('warranties', '*', ['id_subscription' => Session::getValue('id_subscription')]);
		return $query;
	}

	public function getWarrantyById($id)
	{
		$query = $this->database->select('warranties', '*', ['id_warranty' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newWarranty($quantity, $timeFrame)
	{
		$query = $this->database->insert('warranties', [
			'quantity' => $quantity,
			'time_frame' => $timeFrame,
			'id_subscription' => Session::getValue('id_subscription')
		]);

		return $query;
	}

	public function editWarranty($id, $quantity, $timeFrame)
	{
		$query = $this->database->update('warranties', [
            'quantity' => $quantity,
			'time_frame' => $timeFrame
		], ['id_warranty' => $id]);

		return $query;
	}

	public function deleteWarranties($selection)
    {
		$query = $this->database->delete("warranties", [
            'id_warranty' => $selection
        ]);

        return $query;
    }

	public function checkExistWarranty($id, $quantity, $timeFrame, $action)
	{
		$query = $this->database->select('warranties', '*', [
            'AND' => [
                'quantity' => $quantity,
                'time_frame' => $timeFrame,
				'id_subscription' => Session::getValue('id_subscription')
            ]
        ]);

		if (!empty($query) AND $action == 'new')
			return true;
		else if (!empty($query) AND $action == 'edit' AND $id != $query[0]['id_warranty'])
			return true;
		else
			return false;
	}

	public function checkWarrantyRelationships($id)
	{
		$query1 = $this->database->select('products', '*', ['id_warranty' => $id]);
		$query2 = $this->database->select('services', '*', ['id_warranty' => $id]);

        return (!empty($query1) OR !empty($query2)) ? true : false;
	}
}
