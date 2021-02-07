<?php

defined('_EXEC') or die;

class Inventories_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create_inventory_input($data)
	{
		$go = false;

		if ($data['bill_type'] == 'bill' OR $data['bill_type'] == 'ticket')
		{
			$bill = $this->database->select('bills', [
				'id'
			], [
				'AND' => [
					'account' => Session::get_value('vkye_account')['id'],
					'token' => $data['bill_token']
				]
			]);

			if (!empty($bill))
			{
				$bill = $bill[0]['id'];
				$go = true;
			}
			else
			{
				$bill = $this->database->insert('bills', [
					'account' => Session::get_value('vkye_account')['id'],
					'type' => $data['bill_type'],
					'token' => $data['bill_token'],
					'payment' => json_encode([
						'way' => $data['bill_payment_way'],
						'method' => ''
					]),
					'iva' => !empty($data['bill_iva']) ? $data['bill_iva'] : 0,
					'discount' => json_encode([
						'type' => !empty($data['bill_discount_type']) ? $data['bill_discount_type'] : '',
						'amount' => !empty($data['bill_discount_amount']) ? $data['bill_discount_amount'] : '0'
					]),
					'created_date' => Dates::current_date(),
					'created_hour' => Dates::current_hour(),
					'created_user' => Session::get_value('vkye_user')['id']
				]);

				if (!empty($bill))
				{
					$bill = $this->database->id();
					$go = true;
				}
			}
		}
		else
			$go = true;

		if ($go == true)
		{
			foreach (System::temporal('get', 'inventories', 'inputs') as $value)
			{
				foreach ($value['list'] as $subvalue)
				{
					$this->database->insert('inventories', [
						'account' => Session::get_value('vkye_account')['id'],
						'branch' => System::temporal('get', 'inventories', 'branch')['id'],
						'movement' => 'input',
						'date' => $data['date'],
						'hour' => $data['hour'],
						'type' => $data['type'],
						'product' => $value['product']['id'],
						'quantity' => (string) $subvalue['quantity'][1],
						'cost' => (string) $subvalue['cost'],
						'total' => (string) $subvalue['total'],
						'location' => !empty($subvalue['location']) ? $subvalue['location']['id'] : null,
						'categories' => json_encode((!empty($subvalue['categories']) ? array_map('current', $subvalue['categories']) : [])),
						'origin' => json_encode([
							'type' => (!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? 'cnt' : 'unt',
							'quantity' => (!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $subvalue['quantity'][0] : '',
							'content' => (!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $subvalue['content'][1] : ''
						]),
						'provider' => !empty($data['provider']) ? $data['provider'] : null,
						'bill' => ($data['bill_type'] == 'bill' OR $data['bill_type'] == 'ticket') ? $bill : null,
						'transfer' => json_encode([
							'branch' => '',
							'parent' => ''
						]),
						'created_date' => Dates::current_date(),
						'created_hour' => Dates::current_hour(),
						'created_user' => Session::get_value('vkye_user')['id']
					]);
				}
			}
		}

		return $go;
	}

	public function create_inventory_output($data)
	{
		foreach (System::temporal('get', 'inventories', 'outputs') as $value)
		{
			$outputs = [];

			if ($value['product']['inventory'] == true)
			{
				foreach ($value['list'] as $subkey => $subvalue)
				{
					array_push($outputs, [
					   'product' => $value['product']['id'],
					   'quantity' => $subvalue['quantity'][1],
					   'origin' => [
						   'type' => (!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? 'cnt' : 'unt',
						   'quantity' => (!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $subvalue['quantity'][0] : '',
						   'content' => (!empty($subvalue['content']) AND $subvalue['content'][0] == 'cnt') ? $subvalue['content'][1] : ''
					   ]
				   ]);
				}
			}
			else
			{
				if (!empty($value['product']['formula']) AND !empty($value['product']['formula']['parent']))
				{
					array_push($outputs, [
						'product' => $value['product']['formula']['parent']['id'],
						'quantity' => $value['list'][0]['quantity'][1],
						'origin' => [
							'type' => 'chd',
							'quantity' => $value['list'][0]['quantity'][0],
							'child' => $value['product']['id']
						]
					]);

					foreach ($value['list'][0]['supplies'] as $subkey => $subvalue)
					{
						array_push($outputs, [
							'product' => $subkey,
							'quantity' => $subvalue['quantity'],
							'origin' => [
								'type' => 'prt',
								'quantity' => $value['list'][0]['quantity'][1],
								'parent' => $value['product']['formula']['parent']['id']
							]
						]);
					}
				}
			}

			foreach ($value['supplies'] as $subkey => $subvalue)
			{
				array_push($outputs, [
					'product' => $subkey,
					'quantity' => $subvalue['quantity'],
					'origin' => [
						'type' => 'prt',
						'quantity' => $value['list'][0]['quantity'][0],
						'parent' => $value['product']['id']
					]
				]);
			}

			foreach ($outputs as $subvalue)
			{
				$this->database->insert('inventories', [
					'account' => Session::get_value('vkye_account')['id'],
					'branch' => System::temporal('get', 'inventories', 'branch')['id'],
					'movement' => 'output',
					'date' => $data['date'],
					'hour' => $data['hour'],
					'type' => $data['type'],
					'product' => $subvalue['product'],
					'quantity' => (string) $subvalue['quantity'],
					'cost' => null,
					'total' => null,
					'location' => null,
					'categories' => null,
					'origin' => json_encode($subvalue['origin']),
					'provider' => null,
					'bill' => null,
					'transfer' => json_encode([
						'branch' => '',
						'parent' => ''
					]),
					'created_date' => Dates::current_date(),
					'created_hour' => Dates::current_hour(),
					'created_user' => Session::get_value('vkye_user')['id']
				]);
			}
		}

		return true;
	}

	public function create_inventory_transfer($data)
	{
		$query = $this->database->insert('inventories_transfers', [
			'account' => Session::get_value('vkye_account')['id'],
			'output_branch' => System::temporal('get', 'inventories', 'branch')['id'],
			'input_branch' => $data['branch'],
			'products' => json_encode(System::temporal('get', 'inventories', 'transfers')),
			'status' => 'pending',
			'created_date' => Dates::current_date(),
			'created_hour' => Dates::current_hour(),
			'created_user' => Session::get_value('vkye_user')['id'],
			'success_date' => null,
			'success_hour' => null,
			'success_user' => null,
			'rejected_date' => null,
			'rejected_hour' => null,
			'rejected_user' => null,
			'cancel_date' => null,
			'cancel_hour' => null,
			'cancel_user' => null
		]);

		return $query;
	}

	public function read_inventories_movements()
	{
		$where = [];

		if (System::temporal('get', 'inventories', 'period') == 'current')
		{
			$where['AND'] = [
				'account' => Session::get_value('vkye_account')['id'],
				'branch' => System::temporal('get', 'inventories', 'branch')['id'],
				'last' => true
			];
		}
		else
			$where['id'] = System::temporal('get', 'inventories', 'period');

		$period = $this->database->select('inventories_periods', [
			'started_date',
			'end_date'
		], $where);

		$and = [
			'inventories.account' => Session::get_value('vkye_account')['id'],
			'inventories.branch' => System::temporal('get', 'inventories', 'branch')['id']
		];

		if (!empty($period))
		{
			if (System::temporal('get', 'inventories', 'period') == 'current')
				$and['inventories.date[>]'] = $period[0]['end_date'];
			else
				$and['inventories.date[<>]'] = [$period[0]['started_date'], $period[0]['end_date']];
		}

		$query = System::decode_json_to_array($this->database->select('inventories', [
			'[>]inventories_types' => [
				'type' => 'id'
			],
			'[>]products' => [
				'product' => 'id'
			],
			'[>]products_unities' => [
				'products.unity' => 'id'
			]
		], [
			'inventories.id',
			'inventories.movement',
			'inventories.date',
			'inventories.hour',
			'inventories_types.id(type_id)',
			'inventories_types.name(type_name)',
			'inventories_types.system(type_system)',
			'products.type(product_type)',
			'products.name(product_name)',
			'products.token(product_token)',
			'products_unities.name(product_unity_name)',
			'products_unities.system(product_unity_system)',
			'inventories.quantity',
			'inventories.origin'
		], [
			'AND' => $and,
			'ORDER' => [
				'inventories.date' => 'DESC',
				'inventories.hour' => 'DESC'
			]
		]));

		foreach ($query as $key => $value)
		{
			if ($value['origin']['type'] == 'cnt')
			{
				$value['origin']['content'] = System::decode_json_to_array($this->database->select('products_contents', [
					'[>]products_unities' => [
						'unity' => 'id'
					]
				], [
					'products_contents.amount',
					'products_unities.name(unity_name)',
					'products_unities.system(unity_system)'
				], [
					'products_contents.id' => $value['origin']['content']
				]));

				if (!empty($value['origin']['content']))
					$query[$key]['origin']['content'] = $value['origin']['content'][0];
				else
					$query[$key]['origin'] = [];
			}
			else if ($value['origin']['type'] == 'chd')
			{
				$value['origin']['child'] = System::decode_json_to_array($this->database->select('products', [
					'name'
				], [
					'id' => $value['origin']['child']
				]));

				if (!empty($value['origin']['child']))
					$query[$key]['origin']['child'] = $value['origin']['child'][0];
				else
					$query[$key]['origin'] = [];
			}
			else if ($value['origin']['type'] == 'prt')
			{
				$value['origin']['parent'] = System::decode_json_to_array($this->database->select('products', [
					'[>]products_unities' => [
						'unity' => 'id'
					]
				], [
					'products.name',
					'products.inventory',
					'products_unities.name(unity_name)',
					'products_unities.system(unity_system)'
				], [
					'products.id' => $value['origin']['parent']
				]));

				if (!empty($value['origin']['parent']))
					$query[$key]['origin']['parent'] = $value['origin']['parent'][0];
				else
					$query[$key]['origin'] = [];
			}

			$query[$key]['closed'] = $this->check_exist_inventory_period($value['date']);
		}

		return $query;
	}

	public function read_inventories_transfers()
	{
		$fields = [
			'inventories_transfers.id',
			'branches.name(branch)',
			'inventories_transfers.products',
			'inventories_transfers.status',
			'inventories_transfers.created_date'
		];

		$query1 = System::decode_json_to_array($this->database->select('inventories_transfers', [
			'[>]branches' => [
				'input_branch' => 'id'
			]
		], $fields, [
			'AND' => [
				'inventories_transfers.output_branch' => System::temporal('get', 'inventories', 'branch')['id'],
				'status' => 'pending'
			]
		]));

		$query2 = System::decode_json_to_array($this->database->select('inventories_transfers', [
			'[>]branches' => [
				'output_branch' => 'id'
			]
		], $fields, [
			'AND' => [
				'inventories_transfers.input_branch' => System::temporal('get', 'inventories', 'branch')['id'],
				'status' => 'pending'
			]
		]));

		return [
			'outputs' => $query1,
			'inputs' => $query2
		];
	}

	// public function read_inventory_movement($id)
	// {
	// 	$query = System::decode_json_to_array($this->database->select('inventories', [
	// 		'[>]inventories_types' => [
	// 			'type' => 'id'
	// 		],
	// 		'[>]products' => [
	// 			'product' => 'id'
	// 		],
	// 		'[>]products_unities' => [
	// 			'products.unity' => 'id'
	// 		],
	// 		'[>]inventories_locations' => [
	// 			'location' => 'id'
	// 		],
	// 		'[>]providers' => [
	// 			'provider' => 'id'
	// 		],
	// 		'[>]bills' => [
	// 			'bill' => 'id'
	// 		],
	// 		'[>]branches' => [
	// 			'transfer_branch' => 'id'
	// 		],
	// 		'[>]users' => [
	// 			'created_user' => 'id'
	// 		]
	// 	], [
	// 		'inventories.movement',
	// 		'inventories.date',
	// 		'inventories.hour',
	// 		'inventories_types.name(type_name)',
	// 		'inventories_types.system(type_system)',
	// 		'products.type(product_type)',
	// 		'products.avatar(product_avatar)',
	// 		'products.name(product_name)',
	// 		'products.token(product_token)',
	// 		'products_unities.name(product_unity_name)',
	// 		'products_unities.system(product_unity_system)',
	// 		'inventories.quantity',
	// 		'inventories.weight',
	// 		'inventories.cost',
	// 		'inventories_locations.name(location)',
	// 		'inventories.categories',
	// 		'providers.name(provider)',
	// 		'bills.type(bill_type)',
	// 		'bills.token(bill_token)',
	// 		'bills.payment(bill_payment)',
	// 		'branches.name(transfer_branch)',
	// 		'inventories.created_date',
	// 		'inventories.created_hour',
	// 		'users.avatar(user_avatar)',
	// 		'users.firstname(user_firstname)',
	// 		'users.lastname(user_lastname)'
	// 	], [
	// 		'inventories.id' => $id
	// 	]));
	//
	// 	if (!empty($query))
	// 	{
	// 		if (System::temporal('get', 'inventories', 'period') != 'current')
	// 		{
	// 			$periods = System::decode_json_to_array($this->database->select('inventories_periods', [
	// 				'started_date',
	// 				'end_date'
	// 			], [
	// 				'account' => Session::get_value('vkye_account')['id']
	// 			]));
	//
	// 			foreach ($periods as $value)
	// 			{
	// 				if ($query[0]['date'] >= $value['started_date'] AND $query[0]['date'] <= $value['end_date'])
	// 					$query[0]['period'] = $value;
	// 			}
	// 		}
	//
	// 		if ($query[0]['movement'] == 'input')
	// 		{
	// 			foreach ($query[0]['categories'] as $key => $value)
	// 			{
	// 				$query[0]['categories'][$key] = $this->database->select('inventories_categories', [
	// 					'name'
	// 				], [
	// 					'id' => $value
	// 				]);
	//
	// 				if (!empty($query[0]['categories'][$key]))
	// 					$query[0]['categories'][$key] = $query[0]['categories'][$key][0]['name'];
	// 				else
	// 					unset($query[0]['categories'][$key]);
	// 			}
	// 		}
	//
	// 		return $query[0];
	// 	}
	// 	else
	// 		return null;
	// }

	public function read_branches()
	{
		$query = $this->database->select('branches', [
			'id',
			'name'
		], [
			'AND' => [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			],
			'ORDER' => [
				'name' => 'ASC'
			]
		]);

		return $query;
	}

	public function read_branch($id)
	{
		$query = $this->database->select('branches', [
			'id',
			'name'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function read_products($movement)
	{
		$AND = [
			'account' => Session::get_value('vkye_account')['id'],
			'type' => ['sale_menu','supply','work_material'],
			'blocked' => false
		];

		if ($movement == 'input')
			$AND['inventory'] = true;
		else if ($movement == 'output')
		{
			$AND['OR'] = [
				'supplies[!]' => [null,'[]'],
				'formula[!]' => [null,'{"code":"","parent":"","quantity":""}'],
				'inventory' => true
			];
		}

		$query = $this->database->select('products', [
			'id',
			'type',
			'avatar',
			'name',
			'token',
			'inventory'
		], [
			'AND' => $AND,
			'ORDER' => [
				'type' => 'ASC',
				'name' => 'ASC'
			]
		]);

		return $query;
	}

	public function read_product($id, $type = 'id')
	{
		$where = [];

		if ($type == 'id')
			$where['products.id'] = $id;
		else if ($type == 'token')
			$where['products.token'] = $id;

		$query = System::decode_json_to_array($this->database->select('products', [
			'[>]products_unities' => [
				'unity' => 'id'
			]
		], [
			'products.id',
			'products.type',
			'products.name',
			'products.inventory',
			'products_unities.code(unity_code)',
			'products_unities.name(unity_name)',
			'products_unities.system(unity_system)',
			'products.formula',
			'products.contents',
			'products.supplies'
		], $where));

		if (!empty($query))
		{
			if (!empty($query[0]['contents']))
			{
				foreach ($query[0]['contents'] as $key => $value)
				{
					$content = [];
					$go = true;

					$value['content'] = System::decode_json_to_array($this->database->select('products_contents', [
						'[>]products_unities' => [
							'unity' => 'id'
						]
					], [
						'products_contents.amount',
						'products_unities.code(unity_code)',
						'products_unities.name(unity_name)',
						'products_unities.system(unity_system)'
					], [
						'products_contents.id' => $key
					]));

					if (!empty($value['content']))
					{
						$content['content'] = $value['content'][0];
						$content['weight'] = [];

						if (!empty($value['weight']) AND !empty($value['unity']))
						{
							$value['unity'] = System::decode_json_to_array($this->database->select('products_unities', [
								'code',
								'name',
								'system'
							], [
								'id' => $value['unity']
							]));

							if (!empty($value['unity']))
							{
								$content['weight']['amount'] = $value['weight'];
								$content['weight']['unity_code'] = $value['unity'][0]['code'];
								$content['weight']['unity_name'] = $value['unity'][0]['name'];
								$content['weight']['unity_system'] = $value['unity'][0]['system'];
							}
							else
								$go = false;
						}
					}
					else
						$go = false;

					if ($go == true)
						$query[0]['contents'][$key] = $content;
					else
						unset($query[0]['contents'][$key]);
				}
			}

			if (!empty($query[0]['supplies']))
			{
				foreach ($query[0]['supplies'] as $key => $value)
				{
					$product = [];
					$go = true;

					$value['product'] = System::decode_json_to_array($this->database->select('products', [
						'[>]products_unities' => [
							'unity' => 'id'
						]
					], [
						'products.name',
						'products_unities.code(unity_code)',
						'products_unities.name(unity_name)',
						'products_unities.system(unity_system)'
					], [
						'products.id' => $key
					]));

					if (!empty($value['product']))
					{
						$product['product'] = $value['product'][0];
						$product['supply'] = [];

						$value['unity'] = System::decode_json_to_array($this->database->select('products_unities', [
							'code',
							'name',
							'system'
						], [
							'id' => $value['unity']
						]));

						if (!empty($value['unity']))
						{
							$product['supply']['quantity'] = $value['quantity'];
							$product['supply']['unity_code'] = $value['unity'][0]['code'];
							$product['supply']['unity_name'] = $value['unity'][0]['name'];
							$product['supply']['unity_system'] = $value['unity'][0]['system'];
						}
						else
							$go = false;
					}
					else
						$go = false;

					if ($go == true)
						$query[0]['supplies'][$key] = $product;
					else
						unset($query[0]['supplies'][$key]);
				}
			}

			if (!empty($query[0]['formula']) AND !empty($query[0]['formula']['parent']))
				$query[0]['formula']['parent'] = $this->read_product($query[0]['formula']['parent']);

			return $query[0];
		}
		else
			return null;
	}

	public function read_products_unities()
	{
		$fields = [
			'id',
			'code',
			'name',
			'system'
		];

		$AND1 = [
			'system' => true,
			'blocked' => false
		];

		$AND2 = [
			'account' => Session::get_value('vkye_account')['id'],
			'blocked' => false
		];

		$query1 = System::decode_json_to_array($this->database->select('products_unities', $fields, [
			'AND' => $AND1,
			'ORDER' => [
				'order' => 'ASC'
			]
		]));

		$query2 = System::decode_json_to_array($this->database->select('products_unities', $fields, [
			'AND' => $AND2,
			'ORDER' => [
				'name' => 'ASC'
			]
		]));

		return array_merge($query1, $query2);
	}

	public function read_product_unity($id, $field = null)
	{
		$query = $this->database->select('products_unities', [
			'id',
			'code'
		], [
			'id' => $id
		]);

		return !empty($query) ? (!empty($field) ? $query[0][$field] : $query[0]) : null;
	}

	public function read_providers()
	{
		$query = $this->database->select('providers', [
			'id',
			'name'
		], [
			'AND' => [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			],
			'ORDER' => [
				'name' => 'ASC'
			]
		]);

		return $query;
	}

	public function delete_inventory_movement($id)
	{
		$query = $this->database->delete('inventories', [
			'id' => $id
		]);

		return $query;
	}

	public function create_inventory_audit($data)
	{
		$existences = $this->read_inventories_existences('audit');
		$physical = System::temporal('get', 'inventories', 'audit')['physical'];
		$products = System::temporal('get', 'inventories', 'audit')['products'];

		foreach ($existences as $value)
		{
			if (array_key_exists($value['id'], $products))
			{
				$products[$value['id']]['theoretical'] = $value['existence']['theoretical'];
				$products[$value['id']]['physical'] = $value['existence']['physical'];
				$products[$value['id']]['variation'] = $value['variation'];
			}
		}

		$query1 = $this->database->insert('inventories_audits', [
			'account' => Session::get_value('vkye_account')['id'],
			'branch' => System::temporal('get', 'inventories', 'branch')['id'],
			'started_date' => $data['started_date'],
			'end_date' => $data['end_date'],
			'physical' => json_encode($physical),
			'products' => json_encode($products),
			'adjustments' => json_encode([]),
			'comment' => !empty($data['comment']) ? $data['comment'] : null,
			'saved' => $data['saved'],
			'created_date' => Dates::current_date(),
			'created_hour' => Dates::current_hour(),
			'created_user' => Session::get_value('vkye_user')['id']
		]);

		if (!empty($query1) AND $data['saved'] == 'adjust')
		{
			$audit = $this->database->id();
			$adjustements = [];

			foreach ($products as $key => $value)
			{
				if ($value['variation'] > 0)
				{
					$query2 = $this->database->insert('inventories', [
						'account' => Session::get_value('vkye_account')['id'],
						'branch' => System::temporal('get', 'inventories', 'branch')['id'],
						'movement' => ($value['theoretical'] < $value['physical']) ? 'input' : 'output',
						'date' => $data['end_date'],
						'hour' => Dates::current_hour(),
						'type' => 7,
						'product' => $key,
						'quantity' => (string) $value['variation'],
						'cost' => ($value['theoretical'] < $value['physical']) ? '0' : null,
						'total' => ($value['theoretical'] < $value['physical']) ? '0' : null,
						'location' => null,
						'categories' => ($value['theoretical'] < $value['physical']) ? json_encode([]) : null,
						'origin' => json_encode([
							'type' => 'unt',
							'quantity' => '',
							'content' => ''
						]),
						'provider' => null,
						'bill' => null,
						'transfer' => json_encode([
							'branch' => '',
							'parent' => ''
						]),
						'created_date' => Dates::current_date(),
						'created_hour' => Dates::current_hour(),
						'created_user' => Session::get_value('vkye_user')['id']
					]);

					if (!empty($query2))
						array_push($adjustements, $this->database->id());
				}
			}

			if (!empty($adjustements))
			{
				$this->database->update('inventories_audits', [
					'adjustments' => json_encode($adjustements)
				], [
					'id' => $audit
				]);
			}
		}

		return $query1;
	}

	public function read_inventories_audits($option = null)
	{
		$where = [];

		if (System::temporal('get', 'inventories', 'period') == 'current' OR $option == 'current')
		{
			$where['AND'] = [
				'account' => Session::get_value('vkye_account')['id'],
				'branch' => System::temporal('get', 'inventories', 'branch')['id'],
				'last' => true
			];
		}
		else
			$where['id'] = System::temporal('get', 'inventories', 'period');

		$period = $this->database->select('inventories_periods', [
			'started_date',
			'end_date'
		], $where);

		$and = [
			'account' => Session::get_value('vkye_account')['id'],
			'branch' => System::temporal('get', 'inventories', 'branch')['id']
		];

		if (!empty($period))
		{
			if (System::temporal('get', 'inventories', 'period') == 'current')
				$and['started_date[>]'] = $period[0]['end_date'];
			else
				$and['started_date[<>]'] = [$period[0]['started_date'], $period[0]['end_date']];
		}

		$query = System::decode_json_to_array($this->database->select('inventories_audits', [
			'id',
			'started_date',
			'end_date',
			'products',
			'comment',
			'saved'
		], [
			'AND' => $and,
			'ORDER' => [
				'started_date' => 'DESC',
				'end_date' => 'DESC'
			]
		]));

		if (!empty($query))
		{
			foreach ($query as $key => $value)
			{
				$query[$key]['success'] = 0;
				$query[$key]['error'] = 0;

				foreach ($value['products'] as $subvalue)
				{
					if ($subvalue['status'] == 'success')
						$query[$key]['success'] += 1;
					else if ($subvalue['status'] == 'error')
						$query[$key]['error'] += 1;
				}

				$query[$key]['last'] = false;
			}

			$query[0]['last'] = true;
		}

		return $query;
	}

	public function read_inventory_audit($id)
	{
		$query = System::decode_json_to_array($this->database->select('inventories_audits', [
			'[>]users' => [
				'created_user' => 'id'
			]
		], [
			'inventories_audits.id',
			'inventories_audits.started_date',
			'inventories_audits.end_date',
			'inventories_audits.physical',
			'inventories_audits.products',
			'inventories_audits.comment',
			'inventories_audits.saved',
			'inventories_audits.created_date',
			'inventories_audits.created_hour',
			'users.avatar(user_avatar)',
			'users.firstname(user_firstname)',
			'users.lastname(user_lastname)'
		], [
			'inventories_audits.id' => $id
		]));

		if (!empty($query))
		{
			$query[0]['success'] = 0;
			$query[0]['error'] = 0;

			foreach ($query[0]['products'] as $value)
			{
				if ($value['status'] == 'success')
					$query[0]['success'] += 1;
				else if ($value['status'] == 'error')
					$query[0]['error'] += 1;
			}

			return $query[0];
		}
		else
			return null;
	}

	public function read_inventories_existences($type, $data = null)
	{
		$where = [];

		if (!empty($data))
		{
			$ids = [];

			foreach ($data as $key => $value)
				array_push($ids, $key);

			$where['products.id'] = $ids;
		}
		else
		{
			$period = System::decode_json_to_array($this->database->select('inventories_periods', [
				'started_date',
				'end_date',
				'products'
			], [
				'account' => Session::get_value('vkye_account')['id'],
				'branch' => System::temporal('get', 'inventories', 'branch')['id'],
				'last' => true
			]));

			$where['AND'] = [
				'products.account' => Session::get_value('vkye_account')['id'],
				'products.inventory' => true
			];
		}

		$products = System::decode_json_to_array($this->database->select('products', [
			'[>]products_unities' => [
				'unity' => 'id'
			]
		], [
			'products.id',
			'products.type',
			'products.name',
			'products.token',
			'products_unities.name(unity_name)',
			'products_unities.system(unity_system)'
		], $where));

		foreach ($products as $key => $value)
		{
			if (!empty($data))
			{
				$products[$key]['existence'] = [
					'theoretical' => $data[$value['id']]['theoretical'],
					'physical' => $data[$value['id']]['physical']
				];

				$products[$key]['variation'] = $data[$value['id']]['variation'];
			}
			else
			{
				$last_inputs = 0;
				$real_inputs = 0;
				$outputs = 0;

				$AND = [
					'product' => $value['id'],
					'branch' => System::temporal('get', 'inventories', 'branch')['id']
				];

				if (!empty($period))
					$AND['date[>]'] = $period[0]['end_date'];

				$inventories = $this->database->select('inventories', [
					'movement',
					'quantity'
				], [
					'AND' => $AND
				]);

				foreach ($inventories as $subvalue)
				{
					if ($subvalue['movement'] == 'input')
						$real_inputs += $subvalue['quantity'];
					else if ($subvalue['movement'] == 'output')
						$outputs += $subvalue['quantity'];
				}

				$theoretical = (($last_inputs + $real_inputs) - $outputs);
				$physical = 0;

				if (!empty(System::temporal('get', 'inventories', $type)['physical']) AND array_key_exists($value['id'], System::temporal('get', 'inventories', $type)['physical']))
				{
					foreach (System::temporal('get', 'inventories', $type)['physical'][$value['id']]['list'] as $subvalue)
						$physical += $subvalue['quantity'][1];
				}

				$products[$key]['existence'] = [
					'theoretical' => $theoretical,
					'physical' => $physical
				];

				$products[$key]['variation'] = abs(($physical - $theoretical));
			}
		}

		return $products;
	}

	public function read_inventory_existence($id)
	{
		$product = System::decode_json_to_array($this->database->select('products', [
			'[>]products_unities' => [
				'unity' => 'id'
			]
		], [
			'products.id',
			'products.type',
			'products.name',
			'products.token',
			'products_unities.name(unity_name)',
			'products_unities.system(unity_system)'
		], [
			'products.id' => $id
		]));

		if (!empty($product[0]))
		{
			$last_inputs = 0;
			$real_inputs = 0;
			$outputs = 0;

			$AND = [
				'product' => $id,
				'branch' => System::temporal('get', 'inventories', 'branch')['id']
			];

			$period = System::decode_json_to_array($this->database->select('inventories_periods', [
				'started_date',
				'end_date',
				'products'
			], [
				'account' => Session::get_value('vkye_account')['id'],
				'branch' => System::temporal('get', 'inventories', 'branch')['id'],
				'last' => true
			]));

			if (!empty($period))
				$AND['date[>]'] = $period[0]['end_date'];

			$inventories = $this->database->select('inventories', [
				'movement',
				'quantity'
			], [
				'AND' => $AND
			]);

			foreach ($inventories as $subvalue)
			{
				if ($subvalue['movement'] == 'input')
					$real_inputs += $subvalue['quantity'];
				else if ($subvalue['movement'] == 'output')
					$outputs += $subvalue['quantity'];
			}

			$theoretical = (($last_inputs + $real_inputs) - $outputs);
			$physical = 0;

			if (!empty(System::temporal('get', 'inventories', 'audit')['physical']) AND array_key_exists($id, System::temporal('get', 'inventories', 'audit')['physical']))
			{
				foreach (System::temporal('get', 'inventories', 'audit')['physical'][$id]['list'] as $subvalue)
					$physical += $subvalue['quantity'][1];
			}

			$product[0]['existence'] = [
				'theoretical' => $theoretical,
				'physical' => $physical
			];

			$product[0]['variation'] = abs(($physical - $theoretical));

			return $product[0];
		}
		else
			return null;
	}

	public function check_exist_inventory_audit($date)
	{
		$where = [
			'AND' => [
				'account' => Session::get_value('vkye_account')['id'],
				'branch' => System::temporal('get', 'inventories', 'branch')['id']
			]
		];

		if ($date == 'draft')
			$where['AND']['saved'] = 'draft';
		else
		{
			$where['AND']['end_date[>=]'] = $date;
			$where['AND']['saved'] = 'adjust';
		}

		$query = $this->database->count('inventories_audits', $where);

		return ($query > 0) ? true : false;
	}

	public function update_inventory_audit($data)
	{
		$existences = $this->read_inventories_existences('audit');
		$physical = System::temporal('get', 'inventories', 'audit')['physical'];
		$products = System::temporal('get', 'inventories', 'audit')['products'];

		foreach ($existences as $value)
		{
			if (array_key_exists($value['id'], $products))
			{
				$products[$value['id']]['theoretical'] = $value['existence']['theoretical'];
				$products[$value['id']]['physical'] = $value['existence']['physical'];
				$products[$value['id']]['variation'] = $value['variation'];
			}
		}

		$query1 = $this->database->update('inventories_audits', [
			'started_date' => $data['started_date'],
			'end_date' => $data['end_date'],
			'physical' => json_encode($physical),
			'products' => json_encode($products),
			'comment' => !empty($data['comment']) ? $data['comment'] : null,
			'saved' => $data['saved']
		], [
			'id' => $data['id']
		]);

		if (!empty($query1) AND $data['saved'] == 'adjust')
		{
			$adjustements = [];

			foreach ($products as $key => $value)
			{
				if ($value['variation'] > 0)
				{
					$query2 = $this->database->insert('inventories', [
						'account' => Session::get_value('vkye_account')['id'],
						'branch' => System::temporal('get', 'inventories', 'branch')['id'],
						'movement' => ($value['theoretical'] < $value['physical']) ? 'input' : 'output',
						'date' => $data['end_date'],
						'hour' => Dates::current_hour(),
						'type' => 7,
						'product' => $key,
						'quantity' => (string) $value['variation'],
						'cost' => ($value['theoretical'] < $value['physical']) ? '0' : null,
						'total' => ($value['theoretical'] < $value['physical']) ? '0' : null,
						'location' => null,
						'categories' => ($value['theoretical'] < $value['physical']) ? json_encode([]) : null,
						'origin' => json_encode([
							'type' => 'unt',
							'quantity' => '',
							'content' => ''
						]),
						'provider' => null,
						'bill' => null,
						'transfer' => json_encode([
							'branch' => '',
							'parent' => ''
						]),
						'created_date' => Dates::current_date(),
						'created_hour' => Dates::current_hour(),
						'created_user' => Session::get_value('vkye_user')['id']
					]);

					if (!empty($query2))
						array_push($adjustements, $this->database->id());
				}
			}

			if (!empty($adjustements))
			{
				$this->database->update('inventories_audits', [
					'adjustments' => json_encode($adjustements)
				], [
					'id' => $data['id']
				]);
			}
		}

		return $query1;
	}

	public function delete_inventory_audit($id)
	{
		$query = null;

		$deleted = System::decode_json_to_array($this->database->select('inventories_audits', [
			'adjustments'
		], [
			'id' => $id
		]));

		if (!empty($deleted))
		{
			$query = $this->database->delete('inventories_audits', [
				'id' => $id
			]);

			if (!empty($query) AND !empty($deleted[0]['adjustments']))
			{
				$this->database->delete('inventories', [
					'id' => $deleted[0]['adjustments']
				]);
			}
		}

		return $query;
	}

	public function create_inventory_period($data)
	{
		$existences = $this->read_inventories_existences('aperiod');
		$physical = System::temporal('get', 'inventories', 'aperiod')['physical'];
		$products = System::temporal('get', 'inventories', 'aperiod')['products'];

		foreach ($existences as $value)
		{
			if (array_key_exists($value['id'], $products))
			{
				$products[$value['id']]['theoretical'] = $value['existence']['theoretical'];
				$products[$value['id']]['physical'] = $value['existence']['physical'];
				$products[$value['id']]['variation'] = $value['variation'];
			}
		}

		$query1 = $this->database->insert('inventories_periods', [
			'account' => Session::get_value('vkye_account')['id'],
			'branch' => System::temporal('get', 'inventories', 'branch')['id'],
			'started_date' => $data['started_date'],
			'end_date' => $data['end_date'],
			'physical' => json_encode($physical),
			'products' => json_encode($products),
			'last' => false,
			'previous' => null,
			'initials' => json_encode([]),
			'saved' => $data['saved'],
			'created_date' => Dates::current_date(),
			'created_hour' => Dates::current_hour(),
			'created_user' => Session::get_value('vkye_user')['id']
		]);

		if (!empty($query1) AND $data['saved'] == 'closed')
		{
			$period = $this->database->id();

			$last = System::decode_json_to_array($this->database->select('inventories_periods', [
				'id'
			], [
				'AND' => [
					'account' => Session::get_value('vkye_account')['id'],
					'branch' => System::temporal('get', 'inventories', 'branch')['id'],
					'last' => true
				]
			]));

			$this->database->update('inventories_periods', [
				'last' => true
			], [
				'id' => $period
			]);

			if (!empty($last))
			{
				$this->database->update('inventories_periods', [
					'previous' => $last[0]['id']
				], [
					'id' => $period
				]);

				$this->database->update('inventories_periods', [
					'last' => false
				], [
					'id' => $last[0]['id']
				]);
			}

			$initials = [];

			foreach ($products as $key => $value)
			{
				if ($value['physical'] > 0)
				{
					$query2 = $this->database->insert('inventories', [
						'account' => Session::get_value('vkye_account')['id'],
						'branch' => System::temporal('get', 'inventories', 'branch')['id'],
						'movement' => 'input',
						'date' => Dates::future_date($data['end_date'], '1', 'days'),
						'hour' => Dates::current_hour(),
						'type' => 9,
						'product' => $key,
						'quantity' => (string) $value['physical'],
						'cost' => '0',
						'total' => '0',
						'location' => null,
						'categories' => json_encode([]),
						'origin' => json_encode([
							'type' => 'unt',
							'quantity' => '',
							'content' => ''
						]),
						'provider' => null,
						'bill' => null,
						'transfer' => json_encode([
							'branch' => '',
							'parent' => ''
						]),
						'created_date' => Dates::current_date(),
						'created_hour' => Dates::current_hour(),
						'created_user' => Session::get_value('vkye_user')['id']
					]);

					if (!empty($query2))
						array_push($initials, $this->database->id());
				}
			}

			if (!empty($initials))
			{
				$this->database->update('inventories_periods', [
					'initials' => json_encode($initials)
				], [
					'id' => $period
				]);
			}
		}

		return $query1;
	}

	public function read_inventories_periods()
	{
		$query = System::decode_json_to_array($this->database->select('inventories_periods', [
			'id',
			'started_date',
			'end_date',
			'products',
			'last',
			'saved'
		], [
			'AND' => [
				'account' => Session::get_value('vkye_account')['id'],
				'branch' => System::temporal('get', 'inventories', 'branch')['id']
			],
			'ORDER' => [
				'started_date' => 'DESC',
				'end_date' => 'DESC'
			]
		]));

		if (!empty($query))
		{
			foreach ($query as $key => $value)
			{
				$query[$key]['audits'] = $this->database->select('inventories_audits', [
					'id'
				], [
					'AND' => [
						'account' => Session::get_value('vkye_account')['id'],
						'started_date[<>]' => [$query[$key]['started_date'], $query[$key]['end_date']]
					]
				]);
			}

			$query[0]['last'] = true;
		}

		return $query;
	}

	public function read_inventory_period($id)
	{
		$query = System::decode_json_to_array($this->database->select('inventories_periods', [
			'[>]inventories_periods(previous_inventories_periods)' => [
				'previous' => 'id'
			],
			'[>]users' => [
				'created_user' => 'id'
			]
		], [
			'inventories_periods.id',
			'inventories_periods.started_date',
			'inventories_periods.end_date',
			'inventories_periods.physical',
			'inventories_periods.products',
			'inventories_periods.last',
			'inventories_periods.previous',
			'previous_inventories_periods.started_date(previous_started_date)',
			'previous_inventories_periods.end_date(previous_end_date)',
			'inventories_periods.saved',
			'inventories_periods.created_date',
			'inventories_periods.created_hour',
			'users.avatar(user_avatar)',
			'users.firstname(user_firstname)',
			'users.lastname(user_lastname)'
		], [
			'inventories_periods.id' => $id
		]));

		if (!empty($query))
		{
			$query[0]['audits'] = $this->database->select('inventories_audits', [
				'id'
			], [
				'AND' => [
					'account' => Session::get_value('vkye_account')['id'],
					'started_date[<>]' => [$query[0]['started_date'], $query[0]['end_date']]
				]
			]);

			return $query[0];
		}
		else
			return null;
	}

	public function check_exist_inventory_period($date)
	{
		$where = [
			'AND' => [
				'account' => Session::get_value('vkye_account')['id'],
				'branch' => System::temporal('get', 'inventories', 'branch')['id']
			]
		];

		if ($date == 'draft')
			$where['AND']['saved'] = 'draft';
		else
		{
			$where['AND']['end_date[>=]'] = $date;
			$where['AND']['saved'] = 'closed';
		}

		$query = $this->database->count('inventories_periods', $where);

		return ($query > 0) ? true : false;
	}

	public function update_inventory_period($data)
	{
		$existences = $this->read_inventories_existences('aperiod');
		$physical = System::temporal('get', 'inventories', 'aperiod')['physical'];
		$products = System::temporal('get', 'inventories', 'aperiod')['products'];

		foreach ($existences as $value)
		{
			if (array_key_exists($value['id'], $products))
			{
				$products[$value['id']]['theoretical'] = $value['existence']['theoretical'];
				$products[$value['id']]['physical'] = $value['existence']['physical'];
				$products[$value['id']]['variation'] = $value['variation'];
			}
		}

		$query1 = $this->database->update('inventories_periods', [
			'started_date' => $data['started_date'],
			'end_date' => $data['end_date'],
			'physical' => json_encode($physical),
			'products' => json_encode($products),
			'saved' => $data['saved']
		], [
			'id' => $data['id']
		]);

		if (!empty($query1) AND $data['saved'] == 'closed')
		{
			$last = System::decode_json_to_array($this->database->select('inventories_periods', [
				'id'
			], [
				'AND' => [
					'account' => Session::get_value('vkye_account')['id'],
					'branch' => System::temporal('get', 'inventories', 'branch')['id'],
					'last' => true
				]
			]));

			$this->database->update('inventories_periods', [
				'last' => true
			], [
				'id' => $data['id']
			]);

			if (!empty($last))
			{
				$this->database->update('inventories_periods', [
					'previous' => $last[0]['id']
				], [
					'id' => $data['id']
				]);

				$this->database->update('inventories_periods', [
					'last' => false
				], [
					'id' => $last[0]['id']
				]);
			}

			$initials = [];

			foreach ($products as $key => $value)
			{
				if ($value['physical'] > 0)
				{
					$query2 = $this->database->insert('inventories', [
						'account' => Session::get_value('vkye_account')['id'],
						'branch' => System::temporal('get', 'inventories', 'branch')['id'],
						'movement' => 'input',
						'date' => Dates::future_date($data['end_date'], '1', 'days'),
						'hour' => Dates::current_hour(),
						'type' => 9,
						'product' => $key,
						'quantity' => (string) $value['physical'],
						'cost' => '0',
						'total' => '0',
						'location' => null,
						'categories' => json_encode([]),
						'origin' => json_encode([
							'type' => 'unt',
							'quantity' => '',
							'content' => ''
						]),
						'provider' => null,
						'bill' => null,
						'transfer' => json_encode([
							'branch' => '',
							'parent' => ''
						]),
						'created_date' => Dates::current_date(),
						'created_hour' => Dates::current_hour(),
						'created_user' => Session::get_value('vkye_user')['id']
					]);

					if (!empty($query2))
						array_push($initials, $this->database->id());
				}
			}

			if (!empty($initials))
			{
				$this->database->update('inventories_periods', [
					'initials' => json_encode($initials)
				], [
					'id' => $data['id']
				]);
			}
		}

		return $query1;
	}

	public function delete_inventory_period($id)
	{
		$query = null;

		$deleted = System::decode_json_to_array($this->database->select('inventories_periods', [
			'previous',
			'initials'
		], [
			'id' => $id
		]));

		if (!empty($deleted))
		{
			$query = $this->database->delete('inventories_periods', [
				'id' => $id
			]);

			if (!empty($query))
			{
				if (!empty($deleted[0]['previous']))
				{
					$this->database->update('inventories_periods', [
						'last' => true
					], [
						'id' => $deleted[0]['previous']
					]);
				}

				if (!empty($deleted[0]['initials']))
				{
					$this->database->delete('inventories', [
						'id' => $deleted[0]['initials']
					]);
				}
			}
		}

		return $query;
	}

	public function create_inventory_type($data)
	{
		$query = $this->database->insert('inventories_types', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'movement' => $data['movement'],
			'system' => false,
			'blocked' => false
		]);

		return $query;
	}

	public function read_inventories_types($movement = '', $to_use = false)
	{
		$fields = [
			'id',
			'name',
			'movement',
			'system',
			'blocked'
		];

		$AND1 = [
			'system' => true
		];

		$AND2 = [
			'account' => Session::get_value('vkye_account')['id']
		];

		if ($to_use == true)
		{
			$AND1['movement'] = $movement;
			$AND1['blocked'] = false;
			$AND2['movement'] = $movement;
			$AND2['blocked'] = false;
		}

		$query1 = System::decode_json_to_array($this->database->select('inventories_types', $fields, [
			'AND' => $AND1,
			'ORDER' => [
				'movement' => 'ASC',
				'order' => 'ASC'
			]
		]));

		$query2 = System::decode_json_to_array($this->database->select('inventories_types', $fields, [
			'AND' => $AND2,
			'ORDER' => [
				'name' => 'ASC'
			]
		]));

		return array_merge($query1, $query2);
	}

	public function read_inventory_type($id)
	{
		$query = $this->database->select('inventories_types', [
			'name',
			'movement'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function update_inventory_type($data)
	{
		$query = $this->database->update('inventories_types', [
			'name' => $data['name'],
			'movement' => $data['movement']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_inventory_type($id)
	{
		$query = $this->database->update('inventories_types', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_inventory_type($id)
	{
		$query = $this->database->update('inventories_types', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_inventory_type($id)
    {
		$query = $this->database->delete('inventories_types', [
			'id' => $id
		]);

        return $query;
    }

	public function create_inventory_location($data)
	{
		$query = $this->database->insert('inventories_locations', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'blocked' => false
		]);

		return $query;
	}

	public function read_inventories_locations($to_use = false)
	{
		$where = [];

		if ($to_use == true)
		{
			$where['AND'] = [
				'account' => Session::get_value('vkye_account')['id'],
				'blocked' => false
			];
		}
		else
			$where['account'] = Session::get_value('vkye_account')['id'];

		$where['ORDER'] = [
			'name' => 'ASC'
		];

		$query = $this->database->select('inventories_locations', [
			'id',
			'name',
			'blocked'
		], $where);

		return $query;
	}

	public function read_inventory_location($id)
	{
		$query = $this->database->select('inventories_locations', [
			'id',
			'name'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function update_inventory_location($data)
	{
		$query = $this->database->update('inventories_locations', [
			'name' => $data['name']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_inventory_location($id)
	{
		$query = $this->database->update('inventories_locations', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_inventory_location($id)
	{
		$query = $this->database->update('inventories_locations', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_inventory_location($id)
    {
		$query = $this->database->delete('inventories_locations', [
			'id' => $id
		]);

        return $query;
    }

	public function create_inventory_category($data)
	{
		$query = $this->database->insert('inventories_categories', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'level' => $data['level'],
			'blocked' => false
		]);

		return $query;
	}

	public function read_inventories_categories($to_use = false)
	{
		$where = [];

		if (is_array($to_use) OR $to_use == true)
		{
			if (is_array($to_use))
				$where['id'] = $to_use;
			else if ($to_use == true)
			{
				$where['AND'] = [
					'account' => Session::get_value('vkye_account')['id'],
					'blocked' => false
				];
			}
		}
		else
			$where['account'] = Session::get_value('vkye_account')['id'];

		$where['ORDER'] = [
			'level' => 'ASC',
			'name' => 'ASC'
		];

		$query = $this->database->select('inventories_categories', [
			'id',
			'name',
			'level',
			'blocked'
		], $where);

		return $query;
	}

	public function read_inventory_category($id)
	{
		$query = $this->database->select('inventories_categories', [
			'name',
			'level'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function update_inventory_category($data)
	{
		$query = $this->database->update('inventories_categories', [
			'name' => $data['name'],
			'level' => $data['level']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_inventory_category($id)
	{
		$query = $this->database->update('inventories_categories', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_inventory_category($id)
	{
		$query = $this->database->update('inventories_categories', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_inventory_category($id)
    {
		$query = $this->database->delete('inventories_categories', [
			'id' => $id
		]);

        return $query;
    }
}
