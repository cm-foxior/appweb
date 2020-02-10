<?php

defined('_EXEC') or die;

class Users_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Usuarios
	--------------------------------------------------------------------------- */
	public function getAllUsers()
	{
		$query = $this->database->select('users', '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getAllUsersByBranchOffice($id)
	{
		$query = $this->database->select('users', '*', [
			'AND' => [
				'level' => ['8','7'],
				'id_branch_office' => $id,
				'id_subscription' => Session::getValue('id_subscription')
			],
			'ORDER' => 'name ASC'
		]);

		return $query;
	}

	public function getUserById($id)
	{
		$query = $this->database->select('users', '*', ['id_user' => $id]);
		return !empty($query) ? $query[0] : '';
	}

	public function newUser($name, $email, $phoneNumber, $username, $password, $level, $avatar, $branchOffice)
	{
        if (isset($avatar))
		{
			$this->component->loadComponent('uploader');

			$_com_uploader = new Upload;
			$_com_uploader->SetFileName($avatar['name']);
			$_com_uploader->SetTempName($avatar['tmp_name']);
			$_com_uploader->SetFileType($avatar['type']);
			$_com_uploader->SetFileSize($avatar['size']);
			$_com_uploader->SetUploadDirectory(PATH_IMAGES . 'users');
			$_com_uploader->SetValidExtensions(['jpg', 'jpeg', 'png']);
			$_com_uploader->SetMaximumFileSize('unlimited');

			$avatar = $_com_uploader->UploadFile();
		}

        if ($avatar['status'] == 'success' OR !isset($avatar))
		{
			if ($avatar['status'] == 'success')
			{
				$this->format->cutImage($avatar['route'], 1920, 1080, 60);
				$avatar = $avatar['file'];
			}

            $today = date('Y-m-d');

            $query = $this->database->insert('users', [
                'name' => $name,
                'email' => $email,
                'phone_number' => $phoneNumber,
                'username' => $username,
                'password' => $password,
                'level' => $level,
                'registration_date' => $today,
                'avatar' => $avatar,
                'id_branch_office' => $branchOffice,
				'id_subscription' => Session::getValue('id_subscription')
            ]);

            return $query;
		}
		else
			return null;
	}

	public function editUser($id, $name, $email, $phoneNumber, $username, $level, $avatar, $branchOffice)
	{
        if (isset($avatar))
		{
			$this->component->loadComponent('uploader');

			$_com_uploader = new Upload;
			$_com_uploader->SetFileName($avatar['name']);
			$_com_uploader->SetTempName($avatar['tmp_name']);
			$_com_uploader->SetFileType($avatar['type']);
			$_com_uploader->SetFileSize($avatar['size']);
			$_com_uploader->SetUploadDirectory(PATH_IMAGES . 'users');
			$_com_uploader->SetValidExtensions(['jpg', 'jpeg', 'png']);
			$_com_uploader->SetMaximumFileSize('unlimited');

			$avatar = $_com_uploader->UploadFile();
		}

        if (!isset($avatar) OR $avatar['status'] == 'success')
		{
			if (!isset($avatar))
			{
				$user	= $this->database->select('users', '*', ['id_user' => $id]);
				$avatar	= !empty($user[0]['avatar']) ? $user[0]['avatar'] : null;
			}
			else if ($avatar['status'] == 'success')
			{
				$this->format->cutImage($avatar['route'], 1920, 1080, 60);
				$avatar = $avatar['file'];
			}

            $query = $this->database->update('users', [
                'name' => $name,
                'email' => $email,
                'phone_number' => $phoneNumber,
                'username' => $username,
                'level' => $level,
                'avatar' => $avatar,
                'id_branch_office' => $branchOffice
            ], ['id_user' => $id]);

            return $query;
		}
		else
			return null;
	}

	public function restoreUserPassword($id, $password)
	{
        $query = $this->database->update('users', [
            'password' => $password
        ], ['id_user' => $id]);

        return $query;
	}

	public function changeStatusUsers($selection, $status)
    {
		$query = $this->database->update('users', [
            'status' => $status
        ], ['id_user' => $selection]);

        return $query;
    }

	public function deleteUsers($selection)
    {
		$query = $this->database->delete('users', [
            'id_user' => $selection
        ]);

        return $query;
    }

	public function checkExistUser($id, $username, $action)
	{
        $query = $this->database->select('users', '*', ['username' => $username]);

		if (!empty($query))
		{
			$errorUsername = false;

			foreach ($query as $data)
			{
				if ($action == 'new' AND $username == $data['username'])
					$errorUsername = true;
				else if ($action == 'edit' AND $username == $data['username'] AND $id != $data['id_user'])
					$errorUsername = true;
			}

			if ($errorUsername == true)
				return ['status' => true, 'errors' => ['errorUsername' => $errorUsername]];
			else
				return ['status' => false];
		}
		else
			return ['status' => false];
	}

	/*
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

	public function getUserLogged()
    {
        $query = $this->database->select('users', '*', ['id_user' => $_SESSION['id_user']]);
        return !empty($query) ? $query[0] : '';
    }
}
