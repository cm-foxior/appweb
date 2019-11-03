<?php

defined('_EXEC') or die;

class Prospects_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Prospectos
	--------------------------------------------------------------------------- */
	public function getAllProspects()
	{
		$query = $this->database->select('clients', '*', [
			'AND' => [
				'prospect' => true,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);
		return $query;
	}

	public function getProspectById($id)
	{
		$query = $this->database->select('clients', '*', ['id_client' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newProspect($name, $email, $phoneNumber, $address)
	{
        $today = date('Y-m-d');

        $query = $this->database->insert('clients', [
            'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'address' => $address,
			'prospect' => true,
            'registration_date' => $today,
			'id_subscription' => Session::getValue('id_subscription')
        ]);

        return $query;
	}

	public function importFromExcel($xlsx)
	{
		$this->component->loadComponent('uploader');

		$_com_uploader = new Upload;
		$_com_uploader->SetFileName($xlsx['name']);
		$_com_uploader->SetTempName($xlsx['tmp_name']);
		$_com_uploader->SetFileType('application/xlsx');
		$_com_uploader->SetFileSize($xlsx['size']);
		$_com_uploader->SetUploadDirectory(PATH_UPLOADS . 'xlsx');
		$_com_uploader->SetValidExtensions(['xlsx', 'vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
		$_com_uploader->SetMaximumFileSize('unlimited');

		$xlsx = $_com_uploader->UploadFile();

		if ($xlsx['status'] == 'success')
		{
	        $this->component->loadComponent('simplexlsx');
			$xlsx = new SimpleXLSX(PATH_UPLOADS . 'xlsx/' . $xlsx['file']);

			$inserts = [];
			$fullErrors1 = [];
			$fullErrors2 = [];
			$xlsxRow = 1;
			$today = date('Y-m-d');

			foreach ($xlsx->rows() as $prospect)
			{
				$errors = [];

				if (empty($prospect[0]))
					array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El nombre no puede estár vacío']);

				if (empty($prospect[1]))
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El email no puede estár vacío']);
	            else if (Security::checkMail($prospect[1]) == false)
					array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El formato del email es incorrecto']);

				if (empty($prospect[2]))
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país no puede estár vacía']);
	            else if (!is_numeric($prospect[2]))
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país tiene que ser números']);
	            else if ($prospect[2] < 0)
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país no puede ser menor a 0']);
	            else if (Security::checkIsFloat($prospect[2]) == true)
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país no puede contener números decimáles']);
				else if (Security::checkIfExistSpaces($prospect[2]) == true)
					array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. La clave del país no puede contener espacios']);

				if (empty($prospect[3]))
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico no puede estár vacío']);
	            else if (!is_numeric($prospect[3]))
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico tiene que ser números']);
	            else if ($prospect[3] < 0)
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico no puede ser menor a 0']);
	            else if ($prospect[4] == 'Móvil' AND strlen($prospect[3]) != 10)
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico tiene que ser de 10 dígitos']);
	            else if ($prospect[4] == 'Local' AND strlen($prospect[3]) != 7)
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico tiene que ser de 7 dígitos']);
	            else if (Security::checkIsFloat($prospect[3]) == true)
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico no puede contener números decimáles']);
				else if (Security::checkIfExistSpaces($prospect[3]) == true)
					array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El número telefónico no puede contener espacios']);

				if (empty($prospect[4]))
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El tipo de teléfono no puede quedar vacío']);
	            else if ($prospect[4] != 'Local' AND $prospect[4] != 'Móvil')
	                array_push($errors, ['xlsx', 'Linea ' . $xlsxRow . '. El tipo de teléfono solo puede ser Local o Móvil']);

				if (!empty($errors))
				{
					array_push($fullErrors1, $errors);
				}
				else
				{
					$phoneNumber = json_encode([
						'country_code' => $prospect[2],
						'number' => $prospect[3],
						'type' => $prospect[4]
					]);

					$exist = $this->checkExistProspect(null, $prospect[1], $phoneNumber, 'new');

					if ($exist['status'] == true)
						array_push($fullErrors1, [['xlsx', 'Linea ' . $xlsxRow . '. Este registro ya ha sido ingresado previamente']]);
					else
						array_push($inserts, ['name' => $prospect[0], 'email' => $prospect[1], 'phone_number' => $phoneNumber, 'address' => $prospect[5]]);
				}

				$xlsxRow = $xlsxRow + 1;
			}

			if (!empty($fullErrors1))
			{
				foreach ($fullErrors1 as $fullErrors)
				{
					foreach ($fullErrors as $error)
					{
						array_push($fullErrors2, $error);
					}
				}

				return ['status' => 'error', 'errors' => $fullErrors2];
			}
			else
			{
				foreach ($inserts as $insert)
				{
					$query = $this->database->insert('clients', [
					    'name' => $insert['name'],
					    'email' => $insert['email'],
					    'phone_number' => $insert['phone_number'],
					    'address' => $insert['address'],
						'prospect' => true,
					    'registration_date' => $today
					]);
				}

				return ['status' => 'success'];
			}
		}
		else
			return ['status' => 'error', 'errors' => [['xlsx', 'No se pudo subir el XLSX adecuadamente']]];
	}

    public function editProspect($id, $name, $email, $phoneNumber, $address)
	{
        $query = $this->database->update('clients', [
			'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'address' => $address,
			'prospect' => true
        ], ['id_client' => $id]);

        return $query;
	}

	public function addToClients($selection)
    {
		$query = $this->database->update('clients', [
			'prospect' => false
        ], ['id_client' => $selection]);

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
				$prospects = $this->database->select('clients', '*', [
					'AND' => [
						'prospect' => true,
						'status' => true
					]
				]);

				foreach ($prospects as $prospect)
				{
					$this->sendEmail($prospect['email'], $prospect['name'], $subject, $message);
				}
			}
			else if ($sendTo == 'selected')
			{
				$selected = json_decode($selected, true);

				foreach ($selected as $item)
				{
					$prospect = $this->database->select('clients', '*', ['id_client' => $item]);
					$this->sendEmail($prospect[0]['email'], $prospect[0]['name'], $subject, $message);
				}
			}
		}
	}

	public function deleteProspects($selection)
    {
		$query = $this->database->delete('clients', [
            'id_client' => $selection
        ]);

        return $query;
    }

	public function checkExistProspect($id, $email, $phoneNumber, $action)
	{
		$query = $this->database->select('clients', '*', [
			'AND' => [
				'OR' => [
					'email' => $email,
					'phone_number' => $phoneNumber
				],
				'id_subscription' => Session::getValue('id_subscription')
			]
		]);

		if (!empty($query))
		{
			$errorEmail		 	= false;
			$errorPhoneNumber	= false;

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
			}

			if ($errorEmail == true OR $errorPhoneNumber == true)
				return ['status' => true, 'errors' => ['errorEmail' => $errorEmail, 'errorPhoneNumber' => $errorPhoneNumber]];
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
