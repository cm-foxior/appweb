<?php

defined('_EXEC') or die;

class Settings_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getAllSettings()
    {
        $query = $this->database->select('settings', '*', ['id_subscription' => Session::getValue('id_subscription')]);
        return $query[0];
    }

    public function editBusinessSettings($name, $website, $logotype)
    {
		if (isset($logotype))
		{
			$this->component->loadComponent('uploader');

			$_com_uploader = new Upload;
			$_com_uploader->SetFileName($logotype['name']);
			$_com_uploader->SetTempName($logotype['tmp_name']);
			$_com_uploader->SetFileType($logotype['type']);
			$_com_uploader->SetFileSize($logotype['size']);
			$_com_uploader->SetUploadDirectory(PATH_IMAGES . 'logotypes');
			$_com_uploader->SetValidExtensions(['jpg', 'jpeg', 'png']);
			$_com_uploader->SetMaximumFileSize('unlimited');

			$logotype = $_com_uploader->UploadFile();
		}

        if ($logotype['status'] == 'success' OR !isset($logotype))
		{
			if ($logotype['status'] == 'success')
			{
				$this->format->cutImage($logotype['route'], 1920, 1080, 60);
				$logotype = $logotype['file'];
			}

			$settings = json_encode([
				'name' => $name,
				'website' => $website,
				'logotype' => $logotype
			]);

			$query = $this->database->update('settings', [
	            'business' => $settings
	        ], ['id_subscription' => Session::getValue('id_subscription')]);

            return $query;
		}
		else
			return null;
    }

	public function editSalesSettings($settings)
    {
        $query = $this->database->update('settings', [
            'sales' => $settings
        ], ['id_subscription' => Session::getValue('id_subscription')]);

        return $query;
    }

	/*
	--------------------------------------------------------------------------- */
	public function getAllInputs()
    {
        $query = $this->database->select('inventories_inputs', '*', ['id_subscription' => Session::getValue('id_subscription')]);
        return $query;
    }

	public function getProductById($id)
	{
	    $query = $this->database->select('products', '*', ['id_product' => $id]);
	    return !empty($query) ? $query[0] : '';
	}

	public function getBranchOfficeById($id)
    {
        $query = $this->database->select('branch_offices', '*', ['id_branch_office' => $id]);
        return !empty($query) ? $query[0] : '';
    }

	public function getInventoryById($id)
    {
        $query = $this->database->select('inventories', '*', ['id_inventory' => $id]);
        return !empty($query) ? $query[0] : '';
    }
}
