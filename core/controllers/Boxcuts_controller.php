<?php

defined('_EXEC') or die;

class Boxcuts_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/* Lista de cortes de caja
	--------------------------------------------------------------------------- */
	public function index()
	{
		if (Session::getValue('level') >= 9)
        {
			define('_title', '{$lang.title}');

            $template = $this->view->render($this, 'index');
            $template = $this->format->replaceFile($template, 'header');

            if (Session::getValue('level') == 10)
                $boxCuts = $this->model->getAllBoxCuts();
            else if (Session::getValue('level') == 9)
                $boxCuts = $this->model->getAllBoxCutsByBranchOffice(Session::getValue('id_branch_office'));

            $tblBoxCuts =
            '<table id="tblBoxCuts" class="display" data-page-length="100">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Inicio</th>
                        <th>Término</th>
                        <th>Vendedor</th>
                        ' . ((Session::getValue('level') == 10) ? '<th>Sucursal</th>' : '') . '
                        <th width="35px"></th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($boxCuts as $boxCut)
            {
            	$seller = $this->model->getUserById($boxCut['id_user']);

                if (Session::getValue('level') == 10)
                    $branchOffice = $this->model->getBranchOfficeById($boxCut['id_branch_office']);

                $tblBoxCuts .=
                '<tr>
                    <td>' . $boxCut['date_time'] . '</td>
                    <td>' . $boxCut['date_time_start'] . '</td>
                    <td>' . $boxCut['date_time_end'] . '</td>
					<td>' . $seller['name'] . '</td>
                    ' . ((Session::getValue('level') == 10) ? '<td>' . $branchOffice['name'] . '</td>' : '') . '
                    <td><a href=""><i class="material-icons">more_horiz</i><span>Más detalles</span></a></td>
                </tr>';
            }

            $tblBoxCuts .=
            '	</tbody>
            </table>';

            $replace = [
                '{$tblBoxCuts}' => $tblBoxCuts
            ];

            $template = $this->format->replace($replace, $template);

            echo $template;
        }
        else
            header('Location: /dashboard');
	}

    /* Agragar un nuevo corte de caja
	--------------------------------------------------------------------------- */
	public function add()
	{
		if (Session::getValue('level') >= 9)
        {
            if (Format::existAjaxRequest() == true)
			{

			}
            else
            {
                define('_title', '{$lang.title}');

                $template = $this->view->render($this, 'add');
                $template = $this->format->replaceFile($template, 'header');

				$sellers = $this->model->getAllSellers();

                $lstSellers = '';
                $lstBranchOffices = '';

                foreach ($sellers as $seller)
				{
					$lstSellers .=
					'<option value="' . $seller['id_user'] . '">[' . $seller['username'] . '] ' . $seller['name'] . '</option>';
				}

                if (Session::getValue('level') == 10)
                {
                    $branchOffices = $this->model->getAllBranchOffices();

                    $lstBranchOffices .=
                    '<fieldset class="input-group span3">
                        <label data-important>
                            <span>Sucursal</span>
                            <select name="branchOffice" class="chosen-select">
                                <option value="">Seleccione una opción</option>';

                    foreach ($branchOffices as $branchOffice)
                        $lstBranchOffices .= '<option value="' . $branchOffice['id_branch_office'] . '">' . $branchOffice['name'] . '</option>';

                    $lstBranchOffices .=
                    '        </select>
                        </label>
                    </fieldset>';
                }

                $replace = [
                    '{$lstSellers}' => $lstSellers,
                    '{$lstBranchOffices}' => $lstBranchOffices
                ];

                $template = $this->format->replace($replace, $template);

                echo $template;
            }
        }
        else
            header('Location: /dashboard');
	}

	/* Buscar totales para generar corte de caja
	--------------------------------------------------------------------------- */
    public function searchTotals()
    {
        if (Session::getValue('level') >= 9)
        {
            if (Format::existAjaxRequest() == true)
			{
                $today = date('Y-m-d');
                $todayStart = $today . 'T00:00';
                $todayEnd = $today . 'T23:59';

                $startDateTime  = (isset($_POST['startDateTime']) AND !empty($_POST['startDateTime'])) ? $_POST['startDateTime'] : null;
                $endDateTime    = (isset($_POST['endDateTime']) AND !empty($_POST['endDateTime'])) ? $_POST['endDateTime'] : null;
				$seller = (isset($_POST['seller']) AND !empty($_POST['seller'])) ? $_POST['seller'] : null;

                if (Session::getValue('level') == 10)
                    $branchOffice = (isset($_POST['branchOffice']) AND !empty($_POST['branchOffice'])) ? $_POST['branchOffice'] : null;
                else
                    $branchOffice = Session::getValue('id_branch_office');

                $startTotal     = (isset($_POST['startTotal']) AND !empty($_POST['startTotal'])) ? $_POST['startTotal'] : null;
                $mxnTotal       = (isset($_POST['mxnTotal']) AND !empty($_POST['mxnTotal'])) ? $_POST['mxnTotal'] : null;
                $usdTotal       = (isset($_POST['usdTotal']) AND !empty($_POST['usdTotal'])) ? $_POST['usdTotal'] : null;

                $errors = [];

                if (!isset($startDateTime))
                    array_push($errors, ['startDateTime', 'No deje este campo vacío']);
                else if ($startDateTime < $todayStart OR $startDateTime > $todayEnd)
                    array_push($errors, ['startDateTime', 'Solo se permite el día actual']);
                else if ($startDateTime >= $endDateTime)
                    array_push($errors, ['startDateTime', 'La fecha de inicio debe de ser menor a la fecha de término']);

                if (!isset($endDateTime))
                    array_push($errors, ['endDateTime', 'No deje este campo vacío']);
                else if ($endDateTime < $todayStart OR $endDateTime > $todayEnd)
                    array_push($errors, ['endDateTime', 'Solo se permite el día actual']);
                else if ($endDateTime <= $startDateTime)
                    array_push($errors, ['endDateTime', 'La fecha de término debe de ser mayor a la fecha de inicio']);

                if (!isset($seller))
                    array_push($errors, ['seller', 'No deje este campo vacío']);

                if (Session::getValue('level') == 10 AND !isset($branchOffice))
                    array_push($errors, ['branchOffice', 'No deje este campo vacío']);

                if (!isset($startTotal))
                    array_push($errors, ['startTotal', 'No deje este campo vacío']);
                else if ($startTotal < 0)
                    array_push($errors, ['startTotal', 'No ingrese números negativos']);
                else if (!is_numeric($startTotal))
                    array_push($errors, ['startTotal', 'Ingrese únicamente números']);

                if (!isset($mxnTotal))
                    array_push($errors, ['mxnTotal', 'No deje este campo vacío']);
                else if ($mxnTotal < 0)
                    array_push($errors, ['mxnTotal', 'No ingrese números negativos']);
                else if (!is_numeric($mxnTotal))
                    array_push($errors, ['mxnTotal', 'Ingrese únicamente números']);

                if (!isset($usdTotal))
                    array_push($errors, ['usdTotal', 'No deje este campo vacío']);
                else if ($usdTotal < 0)
                    array_push($errors, ['usdTotal', 'No ingrese números negativos']);
                else if (!is_numeric($usdTotal))
                    array_push($errors, ['usdTotal', 'Ingrese únicamente números']);

                if (empty($errors))
                {

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
                Errors::http('404');
        }
        else
            header('Location: /dashboard');
    }

	/* Lista de gastos adicionales
	--------------------------------------------------------------------------- */
	public function expenses()
	{
		if (Session::getValue('level') >= 9)
        {
            if (Format::existAjaxRequest() == true)
			{
				$action		= $_POST['action'];
				$idExpense	= ($action == 'edit') ? $_POST['idExpense'] : null;

				$today = date('Y-m-d');
                $todayStart = $today . 'T00:00';
                $todayEnd = $today . 'T23:59';

				$dateTime 		= (isset($_POST['dateTime']) AND !empty($_POST['dateTime'])) ? $_POST['dateTime'] : null;
				$total 			= (isset($_POST['total']) AND !empty($_POST['total'])) ? $_POST['total'] : null;
				$description	= (isset($_POST['description']) AND !empty($_POST['description'])) ? $_POST['description'] : null;

				if (Session::getValue('level') == 10)
					$branchOffice = (isset($_POST['branchOffice']) AND !empty($_POST['branchOffice'])) ? $_POST['branchOffice'] : null;
				else if (Session::getValue('level') == 9)
					$branchOffice = Session::getValue('id_branch_office');

				$errors = [];

				if (!isset($dateTime))
                    array_push($errors, ['dateTime', 'No deje este campo vacío']);
                else if ($dateTime < $todayStart OR $dateTime > $todayEnd)
                    array_push($errors, ['dateTime', 'Solo se permite el día actual']);

				if (!isset($total))
                    array_push($errors, ['total', 'No deje este campo vacío']);
                else if ($total == 0)
                    array_push($errors, ['total', 'No ingrese cantidades en cero']);
                else if ($total < 0)
                    array_push($errors, ['total', 'No ingrese números negativos']);
                else if (!is_numeric($total))
                    array_push($errors, ['total', 'Ingrese únicamente números']);

				if (!isset($description))
                    array_push($errors, ['description', 'No deje este campo vacío']);

				if (Session::getValue('level') == 10 AND !isset($branchOffice))
                    array_push($errors, ['branchOffice', 'Seleccione una opción']);

				if (empty($errors))
				{
					if ($action == 'new')
						$query = $this->model->newExpense($dateTime, $total, $description, $branchOffice);
					else if ($action == 'edit')
						$query = $this->model->editExpense($idExpense, $dateTime, $total, $description, $branchOffice);

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
            else
            {
				define('_title', '{$lang.title}');

				$template = $this->view->render($this, 'expenses');
				$template = $this->format->replaceFile($template, 'header');

				if (Session::getValue('level') == 10)
	                $expenses = $this->model->getAllExpenses();
	            else if (Session::getValue('level') == 9)
	                $expenses = $this->model->getAllExpensesByBranchOffice(Session::getValue('id_branch_office'));

				$tblExpenses = '';
				$lstBranchOffices = '';

	            $tblExpenses .=
	            '<table id="tblExpenses" class="display" data-page-length="100">
	                <thead>
	                    <tr>
	                        <th width="150px">Fecha</th>
	                        <th width="150px">Total</th>
	                        <th>Descripción</th>
	                        ' . ((Session::getValue('level') == 10) ? '<th width="150px">Sucursal</th>' : '') . '
	                        <th width="50px">IDCC</th>
	                        <th width="35px"></th>
	                    </tr>
	                </thead>
	                <tbody>';

	            foreach ($expenses as $expense)
	            {
					if (Session::getValue('level') == 10)
	                    $branchOffice = $this->model->getBranchOfficeById($expense['id_branch_office']);

					if (isset($expense['id_box_cut']) AND !empty($expense['id_box_cut']))
						$idBoxCut = $expense['id_box_cut'];
					else
						$idBoxCut = '-';

	                $tblExpenses .=
	                '<tr>
	                    <td>' . $expense['date_time'] . '</td>
	                    <td>$ ' . $expense['total'] . ' MXN</td>
	                    <td>' . $expense['description'] . '</td>
	                    ' . ((Session::getValue('level') == 10) ? '<td>' . $branchOffice['name'] . '</td>' : '') . '
	                    <td>' . $idBoxCut . '</td>
	                    <td><a ' . ((isset($expense['id_box_cut']) AND !empty($expense['id_box_cut'])) ? 'disabled' : 'data-action="getExpenseToEdit" data-id="' . $expense['id_box_cut_expense'] . '"') . '><i class="material-icons">edit</i><span>Detalles / Editar</span></a></td>
	                </tr>';
	            }

	            $tblExpenses .=
	            '	</tbody>
	            </table>';

				if (Session::getValue('level') == 10)
				{
					$branchOffices = $this->model->getAllBranchOffices();

					$lstBranchOffices .=
					'<fieldset class="input-group">
	                    <label data-important>
	                        <span>Sucursal</span>
	                        <select name="branchOffice" class="chosen-select">
	                            <option value="">Seleccione una opción</option>';

					foreach ($branchOffices as $branchOffice)
						$lstBranchOffices .= '<option value="' . $branchOffice['id_branch_office'] . '">' . $branchOffice['name'] . '</option>';

	                $lstBranchOffices .=
					'        </select>
	                    </label>
	                </fieldset>';
				}

	            $replace = [
	                '{$tblExpenses}' => $tblExpenses,
	                '{$lstBranchOffices}' => $lstBranchOffices
	            ];

				$template = $this->format->replace($replace, $template);

				echo $template;
			}
		}
		else
            header('Location: /dashboard');
	}

	/* Obtener gasto para editar
	--------------------------------------------------------------------------- */
	public function getExpenseToEdit($id)
	{
		if (Session::getValue('level') >= 9)
		{
			if (Format::existAjaxRequest() == true)
			{
				$expense = $this->model->getExpenseById($id);

	            if (!empty($expense))
	            {
	                echo json_encode([
						'status' => 'success',
						'data' => $expense
					]);
	            }
			}
			else
				Errors::http('404');
		}
		else
			header('Location: /dashboard');
	}
}
