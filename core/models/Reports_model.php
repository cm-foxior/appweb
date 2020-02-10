<?php

defined('_EXEC') or die;

class Reports_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getInventories()
	{
		$query = $this->database->select('inventories', [
			'[>]branch_offices' => ['id_branch_office' => 'id_branch_office']
		], [
			'inventories.id_inventory',
			'inventories.name',
			'inventories.type',
			'branch_offices.name(branch)',
		], [
			'AND' => [
				'inventories.status' => true,
				'inventories.id_subscription' => Session::getValue('id_subscription'),
			]
		]);

		foreach ($query as $key => $value)
		{
			if ($value['type'] == '1')
				$query[$key]['type'] = 'Venta';
			else if ($value['type'] == '2')
				$query[$key]['type'] = 'Producción';
			else if ($value['type'] == '3')
				$query[$key]['type'] = 'Operación';
		}

		return $query;
	}

	public function getHistorical($data)
	{
		$products = [];

		if ($data['type'] == 'historical' OR $data['type'] == 'inputs')
		{
			$and_input = [
				'inventories_inputs.input_date_time[<>]' => [$data['date_start'],$data['date_end']],
				'inventories_inputs.id_inventory' => $data['inventory'],
			];

			if (!empty($data['category_one']))
				$and_input['products.id_product_category_one'] = $data['category_one'];

			if (!empty($data['category_two']))
				$and_input['products.id_product_category_two'] = $data['category_two'];

			if (!empty($data['category_tree']))
				$and_input['products.id_product_category_tree'] = $data['category_tree'];

			if (!empty($data['category_four']))
				$and_input['products.id_product_category_four'] = $data['category_four'];

			if (!empty($data['bill']))
				$and_input['inventories_inputs.bill'] = $data['bill'];

			if (!empty($data['movement']) AND $data['type'] == 'inputs')
				$and_input['inventories_inputs.type'] = $data['movement'];

			$inputs = $this->database->select('inventories_inputs', [
				'[>]products' => ['id_product' => 'id_product'],
				'[>]products_categories_one' => ['products.id_product_category_one' => 'id_product_category_one'],
				'[>]products_categories_two' => ['products.id_product_category_two' => 'id_product_category_two'],
				'[>]products_categories_tree' => ['products.id_product_category_tree' => 'id_product_category_tree'],
				'[>]products_categories_four' => ['products.id_product_category_four' => 'id_product_category_four'],
				'[>]providers' => ['id_provider' => 'id_provider']
			], [
				'inventories_inputs.quantify',
				'inventories_inputs.type',
				'inventories_inputs.input_date_time',
				'products.name(product)',
				'products.unity',
				'products_categories_one.name(category_one)',
				'products_categories_two.name(category_two)',
				'products_categories_tree.name(category_tree)',
				'products_categories_four.name(category_four)',
				'providers.name(provider)'
			], [
				'AND' => $and_input
			]);

			foreach ($inputs as $value)
			{
				if ($value['unity'] == '1')
					$value['unity'] = 'Kilogramos';
				else if ($value['unity'] == '2')
					$value['unity'] = 'Gramos';
				else if ($value['unity'] == '3')
					$value['unity'] = 'Mililitros';
				else if ($value['unity'] == '4')
					$value['unity'] = 'Litros';
				else if ($value['unity'] == '5')
					$value['unity'] = 'Piezas';

				if ($value['type'] == '1')
					$value['type'] = '<span class="active">Compra</span>';
				else if ($value['type'] == '2')
					$value['type'] = '<span class="expired">Transferencia</span>';
				else if ($value['type'] == '3')
					$value['type'] = '<span class="expired">Dev. venta</span>';
				else if ($value['type'] == '4')
					$value['type'] = '<span class="expired">Dev. préstamo</span>';

				array_push($products, [
					'product' => $value['product'] . ' ' . $value['category_one'] . ' ' . $value['category_two'] . ' ' . $value['category_tree'] . ' ' . $value['category_four'],
					'quantify' => $value['quantify'] . ' ' . $value['unity'],
					'date' => $value['input_date_time'],
					'type' => $value['type'],
					'provider' => (!empty($value['provider']) ? $value['provider'] : ''),
					'movement' => '<span class="active">Entrada</span>'
				]);
			}
		}

		if ($data['type'] == 'historical' OR $data['type'] == 'outputs')
		{
			$and_output = [
				'inventories_outputs.output_date_time[<>]' => [$data['date_start'],$data['date_end']],
				'inventories_outputs.id_inventory' => $data['inventory'],
			];

			if (!empty($data['movement']) AND $data['type'] == 'outputs')
				$and_output['inventories_outputs.type'] = $data['movement'];

			$outputs = $this->database->select('inventories_outputs', [
				'[>]products' => ['id_product' => 'id_product'],
				'[>]products_categories_one' => ['products.id_product_category_one' => 'id_product_category_one'],
				'[>]products_categories_two' => ['products.id_product_category_two' => 'id_product_category_two'],
				'[>]products_categories_tree' => ['products.id_product_category_tree' => 'id_product_category_tree'],
				'[>]products_categories_four' => ['products.id_product_category_four' => 'id_product_category_four'],
			], [
				'inventories_outputs.quantity',
				'inventories_outputs.type',
				'inventories_outputs.output_date_time',
				'products.name(product)',
				'products.unity',
				'products_categories_one.name(category_one)',
				'products_categories_two.name(category_two)',
				'products_categories_tree.name(category_tree)',
				'products_categories_four.name(category_four)',
			], [
				'AND' => $and_output
			]);

			foreach ($outputs as $value)
			{
				if ($value['unity'] == '1')
					$value['unity'] = 'Kilogramos';
				else if ($value['unity'] == '2')
					$value['unity'] = 'Gramos';
				else if ($value['unity'] == '3')
					$value['unity'] = 'Mililitros';
				else if ($value['unity'] == '4')
					$value['unity'] = 'Litros';
				else if ($value['unity'] == '5')
					$value['unity'] = 'Piezas';

				if ($value['type'] == '1')
					$value['type'] = '<span class="expired">Transferencia</span>';
				else if ($value['type'] == '2')
					$value['type'] = '<span class="missing">Pérdida</span>';
				else if ($value['type'] == '3')
					$value['type'] = '<span class="same">Dev. proveedor</span>';
				else if ($value['type'] == '4')
					$value['type'] = '<span class="active">Venta</span>';
				else if ($value['type'] == '5')
					$value['type'] = '<span class="expired">Cambio de venta</span>';
				else if ($value['type'] == '6')
					$value['type'] = '<span class="expired">Prestamo</span>';

				array_push($products, [
					'product' => $value['product'] . ' ' . $value['category_one'] . ' ' . $value['category_two'] . ' ' . $value['category_tree'] . ' ' . $value['category_four'],
					'quantify' => $value['quantity'] . ' ' . $value['unity'],
					'date' => $value['output_date_time'],
					'type' => $value['type'],
					'provider' => '',
					'movement' => '<span class="expired">Salida</span>'
				]);
			}
		}

		if (!empty($products))
		{
			foreach ($products as $key => $value)
				$aux[$key] = $value['date'];

			array_multisort($aux, SORT_DESC, $products);
		}

		return $products;
	}

	public function getExistence($data)
	{
		$products = [];

		$and_input = [
			'inventories_inputs.id_inventory' => $data['inventory'],
		];

		if (!empty($data['category_one']))
			$and_input['products.id_product_category_one'] = $data['category_one'];

		if (!empty($data['category_two']))
			$and_input['products.id_product_category_two'] = $data['category_two'];

		if (!empty($data['category_tree']))
			$and_input['products.id_product_category_tree'] = $data['category_tree'];

		if (!empty($data['category_four']))
			$and_input['products.id_product_category_four'] = $data['category_four'];

		if ($data['search'] == 'total')
			$and_input['inventories_inputs.input_date_time[<=]'] = $data['date_end'];
		else if ($data['search'] == 'dates_range')
			$and_input['inventories_inputs.input_date_time[<>]'] = [$data['date_start'],$data['date_end']];

		$inputs = $this->database->select('inventories_inputs', [
			'[>]products' => ['id_product' => 'id_product'],
			'[>]products_categories_one' => ['products.id_product_category_one' => 'id_product_category_one'],
			'[>]products_categories_two' => ['products.id_product_category_two' => 'id_product_category_two'],
			'[>]products_categories_tree' => ['products.id_product_category_tree' => 'id_product_category_tree'],
			'[>]products_categories_four' => ['products.id_product_category_four' => 'id_product_category_four']
		], [
			'inventories_inputs.quantify',
			'inventories_inputs.id_product',
			'products.name(product)',
			'products.unity',
			'products.flirt',
			'products_categories_one.name(category_one)',
			'products_categories_two.name(category_two)',
			'products_categories_tree.name(category_tree)',
			'products_categories_four.name(category_four)'
		], [
			'AND' => $and_input
		]);

		foreach ($inputs as $key => $value)
		{
			$break = false;

			foreach ($products as $subkey => $subvalue)
			{
				if ($value['id_product'] == $subvalue['id_product'])
				{
					$products[$subkey]['inputs'] = $products[$subkey]['inputs'] + $value['quantify'];
					$break = true;
				}
			}

			if ($value['flirt'] == true)
				$break = true;

			if ($break == false)
			{
				if ($value['unity'] == 1)
					$value['unity'] = 'Kilogramos';
				else if ($value['unity'] == 2)
					$value['unity'] = 'Gramos';
				else if ($value['unity'] == 3)
					$value['unity'] = 'Mililitros';
				else if ($value['unity'] == 4)
					$value['unity'] = 'Litros';
				else if ($value['unity'] == 5)
					$value['unity'] = 'Piezas';

				$flirts = $this->database->select('products_flirts', [
					'stock_actual',
					'values'
				], [
					'id_product_2' => $value['id_product']
				]);

				$value['flirt'] = [];

				if (!empty($flirts))
				{
					$value['flirt']['status'] = true;
					$value['flirt']['values'] = [];

					foreach ($flirts as $subvalue)
					{
						array_push($value['flirt']['values'], [
							$subvalue['stock_actual'],
							json_decode($subvalue['values'], true)['first'],
							json_decode($subvalue['values'], true)['second']
						]);
					}
				}
				else
					$value['flirt']['status'] = false;

				array_push($products, [
					'id_product' => $value['id_product'],
					'product' => $value['product'] . (!empty($value['category_one']) ? ' - ' . $value['category_one'] : '') . (!empty($value['category_two']) ? ' - ' . $value['category_two'] : '') . (!empty($value['category_tree']) ? ' - ' . $value['category_tree'] : '') . (!empty($value['category_four']) ? ' - ' . $value['category_four'] : ''),
					'unity' => $value['unity'],
					'inputs' => $value['quantify'],
					'flirt' => $value['flirt']
				]);
			}
		}

		foreach ($products as $key => $value)
		{
			$products[$key]['inputs'] = $value['inputs'] . ' ' . $value['unity'];

			$and_output = [
				'id_product' => $value['id_product'],
				'id_inventory' => $data['inventory'],
			];

			if ($data['search'] == 'total')
				$and_output['output_date_time[<=]'] = $data['date_end'];
			else if ($data['search'] == 'dates_range')
				$and_output['output_date_time[<>]'] = [$data['date_start'],$data['date_end']];

			$outputs = $this->database->select('inventories_outputs', [
				'quantity'
			], [
				'AND' => $and_output
			]);

			$products[$key]['outputs'] = array_sum(array_map('current', $outputs)) . ' ' . $value['unity'];
			$products[$key]['flirts'] = 0;
			$products[$key]['existence'] = $value['inputs'] - $products[$key]['outputs'];

			if ($value['flirt']['status'] == true)
			{
				foreach ($value['flirt']['values'] as $subvalue)
					$products[$key]['flirts'] = $products[$key]['flirts'] + (($subvalue[0] * $subvalue[1]) / $subvalue[2]);

				$products[$key]['flirts'] = $products[$key]['flirts'] . ' ' . $value['unity'];
				$products[$key]['existence'] = $products[$key]['existence'] - $products[$key]['flirts'];
			}
			else
				$products[$key]['flirts'] = '';

			$products[$key]['existence'] = $products[$key]['existence'] . ' ' . $value['unity'];

			$stocks = $this->database->select('inventories_stocks', [
				'min',
				'max'
			], [
				'AND' => [
					'id_product' => $value['id_product'],
					'id_inventory' => $data['inventory'],
				]
			]);

			$products[$key]['min'] = !empty($stocks) ? $stocks[0]['min'] . ' ' . $value['unity'] : '';
			$products[$key]['max'] = !empty($stocks) ? $stocks[0]['max'] . ' ' . $value['unity'] : '';

			if (!empty($stocks[0]['max']))
			{
				if ($stocks[0]['max'] < $products[$key]['existence'])
					$products[$key]['status'] = '<span class="missing">Alto</span>';
			}
			else if (!empty($stocks[0]['min']))
			{
				if ($stocks[0]['min'] >= $products[$key]['existence'] AND $stocks[0]['min'] <= ($products[$key]['existence'] + 10))
					$products[$key]['status'] = '<span class="same">Bajo</span>';
				else if ($stocks[0]['min'] >= ($products[$key]['existence'] + 10))
					$products[$key]['status'] = '<span class="stable">Normal</span>';
				else if ($stocks[0]['min'] < $products[$key]['existence'])
					$products[$key]['status'] = '<span class="missing">Faltante</span>';
			}
			else
				$products[$key]['status'] = '';

			unset($products[$key]['id_product']);
			unset($products[$key]['unity']);
		}

		return $products;
	}

	public function getSales($date1, $date2, $branch, $seller = null)
	{
		if (!empty($seller) AND $seller == 'all')
		{
			$where = [
				'sales.date_time[<>]' => [$date1,$date2],
				'sales.id_branch_office' => $branch,
			];
		}
		else
		{
			$where = [
				'sales.date_time[<>]' => [$date1,$date2],
				'sales.id_branch_office' => $branch,
				'sales.id_user' => $seller
			];
		}

		$query = $this->database->select('sales', [
			'[>]users' => ['id_user' => 'id_user'],
			'[>]clients' => ['id_client' => 'id_client'],
		], [
			'sales.folio',
			'sales.totals',
			'sales.payment',
			'sales.sales',
			'sales.date_time',
			'sales.status',
			'users.name(seller)',
			'clients.name(client)',
		], [
			'AND' => $where
		]);

		$sales = [];
		$total = 0;

		foreach ($query as $value)
		{
			if ($value['payment'] == 'cash')
				$value['payment'] = 'Efectivo';
			else if ($value['payment'] == 'card')
				$value['payment'] = 'Tarjeta de crédito/débito';
			else if ($value['payment'] == 'deferred')
				$value['payment'] = 'Pagos diferidos';

			$value['totals'] = json_decode($value['totals'], true);

			$sales_items = '';
			$sales_items_n = 1;
			$value['sales'] = json_decode($value['sales'], true);

			foreach ($value['sales'] as $subvalue)
			{
				$sales_items .= $sales_items_n . '. ' . 	$subvalue['object']['name'] . '<br>';
				$sales_items_n = $sales_items_n + 1;
			}

			array_push($sales, [
				'folio' => $value['folio'],
				'total' => '$ ' . $value['totals']['total'] . ' ' . $value['totals']['mainCoin'],
				'payment' => $value['payment'],
				'date' => $value['date_time'],
				'seller' => $value['seller'],
				'sales' => $sales_items,
				'status' => (($value['status'] == true) ? '<span class="active">Activa</span>' : '<span class="deactive">Cancelada</span>')
			]);

			$total = $total + $value['totals']['total'];
		}

		$total = number_format($total, 2, '.', ',');

		if (!empty($sales))
		{
			foreach ($sales as $key => $value)
				$aux[$key] = $value['date'];

			array_multisort($aux, SORT_DESC, $sales);
		}

		return [$sales,$total];
	}

	public function getAllCategories($number)
	{
		$query = $this->database->select('products_categories_' . $number, '*', ['id_subscription' => Session::getValue('id_subscription'), 'ORDER' => 'name ASC']);
		return $query;
	}

	public function getBills($inventory)
	{
		$query = $this->database->select('inventories_inputs', [
			'bill'
		], [
			'id_inventory' => $inventory
		]);

		if (!empty($query))
		{
			$query = array_map('current', $query);
			$query = array_unique($query);
	        $query = array_values($query);
		}

		return $query;
	}

	public function getBranchs()
	{
		$query = $this->database->select('branch_offices', [
			'id_branch_office',
			'name'
		], [
			'id_subscription' => Session::getValue('id_subscription')
		]);

		return $query;
	}

	public function getSellers($id)
	{
		$query1 = $this->database->select('users', [
			'id_user',
			'name',
		], [
			'AND' => [
				'level' => ['10'],
				'status' => true,
				'id_subscription' => Session::getValue('id_subscription'),
			]
		]);

		$query2 = $this->database->select('users', [
			'id_user',
			'name',
		], [
			'AND' => [
				'level' => ['7'],
				'status' => true,
				'id_branch_office' => $id,
				'id_subscription' => Session::getValue('id_subscription'),
			]
		]);

		return array_merge($query1, $query2);
	}
}
