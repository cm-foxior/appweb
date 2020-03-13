<?php

defined('_EXEC') or die;

class Products_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($params)
	{
		if ($params[0] == 'menu')
			$params[1] = 'sale';
		else if ($params[0] == 'supplies')
			$params[1] = 'supply';
		else if ($params[0] == 'recipes')
			$params[1] = 'recipe';
		else if ($params[0] == 'workmaterials')
			$params[1] = 'work_material';

		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_product' OR $_POST['action'] == 'update_product')
			{
				$errors = [];

				if (Functions::check_empty_value($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (Functions::check_empty_value($_POST['token']) == false)
					array_push($errors, ['token','{$lang.dont_leave_this_field_empty}']);
				else if (Functions::check_empty_spaces($_POST['token']) == false OR Functions::check_special_characters($_POST['token']) == false)
					array_push($errors, ['token','{$lang.invalid_field}']);

				if ($params[1] == 'sale')
				{
					if (Functions::check_empty_value($_POST['price']) == false)
						array_push($errors, ['price','{$lang.dont_leave_this_field_empty}']);
					else if (Functions::check_float_number($_POST['price']) == false)
						array_push($errors, ['price','{$lang.invalid_field}']);
				}

				if ($params[1] == 'sale' OR $params[1] == 'supply' OR $params[1] == 'work_material')
				{
					if (Functions::check_empty_value($_POST['unity']) == false)
						array_push($errors, ['unity','{$lang.dont_leave_this_field_empty}']);
				}

				if ($params[1] == 'sale' OR $params[1] == 'supply')
				{
					if (Functions::check_float_number($_POST['weight_empty'], true) == false)
						array_push($errors, ['weight_empty','{$lang.invalid_field}']);

					if (Functions::check_float_number($_POST['weight_full'], true) == false)
						array_push($errors, ['weight_full','{$lang.invalid_field}']);
				}

				if (empty($errors))
				{
					if ($params[1] == 'sale')
						$_POST['avatar'] = $_FILES['avatar'];

					$_POST['type'] = $params[1];

					if ($_POST['action'] == 'create_product')
						$query = $this->model->create_product($_POST);
					else if ($_POST['action'] == 'update_product')
						$query = $this->model->update_product($_POST);

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

			if ($_POST['action'] == 'read_product')
			{
				$query = $this->model->read_product($_POST['id']);

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

			if ($_POST['action'] == 'block_product' OR $_POST['action'] == 'unblock_product' OR $_POST['action'] == 'delete_product')
			{
				if ($_POST['action'] == 'block_product')
					$query = $this->model->block_product($_POST['id']);
				else if ($_POST['action'] == 'unblock_product')
					$query = $this->model->unblock_product($_POST['id']);
				else if ($_POST['action'] == 'delete_product')
					$query = $this->model->delete_product($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '} | {$lang.' . $params[0] . '}');

			global $data;

			$data['type'] = $params[1];
			$data['products'] = $this->model->read_products($params[1]);
			$data['products_categories'] = $this->model->read_products_categories(true);
			$data['products_unities'] = $this->model->read_products_unities(true);
			$data['products_supplies'] = $this->model->read_products('supply', true);
			$data['products_recipes'] = $this->model->read_products('recipe', true);

			$template = $this->view->render($this, 'index');

			echo $template;
		}
	}

	public function categories()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_product_category' OR $_POST['action'] == 'update_product_category')
			{
				$errors = [];

				if (Functions::check_empty_value($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (Functions::check_empty_value($_POST['level']) == false)
					array_push($errors, ['level','{$lang.dont_leave_this_field_empty}']);
				else if (Functions::check_int_number($_POST['level']) == false)
					array_push($errors, ['level','{$lang.invalid_field}']);

				if (empty($errors))
				{
					$_POST['avatar'] = $_FILES['avatar'];

					if ($_POST['action'] == 'create_product_category')
						$query = $this->model->create_product_category($_POST);
					else if ($_POST['action'] == 'update_product_category')
						$query = $this->model->update_product_category($_POST);

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

			if ($_POST['action'] == 'read_product_category')
			{
				$query = $this->model->read_product_category($_POST['id']);

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

			if ($_POST['action'] == 'block_product_category' OR $_POST['action'] == 'unblock_product_category' OR $_POST['action'] == 'delete_product_category')
			{
				if ($_POST['action'] == 'block_product_category')
					$query = $this->model->block_product_category($_POST['id']);
				else if ($_POST['action'] == 'unblock_product_category')
					$query = $this->model->unblock_product_category($_POST['id']);
				else if ($_POST['action'] == 'delete_product_category')
					$query = $this->model->delete_product_category($_POST['id']);

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

			$data['products_categories'] = $this->model->read_products_categories();
			$data['products_categories_levels'] = $this->model->read_products_categories_levels();

			$template = $this->view->render($this, 'categories');

			echo $template;
		}
	}

	public function unities()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'create_product_unity' OR $_POST['action'] == 'update_product_unity')
			{
				$errors = [];

				if (Functions::check_empty_value($_POST['name']) == false)
					array_push($errors, ['name','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_product_unity')
						$query = $this->model->create_product_unity($_POST);
					else if ($_POST['action'] == 'update_product_unity')
						$query = $this->model->update_product_unity($_POST);

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

			if ($_POST['action'] == 'read_product_unity')
			{
				$query = $this->model->read_product_unity($_POST['id']);

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

			if ($_POST['action'] == 'block_product_unity' OR $_POST['action'] == 'unblock_product_unity' OR $_POST['action'] == 'delete_product_unity')
			{
				if ($_POST['action'] == 'block_product_unity')
					$query = $this->model->block_product_unity($_POST['id']);
				else if ($_POST['action'] == 'unblock_product_unity')
					$query = $this->model->unblock_product_unity($_POST['id']);
				else if ($_POST['action'] == 'delete_product_unity')
					$query = $this->model->delete_product_unity($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.' . $GLOBALS['_vkye_module'] . '} | {$lang.unities}');

			global $data;

			$data['products_unities'] = $this->model->read_products_unities();

			$template = $this->view->render($this, 'unities');

			echo $template;
		}
	}
}
