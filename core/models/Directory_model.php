<?php

defined('_EXEC') or die;

class Directory_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Contactos
	--------------------------------------------------------------------------- */
	public function getAllContacts()
	{
		$query = $this->database->select('contacts', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getContactById($id)
	{
		$query = $this->database->select('contacts', '*', ['id_contact' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newContact($name, $email, $phoneNumber, $client, $category)
	{
        $query = $this->database->insert('contacts', [
            'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'id_client' => $client,
            'id_contact_category' => $category,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

        return $query;
	}

    public function editContact($id, $name, $email, $phoneNumber, $client, $category)
	{
        $query = $this->database->update('contacts', [
            'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'id_client' => $client,
            'id_contact_category' => $category,
        ], ['id_contact' => $id]);

        return $query;
	}

	public function deleteContacts($selection)
    {
		$query = $this->database->delete('contacts', [
            'id_contact' => $selection
        ]);

        return $query;
    }

	/*
	--------------------------------------------------------------------------- */
	public function getAllCountries()
	{
		$query = $this->database->select('settings_countries', '*', ['ORDER' => 'name ASC']);
		return $query;
	}

    public function getAllClients()
	{
		$query = $this->database->select('clients', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

    public function getClientById($id)
	{
		$query = $this->database->select('clients', '*', ['id_client' => $id]);
		return !empty($query) ? $query[0] : '';
	}

    /* Categorías
	--------------------------------------------------------------------------- */
	public function getAllCategories()
	{
		$query = $this->database->select('contacts_categories', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getCategoryById($id)
	{
		$query = $this->database->select('contacts_categories', '*', ['id_contact_category' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newCategory($name)
	{
		$query = $this->database->insert('contacts_categories', [
			'name' => $name,
			'id_subscription' => Session::getValue('id_subscription')
		]);

		return $query;
	}

	public function editCategory($id, $name)
	{
		$query = $this->database->update('contacts_categories', [
			'name' => $name
		], ['id_contact_category' => $id]);

		return $query;
	}

	public function deleteCategories($selection)
    {
		$query = $this->database->delete("contacts_categories", [
            'id_contact_category' => $selection
        ]);

        return $query;
    }

	public function checkExistCategory($id, $name, $action)
	{
		$query = $this->database->select('contacts_categories', '*', [
			'AND' => [
				'name' => $name,
				'id_subscription' => Session::getValue('id_subscription')
			]
		]);

		if (!empty($query) AND $action == 'new')
			return true;
		else if (!empty($query) AND $action == 'edit' AND $id != $query[0]['id_contact_category'])
			return true;
		else
			return false;
	}
}
