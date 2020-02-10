<?php

defined('_EXEC') or die;

class Expenses_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
                if ($_POST['action'] == 'get')
                {
                    $query = $this->model->get($_POST['id']);

    	            if (!empty($query))
    	            {
                        $query['datetime'] = explode(' ', $query['datetime']);

    	                echo json_encode([
    						'status' => 'success',
    						'data' => $query
    					]);
    	            }
                }

                if ($_POST['action'] == 'new' OR $_POST['action'] == 'edit')
                {
                    $errors = [];

    	            if (!isset($_POST['name']) OR empty($_POST['name']))
    	                array_push($errors, ['name', 'No deje este campo vacío']);

                    if (!isset($_POST['date']) OR empty($_POST['date']))
    	                array_push($errors, ['date', 'No deje este campo vacío']);

    	            if (!isset($_POST['hour']) OR empty($_POST['hour']))
    	                array_push($errors, ['hour', 'No deje este campo vacío']);

    				if (empty($errors))
    				{
                        if ($_POST['action'] == 'new')
                            $query = $this->model->new($_POST);
                        else if ($_POST['action'] == 'edit')
                            $query = $this->model->edit($_POST);

                        if (!empty($query))
                        {
                            echo json_encode([
                                'status' => 'success'
                            ]);
                        }
                        else
                        {
                            echo json_encode([
                                'status' => 'error',
                                'message' => 'Error en la operación a la base de datos'
                            ]);
                        }
    				}
    				else
    				{
    					echo json_encode([
    						'status' => 'error',
    						'labels' => $errors
    					]);
    				}
                }
			}
			else
			{
				define('_title', '{$lang.title} | Dashboard');

				$template = $this->view->render($this, 'index');
				$template = $this->format->replaceFile($template, 'header');

				$tbl = '';

				foreach ($this->model->get() as $value)
				{
					$tbl .=
					'<tr>
						<td><input type="checkbox" data-check value="' . $value['id_expense'] . '" /></td>
                        <td>' . $value['datetime'] . '</td>
						<td>' . $value['name'] . '</td>
                        <td>
                            ' . ((!empty($value['bill'])) ? '# ' . $value['bill'] . '<br>' : '') . '
                            ' . ((!empty($value['cost'])) ? '$ ' . $value['cost'] . ' MXN<br>' : '') . '
                            ' . ((!empty($value['payment'])) ? $value['payment'] : '') . '
                        </td>
						<td>
							<a data-action="get" data-id="' . $value['id_expense'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a>
						</td>
					</tr>';
				}

				$replace = [
					'{$tbl}' => $tbl
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

    public function delete()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if (isset($_POST['data']) && !empty($_POST['data']))
				{
					$query = $this->model->delete(json_decode($_POST['data'], true));

					if (!empty($query))
					{
						echo json_encode([
							'status' => 'success'
						]);
					}
				}
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}
}
