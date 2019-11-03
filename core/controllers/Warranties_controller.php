<?php

defined('_EXEC') or die;

class Warranties_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de garantías, crear y editar garantía
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$action	= $_POST['action'];
				$id		= ($action == 'edit') ? $_POST['id'] : null;

				$quantity	= (isset($_POST['quantity']) AND !empty($_POST['quantity'])) ? $_POST['quantity'] : null;
				$timeFrame	= (isset($_POST['timeFrame']) AND !empty($_POST['timeFrame'])) ? $_POST['timeFrame'] : null;

				$errors = [];

				if (!isset($quantity))
					array_push($errors, ['quantity', 'No deje este campo vacío']);
	            else if (!is_numeric($quantity))
					array_push($errors, ['quantity', 'Ingrese únicamente números']);
				else if ($quantity < 0)
	                array_push($errors, ['quantity', 'No ingrese números negativos']);
	            else if (strlen($quantity) > 4)
					array_push($errors, ['quantity', 'Ingrese máximo 4 caracteres']);
				else if (Security::checkIsFloat($quantity) == true)
	                array_push($errors, ['quantity', 'No ingrese números decimales']);

				if (!isset($timeFrame))
					array_push($errors, ['timeFrame', 'Seleccione una opción']);
				else if ($timeFrame != '1' AND $timeFrame != '2' AND $timeFrame != '3')
					array_push($errors, ['timeFrame', 'Opción no válida']);

				if (empty($errors))
	            {
	                $exist = $this->model->checkExistWarranty($id, $quantity, $timeFrame, $action);

	                if ($exist == true)
	                {
						array_push($errors, ['quantity', 'Este registro ya existe']);
						array_push($errors, ['timeFrame', 'Este registro ya existe']);

						echo json_encode([
							'status' => 'error',
							'labels' => $errors
						]);
	                }
	                else
	                {
						if ($action == 'new')
							$query = $this->model->newWarranty($quantity, $timeFrame);
						else if ($action == 'edit')
							$query = $this->model->editWarranty($id, $quantity, $timeFrame);

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
	            }
	            else
	            {
	                echo json_encode([
						'status' => 'error',
						'labels' => $errors
					]);
	            }
			}
			else
			{
				define('_title', '{$lang.title}');

				$template = $this->view->render($this, 'index');
				$template = $this->format->replaceFile($template, 'header');

				$warranties = $this->model->getAllWarranties();

				$lstWarranties = '';

				foreach ($warranties as $warranty)
				{
					$checkWarrantyRelationships = $this->model->checkWarrantyRelationships($warranty['id_warranty']);

		            if ($warranty['time_frame'] == '1')
		                $warrantyTimeFrame = 'Días';
		            else if ($warranty['time_frame'] == '2')
		                $warrantyTimeFrame = 'Meses';
		            else if ($warranty['time_frame'] == '3')
		                $warrantyTimeFrame = 'Años';

					$lstWarranties .=
					'<tr>
						<td>' . (($checkWarrantyRelationships == true) ? '' : '<input type="checkbox" data-check value="' . $warranty['id_warranty'] . '" />') . '</td>
						<td>' . $warranty['quantity'] . ' ' . $warrantyTimeFrame . '</td>
						<td><a href="" data-action="getWarrantyToEdit" data-id="' . $warranty['id_warranty'] . '"><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>
					</tr>';
				}

				$replace = [
					'{$lstWarranties}' => $lstWarranties
				];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
			header('Location: /dashboard');
	}

	/* Obtener garantía para editar
	--------------------------------------------------------------------------- */
	public function getWarrantyToEdit($id)
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				$warranty = $this->model->getWarrantyById($id);

	            if (!empty($warranty))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $warranty
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}

	/* Eliminar selección de garantías
	--------------------------------------------------------------------------- */
	public function deleteWarranties()
	{
		if (Session::getValue('level') == 10)
		{
			if (Format::existAjaxRequest() == true)
			{
				if(isset($_POST['data']) && !empty($_POST['data']))
				{
					$selection = json_decode($_POST['data']);

					$deleteWarranties = $this->model->deleteWarranties($selection);

					if (!empty($deleteWarranties))
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
