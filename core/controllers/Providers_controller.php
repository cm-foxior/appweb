<?php

defined('_EXEC') or die;

class Providers_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'switch_states')
			{
				$html = '<option value="">{$lang.select} ({$lang.empty})</option>';

				foreach (Functions::states($_POST['country']) as $value)
					$html .= '<option value="' . $value['id'] . '">' . $value['name'][Session::get_value('vkye_lang')] . '</option>';

				echo json_encode([
					'status' => 'success',
					'html' => $html
				]);
			}

			if ($_POST['action'] == 'create_provider' OR $_POST['action'] == 'update_provider')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (Validations::email($_POST['email'], true) == false)
					array_push($errors, ['email','{$lang.invalid_field}']);

				if (Validations::empty([$_POST['phone_country'],$_POST['phone_number']], true) == false)
					array_push($errors, ['phone_number','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::number('int', $_POST['phone_number'], true) == false)
					array_push($errors, ['phone_number','{$lang.invalid_field}']);

				if (Validations::string(['uppercase','int'], $_POST['fiscal_id'], true) == false)
					array_push($errors, ['fiscal_id','{$lang.invalid_field}']);

				if (empty($errors))
				{
					$_POST['avatar'] = $_FILES['avatar'];

					if ($_POST['action'] == 'create_provider')
						$query = $this->model->create_provider($_POST);
					else if ($_POST['action'] == 'update_provider')
						$query = $this->model->update_provider($_POST);

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success',
							'message' => '{$lang.operation_success}'
						]);
					}
					else
					{
						echo json_encode([
							'status' => 'error',
							'message' => '{$lang.operation_error}'
						]);
					}
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}

			if ($_POST['action'] == 'read_provider')
			{
				$query = $this->model->read_provider($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'data' => $query
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}

			if ($_POST['action'] == 'block_provider' OR $_POST['action'] == 'unblock_provider' OR $_POST['action'] == 'delete_provider')
			{
				if ($_POST['action'] == 'block_provider')
					$query = $this->model->block_provider($_POST['id']);
				else if ($_POST['action'] == 'unblock_provider')
					$query = $this->model->unblock_provider($_POST['id']);
				else if ($_POST['action'] == 'delete_provider')
					$query = $this->model->delete_provider($_POST['id']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'message' => '{$lang.operation_success}'
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'message' => '{$lang.operation_error}'
					]);
				}
			}
		}
		else
		{
			define('_title', Configuration::$web_page . ' | {$lang.providers}');

			global $data;

			$data['providers'] = $this->model->read_providers();

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}
}
