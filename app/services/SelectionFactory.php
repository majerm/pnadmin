<?php
/**
 * Created by Pavel Rak (rak.pavel.89@gmail.com)
 * Copyright(c) 2013. All rights reserved.
 */

namespace PNAdmin;


use Nette\Database\Connection;
use Nette\Object;
use PNAdmin\Selections\PresentableSelection;

class SelectionFactory extends Object
{
	private $selectionsNamespace;
	private $connection;

	public function __construct($selectionsNamespace, Connection $connection)
	{
		$this->selectionsNamespace = $selectionsNamespace;
		$this->connection = $connection;
	}

	public function create($table) {
		return new PresentableSelection($table, $this->connection, $this->selectionsNamespace);
	}
}