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

	public function sendMassEmail($subject, $message, $image, $sendTo, $selected)
	{
		if (isset($image))
		{
			$this->component->loadComponent('uploader');

			$_com_uploader = new Upload;
			$_com_uploader->SetFileName($image['name']);
			$_com_uploader->SetTempName($image['tmp_name']);
			$_com_uploader->SetFileType($image['type']);
			$_com_uploader->SetFileSize($image['size']);
			$_com_uploader->SetUploadDirectory(PATH_IMAGES . 'send');
			$_com_uploader->SetValidExtensions(['jpg', 'jpeg', 'png']);
			$_com_uploader->SetMaximumFileSize('unlimited');

			$image = $_com_uploader->UploadFile();
		}

        if ($image['status'] == 'success' OR !isset($image))
		{
			if (isset($message) AND !empty($message))
			{
				$message =
				'<div style="width:700px;height:auto;padding-top:20px;padding-right:20px;padding-bottom:0px;padding-left:20px;box-sizing:border-box;background-color:#f2f2f2;">
					<div style="width:100%;height:auto;padding:20px;box-sizing:border-box;background-color:#fff;">
						<p style="font-family:century gothic;font-size:12px;font-weight:300;color:#000;">' . $message . '</p>
					</div>
				</div>';
			}

			if ($image['status'] == 'success')
			{
				$this->format->cutImage($image['route'], 1920, 1080, 60);
				$image = $image['file'];

				$serPath = 'https://' . $_SERVER['HTTP_HOST'] . '/images/send/' . $image;
				$cdnPath = 'https://cdn.codemonkey.com.mx/images/sofi/image_4.jpg';

				$message .=
				'<figure style="width:700px;height:auto;padding:0px;margin:0px;line-height:0px;overflow:hidden">
					<img style="width:700px;height:auto;" src="' . $cdnPath . '" alt="" />
				</figure>';
			}

			$settings = $this->database->select('settings', '*');
	        $settings = json_decode($settings[0]['business'], true);

			$message .=
			'<div style="width:700px;height:auto;padding-top:0px;padding-right:20px;padding-bottom:20px;padding-left:20px;box-sizing:border-box;background-color:#f2f2f2;">
				<div style="width:100%;height:auto;padding:20px;box-sizing:border-box;background-color:#fff;">
					<p style="font-family:century gothic;font-size:12px;font-weight:300;text-align:center;color:#000;">' . $settings['website'] . '</p>
				</div>
			</div>';

			if ($sendTo == 'all')
			{
				$clients = $this->database->select('clients', '*', [
					'AND' => [
						'prospect' => false,
						'status' => true
					]
				]);

				foreach ($clients as $client)
				{
					$this->sendEmail($client['email'], $client['name'], $subject, $message);
				}
			}
			else if ($sendTo == 'selected')
			{
				$selected = json_decode($selected, true);

				foreach ($selected as $item)
				{
					$client = $this->database->select('clients', '*', ['id_client' => $item]);
					$this->sendEmail($client[0]['email'], $client[0]['name'], $subject, $message);
				}
			}
		}
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

	public function checkExistClient($id, $email, $phoneNumber, $fiscalName, $fiscalCode, $action)
	{
		if (isset($fiscalName) AND !isset($fiscalCode))
		{
			$query = $this->database->select('clients', '*', [
				'AND' => [
					'OR' => [
						'email' => $email,
						'phone_number' => $phoneNumber,
		                'fiscal_name' => $fiscalName
					],
					'id_subscription' => Session::getValue('id_subscription')
				]
			]);
		}
		else if (isset($fiscalCode) AND !isset($fiscalName))
		{
			$query = $this->database->select('clients', '*', [
				'OR' => [
					'email' => $email,
					'phone_number' => $phoneNumber,
	                'fiscal_code' => $fiscalCode
				]
			]);
		}
		else if (isset($fiscalCode) AND isset($fiscalName))
		{
			$query = $this->database->select('clients', '*', [
				'OR' => [
					'email' => $email,
					'phone_number' => $phoneNumber,
	                'fiscal_name' => $fiscalName,
	                'fiscal_code' => $fiscalCode
				]
			]);
		}
		else if (!isset($fiscalCode) AND !isset($fiscalName))
		{
			$query = $this->database->select('clients', '*', [
				'OR' => [
					'email' => $email,
					'phone_number' => $phoneNumber
				]
			]);
		}

		if (!empty($query))
		{
			$errorEmail		 	= false;
			$errorPhoneNumber	= false;
			$errorFiscalName 	= false;
			$errorFiscalCode 	= false;

			foreach ($query as $data)
			{
				if ($action == 'new' AND $email == $data['email'])
					$errorEmail = true;
				else if ($action == 'edit' AND $email == $data['email'] AND $id != $data['id_client'])
					$errorEmail = true;

				if ($action == 'new' AND $phoneNumber == $data['phone_number'])
					$errorPhoneNumber = true;
				else if ($action == 'edit' AND $phoneNumber == $data['phone_number'] AND $id != $data['id_client'])
					$errorPhoneNumber = true;

				if ($action == 'new' AND isset($fiscalName) AND $fiscalName == $data['fiscal_name'])
					$errorFiscalName = true;
				else if ($action == 'edit' AND isset($fiscalName) AND $fiscalName == $data['fiscal_name'] AND $id != $data['id_client'])
					$errorFiscalName = true;

				if ($action == 'new' AND isset($fiscalCode) AND $fiscalCode == $data['fiscal_code'])
					$errorFiscalCode = true;
				else if ($action == 'edit' AND isset($fiscalCode) AND $fiscalCode == $data['fiscal_code'] AND $id != $data['id_client'])
					$errorFiscalCode = true;
			}

			if ($errorEmail == true OR $errorPhoneNumber == true OR $errorFiscalName == true OR $errorFiscalCode == true)
				return ['status' => true, 'errors' => ['errorEmail' => $errorEmail, 'errorPhoneNumber' => $errorPhoneNumber, 'errorFiscalName' => $errorFiscalName, 'errorFiscalCode' => $errorFiscalCode]];
			else
				return ['status' => false];
		}
		else
			return ['status' => false];
	}

	/*
	--------------------------------------------------------------------------- */
	public function getAllCountries()
	{
		$query = $this->database->select('settings_countries', '*', ['ORDER' => 'name ASC']);
		return $query;
	}

	public function sendEmail($email, $name, $subject, $message)
	{
		$this->component->loadComponent('phpmailer');

		send_email(
			[
				$email => $name
			],
			FALSE,
			FALSE,
			FALSE,
			FALSE,
			$subject,
			$message,
			''
		);
	}
}
