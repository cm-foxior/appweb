<?php

defined('_EXEC') or die;

class Branches_controller extends Controller
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

			if ($_POST['action'] == 'create_branch' OR $_POST['action'] == 'update_branch')
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

					if ($_POST['action'] == 'create_branch')
						$query = $this->model->create_branch($_POST);
					else if ($_POST['action'] == 'update_branch')
						$query = $this->model->update_branch($_POST);

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

			if ($_POST['action'] == 'read_branch')
			{
				$query = $this->model->read_branch($_POST['id']);

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

			if ($_POST['action'] == 'block_branch' OR $_POST['action'] == 'unblock_branch' OR $_POST['action'] == 'delete_branch')
			{
				if ($_POST['action'] == 'block_branch')
					$query = $this->model->block_branch($_POST['id']);
				else if ($_POST['action'] == 'unblock_branch')
					$query = $this->model->unblock_branch($_POST['id']);
				else if ($_POST['action'] == 'delete_branch')
					$query = $this->model->delete_branch($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.branches}');

			global $data;

			$data['branches'] = $this->model->read_branches();

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}
}
