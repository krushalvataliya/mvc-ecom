<?php 
require_once 'Core/Table.php';

class Model_Product extends Model_Core_Table
{
	 function __construct()
	{
		$this->setTableName('products');
		$this->setPrimaryKey('product_id');
	}

}

?>