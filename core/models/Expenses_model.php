<?php

defined('_EXEC') or die;

class Expenses_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get($id = null)
	{
        if (!empty($id))
        {
            $query = $this->database->select('expenses', '*', [
                'id_expense' => $id
            ]);

            $query = !empty($query) ? $query[0] : null;
        }
        else
        {
            $query = $this->database->select('expenses', '*', [
                'id_subscription' => Session::getValue('id_subscription'),
                'ORDER' => 'name ASC'
            ]);
        }

        return $query;
	}

	public function new($data)
	{
        $query = $this->database->insert('expenses', [
            'name' => $data['name'],
            'datetime' => $data['date'] . ' ' . $data['hour'],
            'bill' => !empty($data['bill']) ? $data['bill'] : null,
            'cost' => !empty($data['cost']) ? $data['cost'] : null,
            'payment' => !empty($data['payment']) ? $data['payment'] : null,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

        return $query;
	}

	public function edit($data)
	{
        $query = $this->database->update('expenses', [
            'name' => $data['name'],
            'datetime' => $data['date'] . ' ' . $data['hour'],
            'bill' => !empty($data['bill']) ? $data['bill'] : null,
            'cost' => !empty($data['cost']) ? $data['cost'] : null,
            'payment' => !empty($data['payment']) ? $data['payment'] : null
        ], [
            'id_expense' => $data['id']
        ]);

        return $query;
	}

	public function delete($selection)
    {
		$query = $this->database->delete('expenses', [
            'id_expense' => $selection
        ]);

        return $query;
    }
}
