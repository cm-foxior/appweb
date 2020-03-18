<?php

defined('_EXEC') or die;

class Inventories_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($params)
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'switch_branch')
			{
				$query = $this->model->read_branch($_POST['token']);

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'path' => '/inventories/' . strtolower($query['token']) . '/' . System::cleaned_url($query['name'])
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
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '}');

			global $data;

			$data['branches'] = $this->model->read_branches();

			if (!empty($data['branches']))
			{
				if (!isset($params[0]) OR empty($params[0]))
					header('Location: /inventories/' . strtolower($data['branches'][0]['token']) . '/' . System::cleaned_url($data['branches'][0]['name']));
				else
				{
					$data['branch'] = $this->model->read_branch($params[0]);
					$data['inventories'] = $this->model->read_inventories($data['branch']['id']);
				}
			}

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}

	public function types()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_inventory_type' OR $_POST['action'] == 'update_inventory_type')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['movement']) == false)
					array_push($errors, ['movement','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_inventory_type')
						$query = $this->model->create_inventory_type($_POST);
					else if ($_POST['action'] == 'update_inventory_type')
						$query = $this->model->update_inventory_type($_POST);

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

			if ($_POST['action'] == 'read_inventory_type')
			{
				$query = $this->model->read_inventory_type($_POST['id']);

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

			if ($_POST['action'] == 'block_inventory_type' OR $_POST['action'] == 'unblock_inventory_type' OR $_POST['action'] == 'delete_inventory_type')
			{
				if ($_POST['action'] == 'block_inventory_type')
					$query = $this->model->block_inventory_type($_POST['id']);
				else if ($_POST['action'] == 'unblock_inventory_type')
					$query = $this->model->unblock_inventory_type($_POST['id']);
				else if ($_POST['action'] == 'delete_inventory_type')
					$query = $this->model->delete_inventory_type($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '} | {$lang.types}');

			global $data;

			$data['inventories_types'] = $this->model->read_inventories_types();

			$template = $this->view->render($this, 'types');

			echo $template;
		}
	}

	public function locations()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_inventory_location' OR $_POST['action'] == 'update_inventory_location')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_inventory_location')
						$query = $this->model->create_inventory_location($_POST);
					else if ($_POST['action'] == 'update_inventory_location')
						$query = $this->model->update_inventory_location($_POST);

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

			if ($_POST['action'] == 'read_inventory_location')
			{
				$query = $this->model->read_inventory_location($_POST['id']);

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

			if ($_POST['action'] == 'block_inventory_location' OR $_POST['action'] == 'unblock_inventory_location' OR $_POST['action'] == 'delete_inventory_location')
			{
				if ($_POST['action'] == 'block_inventory_location')
					$query = $this->model->block_inventory_location($_POST['id']);
				else if ($_POST['action'] == 'unblock_inventory_location')
					$query = $this->model->unblock_inventory_location($_POST['id']);
				else if ($_POST['action'] == 'delete_inventory_location')
					$query = $this->model->delete_inventory_location($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '} | {$lang.locations}');

			global $data;

			$data['inventories_locations'] = $this->model->read_inventories_locations();

			$template = $this->view->render($this, 'locations');

			echo $template;
		}
	}

	public function categories()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_inventory_category' OR $_POST['action'] == 'update_inventory_category')
			{
				$errors = [];

				if (Validations::empty($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['level']) == false)
					array_push($errors, ['level','{$lang.dont_leave_this_field_empty}']);
				else if (Validations::number('int', $_POST['level']) == false)
					array_push($errors, ['level','{$lang.invalid_field}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_inventory_category')
						$query = $this->model->create_inventory_category($_POST);
					else if ($_POST['action'] == 'update_inventory_category')
						$query = $this->model->update_inventory_category($_POST);

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

			if ($_POST['action'] == 'read_inventory_category')
			{
				$query = $this->model->read_inventory_category($_POST['id']);

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

			if ($_POST['action'] == 'block_inventory_category' OR $_POST['action'] == 'unblock_inventory_category' OR $_POST['action'] == 'delete_inventory_category')
			{
				if ($_POST['action'] == 'block_inventory_category')
					$query = $this->model->block_inventory_category($_POST['id']);
				else if ($_POST['action'] == 'unblock_inventory_category')
					$query = $this->model->unblock_inventory_category($_POST['id']);
				else if ($_POST['action'] == 'delete_inventory_category')
					$query = $this->model->delete_inventory_category($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '} | {$lang.categories}');

			global $data;

			$data['inventories_categories'] = $this->model->read_inventories_categories();
			$data['inventories_categories_levels'] = $this->model->read_inventories_categories_levels();

			$template = $this->view->render($this, 'categories');

			echo $template;
		}
	}
}
