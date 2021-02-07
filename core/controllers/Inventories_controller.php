<?php

defined('_EXEC') or die;

class Inventories_controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function movements()
	{
		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'switch_branch')
			{
				$query = $this->model->read_branch($_POST['id']);

				if (!empty($query))
				{
					System::temporal('set_forced', 'inventories', 'branch', $query);

					echo json_encode([
						'status' => 'success',
						'path' => '/inventories/movements'
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

			if ($_POST['action'] == 'switch_inventory_period')
			{
				System::temporal('set_forced', 'inventories', 'period', $_POST['id']);

				echo json_encode([
					'status' => 'success'
				]);
			}

			if ($_POST['action'] == 'add_product_to_inputs_table')
			{
				$errors = [];

				if (Validations::empty($_POST['product_id']) == false)
					array_push($errors, ['product_id','{$lang.dont_leave_this_field_empty}']);
				else
				{
					$_POST['product'] = $this->model->read_product($_POST['product_id'], 'id');

					if (!empty($_POST['product']))
					{
						if (Validations::empty($_POST['saved']) == false)
							array_push($errors, ['saved','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'quantity') == true AND Validations::empty($_POST['quantity']) == false)
							array_push($errors, ['quantity','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'weight') == true AND Validations::empty($_POST['weight']) == false)
							array_push($errors, ['weight','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'weight') == true AND Validations::empty($_POST['content']) == false)
							array_push($errors, ['weight','{$lang.select_an_content}']);
					}
					else
						array_push($errors, ['product_token','{$lang.this_record_dont_exists}']);
				}

				if (empty($errors))
				{
					$_POST['quantity'] = [(($_POST['saved'] == 'quantity') ? $_POST['quantity'] : $_POST['weight']), ((($_POST['saved'] == 'quantity' AND !empty($_POST['content']) OR $_POST['saved'] == 'weight')) ? '0' : (($_POST['saved'] == 'quantity') ? $_POST['quantity'] : $_POST['weight']))];
					$_POST['content'] = !empty($_POST['content']) ? explode('_', $_POST['content']) : [];

					if ($_POST['saved'] == 'quantity' AND !empty($_POST['content']))
					{
						$_POST['quantity'][1] = ($_POST['quantity'][0] * $_POST['product']['contents'][$_POST['content'][1]]['content']['amount']);
						$_POST['quantity'][1] = Functions::conversion('unity', $_POST['quantity'][1], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code'], $_POST['product']['unity_code']);
					}
					else if ($_POST['saved'] == 'weight')
					{
						if ($_POST['content'][0] == 'cnt')
						{
							$_POST['weight'] = ($_POST['weight'] - $_POST['product']['contents'][$_POST['content'][1]]['weight']['amount']);
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['weight'], $_POST['product']['contents'][$_POST['content'][1]]['weight']['unity_code'], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code']);
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['quantity'][1], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code'], $_POST['product']['unity_code']);
						}
						else if ($_POST['content'][0] == 'unt')
						{
							$unity_1 = $this->model->read_product_unity($_POST['content'][1], 'code');
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['weight'], $unity_1, $_POST['product']['unity_code']);
						}
					}

					$_POST['cost'] = !empty($_POST['cost']) ? $_POST['cost'] : 0;
					$_POST['total'] = 0;

					if (!empty($_POST['cost']))
					{
						if (($_POST['saved'] == 'quantity' AND !empty($_POST['content'])) OR ($_POST['saved'] == 'weight' AND $_POST['content'][0] == 'cnt'))
						{
							$_POST['cost'] = ($_POST['cost'] / $_POST['product']['contents'][$_POST['content'][1]]['content']['amount']);
							$_POST['cost'] = Functions::conversion('cost', $_POST['cost'], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code'], $_POST['product']['unity_code']);
						}
						else if ($_POST['saved'] == 'weight' AND $_POST['content'][0] == 'unt')
						{
							$unity_1 = $this->model->read_product_unity($_POST['content'][1], 'code');
							$_POST['cost'] = Functions::conversion('cost', $_POST['cost'], $unity_1, $_POST['product']['unity_code']);
						}

						$_POST['total'] = ($_POST['cost'] * $_POST['quantity'][1]);
					}

					$_POST['location'] = $this->model->read_inventory_location($_POST['location']);
					$_POST['categories'] = !empty($_POST['categories']) ? $this->model->read_inventories_categories($_POST['categories']) : [];
					$temporal = System::temporal('get', 'inventories', 'inputs');
					$content = (!empty($_POST['content']) ? $_POST['content'][1] : '0') . '-' . (!empty($_POST['cost']) ? $_POST['cost'] : '0') . '-' . (!empty($_POST['location']) ? $_POST['location']['id'] : '0') . '-' . (!empty($_POST['categories']) ? System::summation('string', $_POST['categories'], 'id', null, '-') : '0');

					if (array_key_exists($_POST['product']['id'], $temporal))
					{
						if (array_key_exists($content, $temporal[$_POST['product']['id']]['list']))
						{
							$temporal[$_POST['product']['id']]['list'][$content]['quantity'][0] += $_POST['quantity'][0];
							$temporal[$_POST['product']['id']]['list'][$content]['quantity'][1] += $_POST['quantity'][1];
							$temporal[$_POST['product']['id']]['list'][$content]['total'] += $_POST['total'];
						}
						else
						{
							$temporal[$_POST['product']['id']]['list'][$content] = [
								'quantity' => $_POST['quantity'],
								'cost' => $_POST['cost'],
								'total' => $_POST['total'],
								'location' => $_POST['location'],
								'categories' => $_POST['categories'],
								'content' => $_POST['content']
							];
						}
					}
					else
					{
						$temporal[$_POST['product']['id']] = [
							'product' => $_POST['product'],
							'list' => [
								$content => [
									'quantity' => $_POST['quantity'],
									'cost' => $_POST['cost'],
									'total' => $_POST['total'],
									'location' => $_POST['location'],
									'categories' => $_POST['categories'],
									'content' => $_POST['content']
								]
							]
						];
					}

					System::temporal('set_forced', 'inventories', 'inputs', $temporal);

					$table = '';

					foreach (array_reverse($temporal) as $value)
					{
						$table .=
						'<tr class="first">
							<td><span><strong>' . $value['product']['name'] . '</strong></span></td>
							<td></td>
						</tr>';

						foreach ($value['list'] as $subkey => $subvalue)
						{
							$table .=
							'<tr class="half">
								<td>
									<span>{$lang.quantity}: ' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? '(' . $subvalue['quantity'][0] . ') ' . $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' : '') . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? ')' : '') . '</span>
									<span>{$lang.unitary_cost}: ' . Currency::format($subvalue['cost'], Session::get_value('vkye_account')['currency']) . '</span>
									<span>{$lang.total_cost}: ' . Currency::format($subvalue['total'], Session::get_value('vkye_account')['currency']) . '</span>
									<span>' . (!empty($subvalue['location']) ? '{$lang.location}: ' . $subvalue['location']['name'] : '{$lang.not_location}') . '</span>
									<span>' . (!empty($subvalue['categories']) ? '{$lang.categories}: ' . System::summation('string', $subvalue['categories'], 'name') : '{$lang.not_categories}') . '</span>
								</td>
								<td class="button"><a data-action="remove_product_to_inputs_table" data-id="' . $value['product']['id'] . '_' . $subkey . '" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
							</tr>';
						}

						$table .=
						'<tr class="last">
							<td><span>' . Currency::format(System::summation('math', $value['list'], 'total'), Session::get_value('vkye_account')['currency']) . '</span></td>
							<td></td>
						</tr>';
					}

					$table .=
					'<tr class="last">
						<td><span><strong>' . Currency::format(System::summation('math', $temporal, 'total', 'list'), Session::get_value('vkye_account')['currency']) . '</strong></span></td>
						<td></td>
					</tr>';

					echo json_encode([
						'status' => 'success',
						'data' => [
							'table' => $table
						]
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}

			if ($_POST['action'] == 'add_product_to_outputs_table' OR $_POST['action'] == 'add_product_to_transfers_table')
			{
				$errors = [];

				if (Validations::empty($_POST['product_id']) == false)
					array_push($errors, ['product_id','{$lang.dont_leave_this_field_empty}']);
				else
				{
					$_POST['product'] = $this->model->read_product($_POST['product_id'], 'id');

					if (!empty($_POST['product']))
					{
						if (Validations::empty($_POST['saved']) == false)
							array_push($errors, ['saved','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'quantity') == true AND Validations::empty($_POST['quantity']) == false)
							array_push($errors, ['quantity','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'weight') == true AND Validations::empty($_POST['weight']) == false)
							array_push($errors, ['weight','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'weight') == true AND Validations::empty($_POST['content']) == false)
							array_push($errors, ['weight','{$lang.select_an_content}']);
					}
					else
						array_push($errors, ['product_token','{$lang.this_record_dont_exists}']);
				}

				if (empty($errors))
				{
					$go = true;

					$_POST['quantity'] = [(($_POST['saved'] == 'quantity') ? $_POST['quantity'] : $_POST['weight']), (($_POST['product']['inventory'] == true) ? ((($_POST['saved'] == 'quantity' AND !empty($_POST['content'])) OR $_POST['saved'] == 'weight') ? '0' : (($_POST['saved'] == 'quantity') ? $_POST['quantity'] : $_POST['weight'])) : ((!empty($_POST['product']['formula']) AND !empty($_POST['product']['formula']['parent'])) ? '0' : $_POST['quantity']))];

					if ($_POST['product']['inventory'] == true)
					{
						$_POST['content'] = !empty($_POST['content']) ? explode('_', $_POST['content']) : [];

						if ($_POST['saved'] == 'quantity' AND !empty($_POST['content']))
						{
							$_POST['quantity'][1] = ($_POST['quantity'][0] * $_POST['product']['contents'][$_POST['content'][1]]['content']['amount']);
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['quantity'][1], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code'], $_POST['product']['unity_code']);
						}
						else if ($_POST['saved'] == 'weight')
						{
							if ($_POST['content'][0] == 'cnt')
							{
								$_POST['weight'] = ($_POST['weight'] - $_POST['product']['contents'][$_POST['content'][1]]['weight']['amount']);
								$_POST['quantity'][1] = Functions::conversion('unity', $_POST['weight'], $_POST['product']['contents'][$_POST['content'][1]]['weight']['unity_code'], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code']);
								$_POST['quantity'][1] = Functions::conversion('unity', $_POST['quantity'][1], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code'], $_POST['product']['unity_code']);
							}
							else if ($_POST['content'][0] == 'unt')
							{
								$unity_1 = $this->model->read_product_unity($_POST['content'][1], 'code');
								$_POST['quantity'][1] = Functions::conversion('unity', $_POST['weight'], $unity_1, $_POST['product']['unity_code']);
							}
						}
					}
					else
					{
						if (!empty($_POST['product']['formula']) AND !empty($_POST['product']['formula']['parent']))
						{
							if ($_POST['saved'] == 'quantity')
							{
								if ($_POST['product']['formula']['code'] == 'SHG78K9H')
									$_POST['quantity'][1] = ($_POST['product']['formula']['quantity'] * $_POST['quantity'][0]);
								else
									$go = false;
							}
							else
								$go = false;
						}
					}

					if ($go == true)
					{
						if ($_POST['action'] == 'add_product_to_outputs_table')
							$temporal = System::temporal('get', 'inventories', 'outputs');
						else if ($_POST['action'] == 'add_product_to_transfers_table')
							$temporal = System::temporal('get', 'inventories', 'transfers');

						if (array_key_exists($_POST['product']['id'], $temporal))
						{
							if ($_POST['product']['inventory'] == true)
							{
								$content = !empty($_POST['content']) ? $_POST['content'][1] : 0;

								if (array_key_exists($content, $temporal[$_POST['product']['id']]['list']))
								{
									$temporal[$_POST['product']['id']]['list'][$content]['quantity'][0] += $_POST['quantity'][0];
									$temporal[$_POST['product']['id']]['list'][$content]['quantity'][1] += $_POST['quantity'][1];
								}
								else
								{
									$temporal[$_POST['product']['id']]['list'][$content] = [
										'quantity' => $_POST['quantity'],
										'content' => $_POST['content']
									];
								}
							}
							else
							{
								$temporal[$_POST['product']['id']]['list'][0]['quantity'][0] += $_POST['quantity'][0];
								$temporal[$_POST['product']['id']]['list'][0]['quantity'][1] += $_POST['quantity'][1];

								if (!empty($_POST['product']['formula']) AND !empty($_POST['product']['formula']['parent']))
								{
									if ($_POST['product']['formula']['parent']['type'] == 'sale_menu' AND !empty($_POST['product']['formula']['parent']['supplies']))
									{
										foreach ($_POST['product']['formula']['parent']['supplies'] as $key => $value)
										{
											$value['supply']['quantity'] = ($value['supply']['quantity'] * $_POST['quantity'][1]);
											$value['supply']['quantity'] = Functions::conversion('unity', $value['supply']['quantity'], $value['supply']['unity_code'], $value['product']['unity_code']);
											$temporal[$_POST['product']['id']]['list'][0]['supplies'][$key]['quantity'] += $value['supply']['quantity'];
										}
									}
								}
							}
						}
						else
						{
							$temporal[$_POST['product']['id']] = [
								'product' => $_POST['product'],
								'list' => [],
								'supplies' => []
							];

							if ($_POST['product']['inventory'] == true)
							{
								$content = !empty($_POST['content']) ? $_POST['content'][1] : 0;

								$temporal[$_POST['product']['id']]['list'][$content] = [
									'quantity' => $_POST['quantity'],
									'content' => $_POST['content']
								];
							}
							else
							{
								if (!empty($_POST['product']['formula']) AND !empty($_POST['product']['formula']['parent']))
								{
									$temporal[$_POST['product']['id']]['list'][0] = [
										'quantity' => $_POST['quantity'],
										'supplies' => []
									];

									if ($_POST['product']['formula']['parent']['type'] == 'sale_menu' AND !empty($_POST['product']['formula']['parent']['supplies']))
									{
										foreach ($_POST['product']['formula']['parent']['supplies'] as $key => $value)
										{
											$value['supply']['quantity'] = ($value['supply']['quantity'] * $_POST['quantity'][1]);
											$value['supply']['quantity'] = Functions::conversion('unity', $value['supply']['quantity'], $value['supply']['unity_code'], $value['product']['unity_code']);

											$temporal[$_POST['product']['id']]['list'][0]['supplies'][$key] = [
												'product' => $value['product'],
												'quantity' => $value['supply']['quantity']
											];
										}
									}
								}
								else
								{
									$temporal[$_POST['product']['id']]['list'][0] = [
										'quantity' => $_POST['quantity']
									];
								}
							}
						}

						if ($_POST['product']['type'] == 'sale_menu' AND !empty($_POST['product']['supplies']))
						{
							foreach ($_POST['product']['supplies'] as $key => $value)
							{
								$value['supply']['quantity'] = ($value['supply']['quantity'] * $_POST['quantity'][1]);
								$value['supply']['quantity'] = Functions::conversion('unity', $value['supply']['quantity'], $value['supply']['unity_code'], $value['product']['unity_code']);

								if (array_key_exists($key, $temporal[$_POST['product']['id']]['supplies']))
									$temporal[$_POST['product']['id']]['supplies'][$key]['quantity'] += $value['supply']['quantity'];
								else
								{
									$temporal[$_POST['product']['id']]['supplies'][$key] = [
										'product' => $value['product'],
										'quantity' => $value['supply']['quantity']
									];
								}
							}
						}

						if ($_POST['action'] == 'add_product_to_outputs_table')
							System::temporal('set_forced', 'inventories', 'outputs', $temporal);
						else if ($_POST['action'] == 'add_product_to_transfers_table')
							System::temporal('set_forced', 'inventories', 'transfers', $temporal);

						$table = '';

						foreach (array_reverse($temporal) as $key => $value)
						{
							if ($value['product']['inventory'] == true)
							{
								foreach ($value['list'] as $subkey => $subvalue)
								{
									$table .=
									'<tr class="first">
										<td><span><strong>' . '(' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $subvalue['quantity'][0] : number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name'])) . ') ' . $value['product']['name'] . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? ' ' . $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')' : '') . '</strong></span></td>
										<td class="button"><a data-action="' . (($_POST['action'] == 'add_product_to_outputs_table') ? 'remove_product_to_outputs_table' : 'remove_product_to_transfers_table') . '" data-id="' . $value['product']['id'] . '_' . $subkey . '" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
									</tr>';
								}
							}
							else
							{
								$table .=
								'<tr class="first">
									<td><span><strong>' . ((!empty($value['product']['formula']) AND !empty($value['product']['formula']['parent'])) ? '(' . $value['list'][0]['quantity'][0] . ') ' . $value['product']['name'] . ' (' . number_format($value['list'][0]['quantity'][1], 2, '.', '') . ' ' . (($value['product']['formula']['parent']['unity_system'] == true) ? $value['product']['formula']['parent']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['formula']['parent']['unity_name']) . ' / ' . $value['product']['formula']['parent']['name'] . ')' : '(' . $value['list'][0]['quantity'][1] . ') ' . $value['product']['name']) . '</strong></span></td>
									<td class="button"><a data-action="' . (($_POST['action'] == 'add_product_to_outputs_table') ? 'remove_product_to_outputs_table' : 'remove_product_to_transfers_table') . '" data-id="' . $value['product']['id'] . '_0" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
								</tr>';

								if (!empty($value['product']['formula']) AND !empty($value['product']['formula']['parent']))
								{
									if (!empty($value['list'][0]['supplies']))
									{
										foreach ($value['list'][0]['supplies'] as $subkey => $subvalue)
										{
											$table .=
											'<tr class="half">
												<td><span>' . '(' . number_format($subvalue['quantity'], 2, '.', '') . ' ' . (($subvalue['product']['unity_system'] == true) ? $subvalue['product']['unity_name'][Session::get_value('vkye_account')['language']] : $subvalue['product']['unity_name']) . ') ' . $subvalue['product']['name'] . '</span></td>
												<td></td>
											</tr>';
										}
									}
								}
							}

							if (!empty($value['supplies']))
							{
								foreach ($value['supplies'] as $subkey => $subvalue)
								{
									$table .=
									'<tr class="half">
										<td><span>' . '(' . number_format($subvalue['quantity'], 2, '.', '') . ' ' . (($subvalue['product']['unity_system'] == true) ? $subvalue['product']['unity_name'][Session::get_value('vkye_account')['language']] : $subvalue['product']['unity_name']) . ') ' . $subvalue['product']['name'] . '</span></td>
										<td></td>
									</tr>';
								}
							}
						}

						echo json_encode([
							'status' => 'success',
							'data' => [
								'table' => $table
							]
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

			if ($_POST['action'] == 'remove_product_to_inputs_table')
			{
				$temporal = System::temporal('get', 'inventories', 'inputs');

				if ($_POST['id'] == 'all')
					$temporal = [];
				else
				{
					$_POST['id'] = explode('_', $_POST['id']);

					unset($temporal[$_POST['id'][0]]['list'][$_POST['id'][1]]);

					if (empty($temporal[$_POST['id'][0]]['list']))
						unset($temporal[$_POST['id'][0]]);
				}

				System::temporal('set_forced', 'inventories', 'inputs', $temporal);

				$table = '';

				if (!empty($temporal))
				{
					foreach (array_reverse($temporal) as $value)
					{
						$table .=
						'<tr class="first">
							<td><span><strong>' . $value['product']['name'] . '</strong></span></td>
							<td></td>
						</tr>';

						foreach ($value['list'] as $subkey => $subvalue)
						{
							$table .=
							'<tr class="half">
								<td>
									<span>{$lang.quantity}: ' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? '(' . $subvalue['quantity'][0] . ') ' . $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' : '') . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? ')' : '') . '</span>
									<span>{$lang.unitary_cost}: ' . Currency::format($subvalue['cost'], Session::get_value('vkye_account')['currency']) . '</span>
									<span>{$lang.total_cost}: ' . Currency::format($subvalue['total'], Session::get_value('vkye_account')['currency']) . '</span>
									<span>' . (!empty($subvalue['location']) ? '{$lang.location}: ' . $subvalue['location']['name'] : '{$lang.not_location}') . '</span>
									<span>' . (!empty($subvalue['categories']) ? '{$lang.categories}: ' . System::summation('string', $subvalue['categories'], 'name') : '{$lang.not_categories}') . '</span>
								</td>
								<td class="button"><a data-action="remove_product_to_inputs_table" data-id="' . $value['product']['id'] . '_' . $subkey . '" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
							</tr>';
						}

						$table .=
						'<tr class="last">
							<td><span>' . Currency::format(System::summation('math', $value['list'], 'total'), Session::get_value('vkye_account')['currency']) . '</span></td>
							<td></td>
						</tr>';
					}

					$table .=
					'<tr class="last">
						<td><span><strong>' . Currency::format(System::summation('math', $temporal, 'total', 'list'), Session::get_value('vkye_account')['currency']) . '</strong></span></td>
						<td></td>
					</tr>';
				}
				else
				{
					$table .=
					'<tr>
						<td class="message">{$lang.not_records_in_the_table}</td>
					</tr>';
				}

				echo json_encode([
					'status' => 'success',
					'data' => [
						'table' => $table
					]
				]);
			}

			if ($_POST['action'] == 'remove_product_to_outputs_table' OR $_POST['action'] == 'remove_product_to_transfers_table')
			{
				if ($_POST['action'] == 'remove_product_to_outputs_table')
					$temporal = System::temporal('get', 'inventories', 'outputs');
				else if ($_POST['action'] == 'remove_product_to_transfers_table')
					$temporal = System::temporal('get', 'inventories', 'transfers');

				if ($_POST['id'] == 'all')
					$temporal = [];
				else
				{
					$_POST['id'] = explode('_', $_POST['id']);

					unset($temporal[$_POST['id'][0]]['list'][$_POST['id'][1]]);

					if (empty($temporal[$_POST['id'][0]]['list']))
						unset($temporal[$_POST['id'][0]]);
				}

				if ($_POST['action'] == 'remove_product_to_outputs_table')
					System::temporal('set_forced', 'inventories', 'outputs', $temporal);
				else if ($_POST['action'] == 'remove_product_to_transfers_table')
					System::temporal('set_forced', 'inventories', 'transfers', $temporal);

				$table = '';

				if (!empty($temporal))
				{
					foreach (array_reverse($temporal) as $key => $value)
					{
						if ($value['product']['inventory'] == true)
						{
							foreach ($value['list'] as $subkey => $subvalue)
							{
								$table .=
								'<tr class="first">
									<td><span><strong>' . '(' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $subvalue['quantity'][0] : number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name'])) . ') ' . $value['product']['name'] . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? ' ' . $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')' : '') . '</strong></span></td>
									<td class="button"><a data-action="' . (($_POST['action'] == 'remove_product_to_outputs_table') ? 'remove_product_to_outputs_table' : 'remove_product_to_transfers_table') . '" data-id="' . $value['product']['id'] . '_' . $subkey . '" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
								</tr>';
							}
						}
						else
						{
							$table .=
							'<tr class="first">
								<td><span><strong>' . ((!empty($value['product']['formula']) AND !empty($value['product']['formula']['parent'])) ? '(' . $value['list'][0]['quantity'][0] . ') ' . $value['product']['name'] . ' (' . number_format($value['list'][0]['quantity'][1], 2, '.', '') . ' ' . (($value['product']['formula']['parent']['unity_system'] == true) ? $value['product']['formula']['parent']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['formula']['parent']['unity_name']) . ' / ' . $value['product']['formula']['parent']['name'] . ')' : '(' . $value['list'][0]['quantity'][1] . ') ' . $value['product']['name']) . '</strong></span></td>
								<td class="button"><a data-action="' . (($_POST['action'] == 'remove_product_to_outputs_table') ? 'remove_product_to_outputs_table' : 'remove_product_to_transfers_table') . '" data-id="' . $value['product']['id'] . '_0" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
							</tr>';

							if (!empty($value['product']['formula']) AND !empty($value['product']['formula']['parent']))
							{
								if (!empty($value['list'][0]['supplies']))
								{
									foreach ($value['list'][0]['supplies'] as $subkey => $subvalue)
									{
										$table .=
										'<tr class="half">
											<td><span>' . '(' . number_format($subvalue['quantity'], 2, '.', '') . ' ' . (($subvalue['product']['unity_system'] == true) ? $subvalue['product']['unity_name'][Session::get_value('vkye_account')['language']] : $subvalue['product']['unity_name']) . ') ' . $subvalue['product']['name'] . '</span></td>
											<td></td>
										</tr>';
									}
								}
							}
						}

						if (!empty($value['supplies']))
						{
							foreach ($value['supplies'] as $subkey => $subvalue)
							{
								$table .=
								'<tr class="half">
									<td><span>' . '(' . number_format($subvalue['quantity'], 2, '.', '') . ' ' . (($subvalue['product']['unity_system'] == true) ? $subvalue['product']['unity_name'][Session::get_value('vkye_account')['language']] : $subvalue['product']['unity_name']) . ') ' . $subvalue['product']['name'] . '</span></td>
									<td></td>
								</tr>';
							}
						}
					}
				}
				else
				{
					$table .=
					'<tr>
						<td class="message">{$lang.not_records_in_the_table}</td>
					</tr>';
				}

				echo json_encode([
					'status' => 'success',
					'data' => [
						'table' => $table
					]
				]);
			}

			if ($_POST['action'] == 'create_inventory_input')
			{
				$errors = [];

				if (Validations::empty($_POST['product_id']) == true)
					array_push($errors, ['product_id','{$lang.add_this_product}']);

				if (Validations::empty(System::temporal('get', 'inventories', 'inputs')) == false)
					array_push($errors, ['product_token','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['date']) == false)
					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);
				else if ($_POST['date'] > Dates::current_date())
					array_push($errors, ['date','{$lang.invalid_field}']);
				else if ($this->model->check_exist_inventory_period($_POST['date']) == true)
					array_push($errors, ['date','{$lang.this_period_already_closed}']);

				if (Validations::empty($_POST['hour']) == false)
					array_push($errors, ['hour','{$lang.dont_leave_this_field_empty}']);

				if (Validations::empty($_POST['type']) == false)
					array_push($errors, ['type','{$lang.dont_leave_this_field_empty}']);

				if (Validations::equals($_POST['bill_type'], ['bill','ticket']) == true AND Validations::empty($_POST['bill_token']) == false)
					array_push($errors, ['bill_token','{$lang.dont_leave_this_field_empty}']);

				if (Validations::equals($_POST['bill_type'], ['bill','ticket']) == true AND Validations::empty($_POST['bill_payment_way']) == false)
					array_push($errors, ['bill_payment_way','{$lang.dont_leave_this_field_empty}']);

				if (Validations::equals($_POST['bill_type'], ['bill','ticket']) == true AND Validations::number('float', $_POST['bill_iva'], true) == false)
					array_push($errors, ['bill_iva','{$lang.invalid_field}']);

				if (Validations::equals($_POST['bill_type'], ['bill','ticket']) == true AND Validations::number('float', $_POST['bill_discount_amount'], true) == false)
					array_push($errors, ['bill_discount_amount','{$lang.invalid_field}']);

				if (empty($errors))
				{
					$query = $this->model->create_inventory_input($_POST);

					if (!empty($query))
					{
						System::temporal('set_forced', 'inventories', 'inputs', []);

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

			if ($_POST['action'] == 'create_inventory_output' OR $_POST['action'] == 'create_inventory_transfer')
			{
				$errors = [];

				if (Validations::empty($_POST['product_id']) == true)
					array_push($errors, ['product_id','{$lang.add_this_product}']);

				if (($_POST['action'] == 'create_inventory_output' AND Validations::empty(System::temporal('get', 'inventories', 'outputs')) == false) OR ($_POST['action'] == 'create_inventory_transfer' AND Validations::empty(System::temporal('get', 'inventories', 'transfers')) == false))
					array_push($errors, ['product_token','{$lang.dont_leave_this_field_empty}']);

				if ($_POST['action'] == 'create_inventory_output' AND Validations::empty($_POST['date']) == false)
					array_push($errors, ['date','{$lang.dont_leave_this_field_empty}']);
				else if ($_POST['action'] == 'create_inventory_output' AND $_POST['date'] > Dates::current_date())
					array_push($errors, ['date','{$lang.invalid_field}']);
				else if ($_POST['action'] == 'create_inventory_output' AND $this->model->check_exist_inventory_period($_POST['date']) == true)
					array_push($errors, ['date','{$lang.this_period_already_closed}']);

				if ($_POST['action'] == 'create_inventory_output' AND Validations::empty($_POST['hour']) == false)
					array_push($errors, ['hour','{$lang.dont_leave_this_field_empty}']);

				if ($_POST['action'] == 'create_inventory_output' AND Validations::empty($_POST['type']) == false)
					array_push($errors, ['type','{$lang.dont_leave_this_field_empty}']);

				if ($_POST['action'] == 'create_inventory_transfer' AND Validations::empty($_POST['branch']) == false)
					array_push($errors, ['branch','{$lang.dont_leave_this_field_empty}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_inventory_output')
						$query = $this->model->create_inventory_output($_POST);
					else if ($_POST['action'] == 'create_inventory_transfer')
						$query = $this->model->create_inventory_transfer($_POST);

					if (!empty($query))
					{
						if ($_POST['action'] == 'create_inventory_output')
							System::temporal('set_forced', 'inventories', 'outputs', []);
						else if ($_POST['action'] == 'create_inventory_transfer')
							System::temporal('set_forced', 'inventories', 'transfers', []);

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
				$query = $this->model->read_product((!empty($_POST['token']) ? $_POST['token'] : $_POST['id']), (!empty($_POST['token']) ? 'token' : 'id'));

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'data' => [
							'product' => $query,
							'products_unities' => $this->model->read_products_unities()
						]
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

			// if ($_POST['action'] == 'read_inventory_movement')
			// {
			// 	$query = $this->model->read_inventory_movement($_POST['id']);
			//
			// 	if (!empty($query))
			// 	{
			// 		$html =
			// 		'<div>
			// 			<figure>
			// 				<img src="' . (!empty($query['product_avatar']) ? '{$path.images}' . $query['product_avatar'] : '{$path.images}product.png') . '">
			// 			</figure>
			// 			<div>
			// 				<h2>' . $query['product_name'] . '</h2>
			// 				<p>' . $query['product_token'] . ' | {$lang.' . $query['product_type'] . '}</p>
			// 			</div>
			// 		</div>
			// 		<div>';
			//
			// 		if ($query['movement'] == 'input')
			// 			$html .= '<i class="fas fa-plus"></i>';
			// 		else if ($query['movement'] == 'output')
			// 			$html .= '<i class="fas fa-minus"></i>';
			//
			// 		$html .=
			// 		'	<div>
			// 				<h2>{$lang.' . $query['movement'] . '}</h2>
			// 				<p>' . (($query['type_system'] == true) ? $query['type_name'][Session::get_value('vkye_account')['language']] : $query['type_name']) . ' | ' . number_format($query['quantity'], 2, '.', '') . ' ' . (($query['product_unity_system'] == true) ? $query['product_unity_name'][Session::get_value('vkye_account')['language']] : $query['product_unity_name']) . ' {$lang.the} ' . Dates::format_date($query['date'], 'long') . ' {$lang.at} ' . Dates::format_hour($query['hour'], '12-long') . '</p>
			// 			</div>
			// 		</div>';
			//
			// 		if (!empty($query['transfer_branch']))
			// 		{
			// 			$html .=
			// 			'<div>
			// 				<i class="fas fa-store"></i>
			// 				<div>';
			//
			// 			if ($query['movement'] == 'input')
			// 				$html .= '<h2>{$lang.transfered_from}</h2>';
			// 			else if ($query['movement'] == 'output')
			// 				$html .= '<h2>{$lang.transfered_to}</h2>';
			//
			// 			$html .=
			// 			'		<p>' . $query['transfer_branch'] . '</p>
			// 				</div>
			// 			</div>';
			// 		}
			//
			// 		$html .=
			// 		'<div>
			// 			<i class="fas fa-weight"></i>
			// 			<div>
			// 				<h2>{$lang.weight}</h2>
			// 				<p>' . (!empty($query['weight']) ? $query['weight'] . ' {$lang.gramers}' : '{$lang.not_weight}') . '</p>
			// 			</div>
			// 		</div>';
			//
			// 		if ($query['movement'] == 'input')
			// 		{
			// 			$html .=
			// 			'<div>
			// 				<i class="fas fa-dollar-sign"></i>
			// 				<div>
			// 					<h2>{$lang.cost}</h2>
			// 					<p>' . (!empty($query['cost']) ? Currency::format($query['cost'], Session::get_value('vkye_account')['currency']) : '{$lang.not_cost}') . '</p>
			// 				</div>
			// 			</div>
			// 			<div>
			// 				<i class="fas fa-map-marker-alt"></i>
			// 				<div>
			// 					<h2>{$lang.location}</h2>
			// 					<p>' . (!empty($query['location']) ? $query['location'] : '{$lang.not_location}') . '</p>
			// 				</div>
			// 			</div>
			// 			<div>
			// 				<i class="fas fa-shapes"></i>
			// 				<div>
			// 					<h2>{$lang.categories}</h2>
			// 					<p>';
			//
			// 			if (!empty($query['categories']))
			// 			{
			// 				foreach ($query['categories'] as $value)
			// 					$html .= $value . ', ';
			// 			}
			// 			else
			// 				$html .= '{$lang.not_categories}';
			//
			// 			$html .=
			// 			'		</p>
			// 				</div>
			// 			</div>
			// 			<div>
			// 				<i class="fas fa-truck"></i>
			// 				<div>
			// 					<h2>{$lang.provider}</h2>
			// 					<p>' . (!empty($query['provider']) ? $query['provider'] : '{$lang.not_provider}') . '</p>
			// 				</div>
			// 			</div>
			// 			<div>
			// 				<i class="fas fa-file-invoice-dollar"></i>
			// 				<div>
			// 					<h2>' . (!empty($query['bill_token']) ? '{$lang.' . $query['bill_type'] . '}' : '{$lang.bill}') . '</h2>
			// 					<p>' . (!empty($query['bill_token']) ? '{$lang.' . $query['bill_payment']['way'] . '} | #' . $query['bill_token'] : '{$lang.not_bill}') . '</p>
			// 				</div>
			// 			</div>';
			// 		}
			//
			// 		$html .=
			// 		'<div>
			// 			<i class="fas fa-box"></i>
			// 			<div>
			// 				<h2>' . ((System::temporal('get', 'inventories', 'period') == 'current') ? '{$lang.current_period}' : '{$lang.closed_period}') . '</h2>
			// 				<p>' . ((System::temporal('get', 'inventories', 'period') == 'current') ? Dates::format_date(Dates::current_date(), 'long') : '{$lang.from} ' . Dates::format_date($query['period']['started_date'], 'long') . ' {$lang.to} ' . Dates::format_date($query['period']['end_date'], 'long')) . '</p>
			// 			</div>
			// 		</div>
			// 		<div>
			// 			<figure>
			// 				<img src="' . (!empty($query['user_avatar']) ? '{$path.uploads}' . $query['user_avatar'] : 'https://cdn.codemonkey.com.mx/monkeyboard/assets/images/user.png') . '">
			// 			</figure>
			// 			<div>
			// 				<h2>{$lang.movement_by}</h2>
			// 				<p>' . $query['user_firstname'] . ' ' . $query['user_lastname'] . ' {$lang.the} ' . Dates::format_date($query['created_date'], 'long') . ' {$lang.at} ' . Dates::format_hour($query['created_hour'], '12-long') . '</p>
			// 			</div>
			// 		</div>';
			//
			// 		echo json_encode([
			// 			'status' => 'success',
			// 			'data' => [
			// 				'html' => $html
			// 			]
			// 		]);
			// 	}
			// 	else
			// 	{
			// 		echo json_encode([
			// 			'status' => 'error',
			// 			'message' => '{$lang.operation_error}'
			// 		]);
			// 	}
			// }

			if ($_POST['action'] == 'delete_inventory_movement')
			{
				$query = $this->model->delete_inventory_movement($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories} | {$lang.movements}');

			global $data;

			$data['branches'] = $this->model->read_branches();

			if (!empty($data['branches']))
			{
				if (System::temporal('get_if_exists', 'inventories', 'branch') == false)
					System::temporal('set_forced', 'inventories', 'branch', Permissions::redirection('branch', $data['branches']));

				if (System::temporal('get_if_exists', 'inventories', 'period') == false)
					System::temporal('set_forced', 'inventories', 'period', 'current');

				$data['inventories_periods'] = $this->model->read_inventories_periods();
				$data['inventories_movements'] = $this->model->read_inventories_movements();
				$data['inventories_transfers'] = $this->model->read_inventories_transfers();
				$data['products_inputs'] = $this->model->read_products('input');
				$data['products_outputs'] = $this->model->read_products('output');
				$data['inventories_locations'] = $this->model->read_inventories_locations(true);
				$data['inventories_categories'] = $this->model->read_inventories_categories(true);
				$data['inventories_types_inputs'] = $this->model->read_inventories_types('input', true);
				$data['inventories_types_outputs'] = $this->model->read_inventories_types('output', true);
				$data['providers'] = $this->model->read_providers();
			}

			$template = $this->view->render($this, 'movements');

			echo $template;
		}
	}

	public function audits($params)
	{
		global $data;

		if (!empty($params[0]) AND $params[0] == 'create')
			$data['render'] = 'create';
		else if (!empty($params[0]) AND $params[0] == 'update')
			$data['render'] = 'update';
		else if (!empty($params[0]) AND $params[0] == 'results')
			$data['render'] = 'results';
		else
			$data['render'] = 'list';

		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'switch_branch')
			{
				$query = $this->model->read_branch($_POST['id']);

				if (!empty($query))
				{
					System::temporal('set_forced', 'inventories', 'branch', $query);

					if ($data['render'] == 'create')
						$path = '/inventories/audits/create';
					else if ($data['render'] == 'list')
						$path = '/inventories/audits';

					echo json_encode([
						'status' => 'success',
						'path' => $path
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

			if ($_POST['action'] == 'switch_inventory_period')
			{
				System::temporal('set_forced', 'inventories', 'period', $_POST['id']);

				echo json_encode([
					'status' => 'success'
				]);
			}

			if ($_POST['action'] == 'add_product_to_physical_table')
			{
				$errors = [];

				if (Validations::empty($_POST['product_id']) == false)
					array_push($errors, ['product_id','{$lang.dont_leave_this_field_empty}']);
				else
				{
					$_POST['product'] = $this->model->read_product($_POST['product_id'], 'id');

					if (!empty($_POST['product']))
					{
						if (Validations::empty($_POST['saved']) == false)
							array_push($errors, ['saved','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'weight') == true AND Validations::empty($_POST['weight']) == false)
							array_push($errors, ['weight','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'weight') == true AND Validations::empty($_POST['content']) == false)
							array_push($errors, ['weight','{$lang.select_an_content}']);
					}
					else
						array_push($errors, ['product_token','{$lang.this_record_dont_exists}']);
				}

				if (empty($errors))
				{
					$_POST['quantity'] = [(($_POST['saved'] == 'quantity') ? (!empty($_POST['quantity']) ? $_POST['quantity'] : '0') : $_POST['weight']), ((($_POST['saved'] == 'quantity' AND !empty($_POST['content']) OR $_POST['saved'] == 'weight')) ? '0' : (($_POST['saved'] == 'quantity') ? (!empty($_POST['quantity']) ? $_POST['quantity'] : '0') : $_POST['weight']))];
					$_POST['content'] = !empty($_POST['content']) ? explode('_', $_POST['content']) : [];

					if ($_POST['saved'] == 'quantity' AND !empty($_POST['content']))
					{
						$_POST['quantity'][1] = ($_POST['quantity'][0] * $_POST['product']['contents'][$_POST['content'][1]]['content']['amount']);
						$_POST['quantity'][1] = Functions::conversion('unity', $_POST['quantity'][1], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code'], $_POST['product']['unity_code']);
					}
					else if ($_POST['saved'] == 'weight')
					{
						if ($_POST['content'][0] == 'cnt')
						{
							$_POST['weight'] = ($_POST['weight'] - $_POST['product']['contents'][$_POST['content'][1]]['weight']['amount']);
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['weight'], $_POST['product']['contents'][$_POST['content'][1]]['weight']['unity_code'], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code']);
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['quantity'][1], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code'], $_POST['product']['unity_code']);
						}
						else if ($_POST['content'][0] == 'unt')
						{
							$unity_1 = $this->model->read_product_unity($_POST['content'][1], 'code');
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['weight'], $unity_1, $_POST['product']['unity_code']);
						}
					}

					$temporal = System::temporal('get', 'inventories', 'audit');
					$content = (!empty($_POST['content']) ? $_POST['content'][1] : '0') . '-' . (!empty($_POST['cost']) ? $_POST['cost'] : '0') . '-' . (!empty($_POST['location']) ? $_POST['location']['id'] : '0') . '-' . (!empty($_POST['categories']) ? System::summation('string', $_POST['categories'], 'id', null, '-') : '0');

					if (array_key_exists($_POST['product']['id'], $temporal['physical']))
					{
						if (array_key_exists($content, $temporal['physical'][$_POST['product']['id']]['list']))
						{
							$temporal['physical'][$_POST['product']['id']]['list'][$content]['quantity'][0] += $_POST['quantity'][0];
							$temporal['physical'][$_POST['product']['id']]['list'][$content]['quantity'][1] += $_POST['quantity'][1];
						}
						else
						{
							$temporal['physical'][$_POST['product']['id']]['list'][$content] = [
								'quantity' => $_POST['quantity'],
								'content' => $_POST['content']
							];
						}
					}
					else
					{
						$temporal['physical'][$_POST['product']['id']] = [
							'product' => $_POST['product'],
							'list' => [
								$content => [
									'quantity' => $_POST['quantity'],
									'content' => $_POST['content']
								]
							]
						];
					}

					System::temporal('set_forced', 'inventories', 'audit', $temporal);

					$table = '';

					foreach (array_reverse($temporal['physical']) as $value)
					{
						foreach ($value['list'] as $subkey => $subvalue)
						{
							$table .=
							'<tr class="first">
								<td><span>' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? '(' . $subvalue['quantity'][0] . ')' : '(' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')') . ' ' . $value['product']['name'] . ' ' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')' : '') . '</span></td>
								<td class="button"><a data-action="remove_product_to_physical_table" data-id="' . $value['product']['id'] . '_' . $subkey . '" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
							</tr>';
						}
					}

					echo json_encode([
						'status' => 'success',
						'data' => [
							'table' => $table
						]
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}

			if ($_POST['action'] == 'remove_product_to_physical_table')
			{
				$temporal = System::temporal('get', 'inventories', 'audit');

				if ($_POST['id'] == 'all')
					$temporal['physical'] = [];
				else
				{
					$_POST['id'] = explode('_', $_POST['id']);

					unset($temporal['physical'][$_POST['id'][0]]['list'][$_POST['id'][1]]);

					if (empty($temporal['physical'][$_POST['id'][0]]['list']))
						unset($temporal['physical'][$_POST['id'][0]]);
				}

				System::temporal('set_forced', 'inventories', 'audit', $temporal);

				$table = '';

				if (!empty($temporal['physical']))
				{
					foreach (array_reverse($temporal['physical']) as $value)
					{
						foreach ($value['list'] as $subkey => $subvalue)
						{
							$table .=
							'<tr class="first">
								<td><span>' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? '(' . $subvalue['quantity'][0] . ')' : '(' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')') . ' ' . $value['product']['name'] . ' ' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')' : '') . '</span></td>
								<td class="button"><a data-action="remove_product_to_physical_table" data-id="' . $value['product']['id'] . '_' . $subkey . '" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
							</tr>';
						}
					}
				}
				else
				{
					$table .=
					'<tr>
						<td class="message">{$lang.not_records_in_the_table}</td>
					</tr>';
				}

				echo json_encode([
					'status' => 'success',
					'data' => [
						'table' => $table
					]
				]);
			}

			if ($_POST['action'] == 'add_products_to_inventory_audit')
			{
				$temporal = System::temporal('get', 'inventories', 'audit');

				foreach ($temporal['physical'] as $value)
				{
					$existence = $this->model->read_inventory_existence($value['product']['id']);

					if ($existence['existence']['physical'] >= $existence['existence']['theoretical'])
						$status = 'success';
					else if ($existence['existence']['physical'] < $existence['existence']['theoretical'])
						$status = 'error';

					if (array_key_exists($value['product']['id'], $temporal['products']))
						$temporal['products'][$value['product']['id']]['status'] = $status;
					else
					{
						$temporal['products'][$value['product']['id']] = [
							'theoretical' => 0,
							'physical' => 0,
							'variation' => 0,
							'status' => $status,
							'comment' => ''
						];
					}
				}

				System::temporal('set_forced', 'inventories', 'audit', $temporal);

				echo json_encode([
					'status' => 'success'
				]);
			}

			if ($_POST['action'] == 'add_success_to_inventory_audit' OR $_POST['action'] == 'add_error_to_inventory_audit')
			{
				$temporal = System::temporal('get', 'inventories', 'audit');

				$status = '';

				if (array_key_exists($_POST['id'], $temporal['products']))
				{
					if (($_POST['action'] == 'add_success_to_inventory_audit' AND $temporal['products'][$_POST['id']]['status'] == 'success') OR ($_POST['action'] == 'add_error_to_inventory_audit' AND $temporal['products'][$_POST['id']]['status'] == 'error'))
						unset($temporal['products'][$_POST['id']]);
					else
					{
						if ($_POST['action'] == 'add_success_to_inventory_audit' AND $temporal['products'][$_POST['id']]['status'] == 'error')
							$status = 'success';
						else if ($_POST['action'] == 'add_error_to_inventory_audit' AND $temporal['products'][$_POST['id']]['status'] == 'success')
							$status = 'error';

						$temporal['products'][$_POST['id']]['status'] = $status;
					}
				}
				else
				{
					if ($_POST['action'] == 'add_success_to_inventory_audit')
						$status = 'success';
					else if ($_POST['action'] == 'add_error_to_inventory_audit')
						$status = 'error';

					$temporal['products'][$_POST['id']] = [
						'theoretical' => 0,
						'physical' => 0,
						'variation' => 0,
						'status' => $status,
						'comment' => ''
					];
				}

				System::temporal('set_forced', 'inventories', 'audit', $temporal);

				echo json_encode([
					'status' => $status
				]);
			}

			if ($_POST['action'] == 'add_comment_to_inventory_audit')
			{
				$temporal = System::temporal('get', 'inventories', 'audit');

				if (array_key_exists($_POST['id'], $temporal['products']))
				{
					if (!empty($_POST['status']))
					{
						$temporal['products'][$_POST['id']]['status'] = $_POST['status'];
						$temporal['products'][$_POST['id']]['comment'] = $_POST['comment'];
					}
					else
						unset($temporal['products'][$_POST['id']]);
				}
				else
				{
					if (!empty($_POST['status']))
					{
						$temporal['products'][$_POST['id']] = [
							'theoretical' => 0,
							'physical' => 0,
							'variation' => 0,
							'status' => $_POST['status'],
							'comment' => $_POST['comment']
						];
					}
				}

				System::temporal('set_forced', 'inventories', 'audit', $temporal);

				echo json_encode([
					'status' => $_POST['status'],
					'comment' => $_POST['comment']
				]);
			}

			if ($_POST['action'] == 'read_comment_to_inventory_audit')
			{
				$temporal = System::temporal('get', 'inventories', 'audit');

				$status = '';
				$comment = '';

				if (array_key_exists($_POST['id'], $temporal['products']))
				{
					$status = $temporal['products'][$_POST['id']]['status'];
					$comment = $temporal['products'][$_POST['id']]['comment'];
				}

				echo json_encode([
					'status' => $status,
					'comment' => $comment
				]);
			}

			if ($_POST['action'] == 'create_inventory_audit' OR $_POST['action'] == 'update_inventory_audit')
			{
				$errors = [];

				if (Validations::empty($_POST['started_date']) == false)
					array_push($errors, ['started_date','{$lang.dont_leave_this_field_empty}']);
				else if ($_POST['started_date'] >= $_POST['end_date'])
					array_push($errors, ['started_date','{$lang.invalid_field}']);
				else if ($this->model->check_exist_inventory_audit($_POST['started_date']) == true)
					array_push($errors, ['started_date','{$lang.this_audit_already_closed}']);

				if (Validations::empty($_POST['end_date']) == false)
					array_push($errors, ['end_date','{$lang.dont_leave_this_field_empty}']);
				else if ($_POST['started_date'] >= $_POST['end_date'])
					array_push($errors, ['end_date','{$lang.invalid_field}']);

				if (Validations::empty($_POST['saved']) == false)
					array_push($errors, ['saved','{$lang.dont_leave_this_field_empty}']);
				else if ($this->model->check_exist_inventory_audit('draft') == true)
					array_push($errors, ['saved','{$lang.this_draft_audit_already_exist}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_inventory_audit')
						$query = $this->model->create_inventory_audit($_POST);
					else if ($_POST['action'] == 'update_inventory_audit')
					{
						$_POST['id'] = $params[1];

						$query = $this->model->update_inventory_audit($_POST);
					}

					if (!empty($query))
					{
						System::temporal('set_forced', 'inventories', 'audit', [
							'physical' => [],
							'products' => [],
							'render' => ''
						]);

						echo json_encode([
							'status' => 'success',
							'message' => '{$lang.operation_success}',
							'path' => '/inventories/audits'
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
				$query = $this->model->read_product((!empty($_POST['token']) ? $_POST['token'] : $_POST['id']), (!empty($_POST['token']) ? 'token' : 'id'));

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'data' => [
							'product' => $query,
							'products_unities' => $this->model->read_products_unities()
						]
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

			if ($_POST['action'] == 'delete_inventory_audit')
			{
				$query = $this->model->delete_inventory_audit($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories} | {$lang.audits}');

			$data['branches'] = $this->model->read_branches();

			if (!empty($data['branches']))
			{
				if (System::temporal('get_if_exists', 'inventories', 'branch') == false)
					System::temporal('set_forced', 'inventories', 'branch', Permissions::redirection('branch', $data['branches']));

				if ($data['render'] == 'create' OR $data['render'] == 'update' OR (($data['render'] == 'results' OR $data['render'] == 'list') AND System::temporal('get_if_exists', 'inventories', 'period') == false))
					System::temporal('set_forced', 'inventories', 'period', 'current');

				if ((System::temporal('get_if_exists', 'inventories', 'audit') == true AND $data['render'] == 'create' AND (System::temporal('get', 'inventories', 'audit')['render'] == 'update' OR System::temporal('get', 'inventories', 'audit')['render'] == 'results')) OR System::temporal('get_if_exists', 'inventories', 'audit') == false)
				{
					System::temporal('set_forced', 'inventories', 'audit', [
						'physical' => [],
						'products' => [],
						'render' => ''
					]);
				}

				if ($data['render'] == 'create' OR $data['render'] == 'update' OR $data['render'] == 'results')
				{
					if ($data['render'] == 'update' OR $data['render'] == 'results')
					{
						$data['audit'] = $this->model->read_inventory_audit($params[1]);

						if (!empty($data['audit']))
						{
							$temporal = System::temporal('get', 'inventories', 'audit');

							if (($data['render'] == 'update' AND $temporal['render'] != 'update') OR ($data['render'] == 'results' AND $temporal['render'] != 'results'))
							{
								$temporal['physical'] = [];
								$temporal['products'] = [];

								foreach ($data['audit']['physical'] as $key => $value)
								{
									if (!array_key_exists($key, $temporal['physical']))
										$temporal['physical'][$key] = $value;
								}

								foreach ($data['audit']['products'] as $key => $value)
								{
									if (!array_key_exists($key, $temporal['products']))
										$temporal['products'][$key] = $value;
								}

								$temporal['render'] = $data['render'];
							}

							System::temporal('set_forced', 'inventories', 'audit', $temporal);

							if ($data['render'] == 'results')
								$data['inventories_existences'] = $this->model->read_inventories_existences('audit', $data['audit']['products']);
						}
						else
							header('Location: /inventories/audits');
					}

					if ($data['render'] == 'create' OR $data['render'] == 'update')
					{
						$data['inventories_existences'] = $this->model->read_inventories_existences('audit');
						$data['products'] = $this->model->read_products('input');
					}
				}
				else if ($data['render'] == 'list')
				{
					$data['inventories_periods'] = $this->model->read_inventories_periods();
					$data['inventories_audits'] = $this->model->read_inventories_audits();
				}
			}
			else if (empty($data['branches']) AND !empty($params[0]))
				header('Location: /inventories/audits');

			$template = $this->view->render($this, 'audits');

			echo $template;
		}
	}

	public function periods($params)
	{
		global $data;

		if (!empty($params[0]) AND $params[0] == 'create')
			$data['render'] = 'create';
		else if (!empty($params[0]) AND $params[0] == 'update')
			$data['render'] = 'update';
		else if (!empty($params[0]) AND $params[0] == 'results')
			$data['render'] = 'results';
		else
			$data['render'] = 'list';

		if (Format::exist_ajax_request() == true)
		{
			if ($_POST['action'] == 'switch_branch')
			{
				$query = $this->model->read_branch($_POST['id']);

				if (!empty($query))
				{
					System::temporal('set_forced', 'inventories', 'branch', $query);

					if ($data['render'] == 'create')
						$path = '/inventories/periods/create';
					else if ($data['render'] == 'list')
						$path = '/inventories/periods';

					echo json_encode([
						'status' => 'success',
						'path' => $path
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

			if ($_POST['action'] == 'add_product_to_physical_table')
			{
				$errors = [];

				if (Validations::empty($_POST['product_id']) == false)
					array_push($errors, ['product_id','{$lang.dont_leave_this_field_empty}']);
				else
				{
					$_POST['product'] = $this->model->read_product($_POST['product_id'], 'id');

					if (!empty($_POST['product']))
					{
						if (Validations::empty($_POST['saved']) == false)
							array_push($errors, ['saved','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'weight') == true AND Validations::empty($_POST['weight']) == false)
							array_push($errors, ['weight','{$lang.dont_leave_this_field_empty}']);

						if (Validations::equals($_POST['saved'], 'weight') == true AND Validations::empty($_POST['content']) == false)
							array_push($errors, ['weight','{$lang.select_an_content}']);
					}
					else
						array_push($errors, ['product_token','{$lang.this_record_dont_exists}']);
				}

				if (empty($errors))
				{
					$_POST['quantity'] = [(($_POST['saved'] == 'quantity') ? (!empty($_POST['quantity']) ? $_POST['quantity'] : '0') : $_POST['weight']), ((($_POST['saved'] == 'quantity' AND !empty($_POST['content']) OR $_POST['saved'] == 'weight')) ? '0' : (($_POST['saved'] == 'quantity') ? (!empty($_POST['quantity']) ? $_POST['quantity'] : '0') : $_POST['weight']))];
					$_POST['content'] = !empty($_POST['content']) ? explode('_', $_POST['content']) : [];

					if ($_POST['saved'] == 'quantity' AND !empty($_POST['content']))
					{
						$_POST['quantity'][1] = ($_POST['quantity'][0] * $_POST['product']['contents'][$_POST['content'][1]]['content']['amount']);
						$_POST['quantity'][1] = Functions::conversion('unity', $_POST['quantity'][1], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code'], $_POST['product']['unity_code']);
					}
					else if ($_POST['saved'] == 'weight')
					{
						if ($_POST['content'][0] == 'cnt')
						{
							$_POST['weight'] = ($_POST['weight'] - $_POST['product']['contents'][$_POST['content'][1]]['weight']['amount']);
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['weight'], $_POST['product']['contents'][$_POST['content'][1]]['weight']['unity_code'], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code']);
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['quantity'][1], $_POST['product']['contents'][$_POST['content'][1]]['content']['unity_code'], $_POST['product']['unity_code']);
						}
						else if ($_POST['content'][0] == 'unt')
						{
							$unity_1 = $this->model->read_product_unity($_POST['content'][1], 'code');
							$_POST['quantity'][1] = Functions::conversion('unity', $_POST['weight'], $unity_1, $_POST['product']['unity_code']);
						}
					}

					$temporal = System::temporal('get', 'inventories', 'aperiod');
					$content = (!empty($_POST['content']) ? $_POST['content'][1] : '0') . '-' . (!empty($_POST['cost']) ? $_POST['cost'] : '0') . '-' . (!empty($_POST['location']) ? $_POST['location']['id'] : '0') . '-' . (!empty($_POST['categories']) ? System::summation('string', $_POST['categories'], 'id', null, '-') : '0');

					if (array_key_exists($_POST['product']['id'], $temporal['physical']))
					{
						if (array_key_exists($content, $temporal['physical'][$_POST['product']['id']]['list']))
						{
							$temporal['physical'][$_POST['product']['id']]['list'][$content]['quantity'][0] += $_POST['quantity'][0];
							$temporal['physical'][$_POST['product']['id']]['list'][$content]['quantity'][1] += $_POST['quantity'][1];
						}
						else
						{
							$temporal['physical'][$_POST['product']['id']]['list'][$content] = [
								'quantity' => $_POST['quantity'],
								'content' => $_POST['content']
							];
						}
					}
					else
					{
						$temporal['physical'][$_POST['product']['id']] = [
							'product' => $_POST['product'],
							'list' => [
								$content => [
									'quantity' => $_POST['quantity'],
									'content' => $_POST['content']
								]
							]
						];
					}

					System::temporal('set_forced', 'inventories', 'aperiod', $temporal);

					$table = '';

					foreach (array_reverse($temporal['physical']) as $value)
					{
						foreach ($value['list'] as $subkey => $subvalue)
						{
							$table .=
							'<tr class="first">
								<td><span>' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? '(' . $subvalue['quantity'][0] . ')' : '(' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')') . ' ' . $value['product']['name'] . ' ' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')' : '') . '</span></td>
								<td class="button"><a data-action="remove_product_to_physical_table" data-id="' . $value['product']['id'] . '_' . $subkey . '" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
							</tr>';
						}
					}

					echo json_encode([
						'status' => 'success',
						'data' => [
							'table' => $table
						]
					]);
				}
				else
				{
					echo json_encode([
						'status' => 'error',
						'errors' => $errors
					]);
				}
			}

			if ($_POST['action'] == 'remove_product_to_physical_table')
			{
				$temporal = System::temporal('get', 'inventories', 'aperiod');

				if ($_POST['id'] == 'all')
					$temporal['physical'] = [];
				else
				{
					$_POST['id'] = explode('_', $_POST['id']);

					unset($temporal['physical'][$_POST['id'][0]]['list'][$_POST['id'][1]]);

					if (empty($temporal['physical'][$_POST['id'][0]]['list']))
						unset($temporal['physical'][$_POST['id'][0]]);
				}

				System::temporal('set_forced', 'inventories', 'aperiod', $temporal);

				$table = '';

				if (!empty($temporal['physical']))
				{
					foreach (array_reverse($temporal['physical']) as $value)
					{
						foreach ($value['list'] as $subkey => $subvalue)
						{
							$table .=
							'<tr class="first">
								<td><span>' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? '(' . $subvalue['quantity'][0] . ')' : '(' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')') . ' ' . $value['product']['name'] . ' ' . ((!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $value['product']['contents'][$subvalue['content'][1]]['content']['amount'] . ' ' . (($value['product']['contents'][$subvalue['content'][1]]['content']['unity_system'] == true) ? $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['contents'][$subvalue['content'][1]]['content']['unity_name']) . ' (' . number_format($subvalue['quantity'][1], 2, '.', '') . ' ' . (($value['product']['unity_system'] == true) ? $value['product']['unity_name'][Session::get_value('vkye_account')['language']] : $value['product']['unity_name']) . ')' : '') . '</span></td>
								<td class="button"><a data-action="remove_product_to_physical_table" data-id="' . $value['product']['id'] . '_' . $subkey . '" class="alert"><i class="fas fa-times"></i><span>{$lang.remove_to_table}</span></a></td>
							</tr>';
						}
					}
				}
				else
				{
					$table .=
					'<tr>
						<td class="message">{$lang.not_records_in_the_table}</td>
					</tr>';
				}

				echo json_encode([
					'status' => 'success',
					'data' => [
						'table' => $table
					]
				]);
			}

			if ($_POST['action'] == 'add_products_to_inventory_period')
			{
				$temporal = System::temporal('get', 'inventories', 'aperiod');

				foreach ($temporal['physical'] as $value)
				{
					$existence = $this->model->read_inventory_existence($value['product']['id']);

					if (!array_key_exists($value['product']['id'], $temporal['products']))
					{
						$temporal['products'][$value['product']['id']] = [
							'theoretical' => 0,
							'physical' => 0,
							'variation' => 0
						];
					}
				}

				System::temporal('set_forced', 'inventories', 'aperiod', $temporal);

				echo json_encode([
					'status' => 'success'
				]);
			}

			if ($_POST['action'] == 'create_inventory_period' OR $_POST['action'] == 'update_inventory_period')
			{
				$errors = [];

				if (Validations::empty($_POST['started_date']) == false)
					array_push($errors, ['started_date','{$lang.dont_leave_this_field_empty}']);
				else if ($_POST['started_date'] >= $_POST['end_date'])
					array_push($errors, ['started_date','{$lang.invalid_field}']);
				else if ($this->model->check_exist_inventory_period($_POST['started_date']) == true)
					array_push($errors, ['started_date','{$lang.this_period_already_closed}']);

				if (Validations::empty($_POST['end_date']) == false)
					array_push($errors, ['end_date','{$lang.dont_leave_this_field_empty}']);
				else if ($_POST['started_date'] >= $_POST['end_date'])
					array_push($errors, ['end_date','{$lang.invalid_field}']);

				if (Validations::empty($_POST['saved']) == false)
					array_push($errors, ['saved','{$lang.dont_leave_this_field_empty}']);
				else if ($this->model->check_exist_inventory_period('draft') == true)
					array_push($errors, ['saved','{$lang.this_draft_period_already_exist}']);

				if (empty($errors))
				{
					if ($_POST['action'] == 'create_inventory_period')
						$query = $this->model->create_inventory_period($_POST);
					else if ($_POST['action'] == 'update_inventory_period')
					{
						$_POST['id'] = $params[1];

						$query = $this->model->update_inventory_period($_POST);
					}

					if (!empty($query))
					{
						System::temporal('set_forced', 'inventories', 'aperiod', [
							'physical' => [],
							'products' => [],
							'render' => ''
						]);

						echo json_encode([
							'status' => 'success',
							'message' => '{$lang.operation_success}',
							'path' => '/inventories/periods'
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
				$query = $this->model->read_product((!empty($_POST['token']) ? $_POST['token'] : $_POST['id']), (!empty($_POST['token']) ? 'token' : 'id'));

				if (!empty($query))
				{
					echo json_encode([
						'status' => 'success',
						'data' => [
							'product' => $query,
							'products_unities' => $this->model->read_products_unities()
						]
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

			if ($_POST['action'] == 'delete_inventory_period')
			{
				$query = $this->model->delete_inventory_period($_POST['id']);

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories} | {$lang.periods}');

			$data['branches'] = $this->model->read_branches();

			if (!empty($data['branches']))
			{
				if (System::temporal('get_if_exists', 'inventories', 'branch') == false)
					System::temporal('set_forced', 'inventories', 'branch', Permissions::redirection('branch', $data['branches']));

				if ($data['render'] == 'create' OR $data['render'] == 'update' OR (($data['render'] == 'results' OR $data['render'] == 'list') AND System::temporal('get_if_exists', 'inventories', 'period') == false))
					System::temporal('set_forced', 'inventories', 'period', 'current');

				if ((System::temporal('get_if_exists', 'inventories', 'aperiod') == true AND $data['render'] == 'create' AND (System::temporal('get', 'inventories', 'aperiod')['render'] == 'update' OR System::temporal('get', 'inventories', 'aperiod')['render'] == 'results')) OR System::temporal('get_if_exists', 'inventories', 'aperiod') == false)
				{
					System::temporal('set_forced', 'inventories', 'aperiod', [
						'physical' => [],
						'products' => [],
						'render' => ''
					]);
				}

				if ($data['render'] == 'create' OR $data['render'] == 'update' OR $data['render'] == 'results')
				{
					if ($data['render'] == 'update' OR $data['render'] == 'results')
					{
						$data['period'] = $this->model->read_inventory_period($params[1]);

						if (!empty($data['period']))
						{
							$temporal = System::temporal('get', 'inventories', 'aperiod');

							if (($data['render'] == 'update' AND $temporal['render'] != 'update') OR ($data['render'] == 'results' AND $temporal['render'] != 'results'))
							{
								$temporal['physical'] = [];
								$temporal['products'] = [];

								foreach ($data['period']['physical'] as $key => $value)
								{
									if (!array_key_exists($key, $temporal['physical']))
										$temporal['physical'][$key] = $value;
								}

								foreach ($data['period']['products'] as $key => $value)
								{
									if (!array_key_exists($key, $temporal['products']))
										$temporal['products'][$key] = $value;
								}

								$temporal['render'] = $data['render'];
							}

							System::temporal('set_forced', 'inventories', 'aperiod', $temporal);

							if ($data['render'] == 'results')
								$data['inventories_existences'] = $this->model->read_inventories_existences('aperiod', $data['period']['products']);
						}
						else
							header('Location: /inventories/periods');
					}

					if ($data['render'] == 'create' OR $data['render'] == 'update')
					{
						$data['inventories_existences'] = $this->model->read_inventories_existences('aperiod');
						$data['products'] = $this->model->read_products('input');
					}
				}
				else if ($data['render'] == 'list')
					$data['inventories_periods'] = $this->model->read_inventories_periods();
			}
			else if (empty($data['branches']) AND !empty($params[0]))
				header('Location: /inventories/periods');

			$template = $this->view->render($this, 'periods');

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories} | {$lang.types}');

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories} | {$lang.locations}');

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
			define('_title', Configuration::$web_page . ' | {$lang.inventories} | {$lang.categories}');

			global $data;

			$data['inventories_categories'] = $this->model->read_inventories_categories();

			$template = $this->view->render($this, 'categories');

			echo $template;
		}
	}
}
