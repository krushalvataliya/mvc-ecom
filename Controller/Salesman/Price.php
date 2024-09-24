<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Salesman_price.php';
/**
 * 
 */
class Controller_Salesman_price extends Controller_Core_Action
{
	protected $salesmenPrice = [];
	protected $salesmen = [];
	protected $modelSalesmanPrice = null;

	public function gridAction()
	{
		$request = $this->getRequest();
		$id=(int)$request->getParam('salesman_id');
		if(!$id)
		{
			throw new Exception("invalid salesman ID.", 1);
		}
		
		$sql="SELECT * FROM `salesmen` ORDER BY `first_name` ASC";
		$modelSalesmanPrice =$this->getModelSalesmanPrice();
		$salesmen = $modelSalesmanPrice->fetchAll($sql);
		$this->setSalesmen($salesmen);
		$sql = "SELECT SP.entity_id, SP.salesman_price, P.sku, P.cost, P.price, P.product_id 
		FROM `products` P 
		LEFT JOIN `salesman_price` SP ON P.product_id = SP.product_id AND SP.salesman_id = ".$id."";
		$products = $modelSalesmanPrice->fetchAll($sql);
		$this->setSalesmenPrice($products);
		$this->getTemplete('Salesman_price/grid.phtml');
	}

	public function updateAction()
	{
		$request = $this->getRequest();
		if (!$request->isPost())
		{
			throw new Exception("invalid Request.", 1);
		}

		$updateSalesmanPrice = $request->getPost('sprice');
		$id = (int)$request->getParam('salesman_id');
		if(!$id)
		{
			throw new Exception("invalid salesman ID;", 1);
		}

		$modelSalesmanPrice =$this->getModelSalesmanPrice();
		$button = $request->getPost('button');
		if($button == 'update'){
		foreach ($updateSalesmanPrice as $key => $value)
		{
		$sql = 'SELECT `entity_id` FROM `salesman_price` WHERE `product_id` = '.$key.' AND `salesman_id` = '.$id.'';
		$result = $modelSalesmanPrice->fetchAll($sql);
		if ($result)
		{
			$salesmanPrice['salesman_price'] = $value;
			$condition = array('product_id' => $key, 'salesman_id' => $id);
		$result = $modelSalesmanPrice->update($salesmanPrice, $condition);
		}
		else
		{
		if($value != null)
		{
		$salesmanPrice = array('product_id' => $key, 'salesman_id' => $id, 'salesman_price' => $value);
		$result = $modelSalesmanPrice->insert($salesmanPrice);
		}
		}
		}
		}
		if ($button == 'delete')
		{
			 return  $this->deleteAction();
		}
		return $this->redirect($this->getModelUrl()->getUrl('grid','salesman_price',['salesman_id'-> $id]));
		}

	

	public function deleteAction()
	{
		$id = (int)$this->getRequest()->getParam('salesman_id');
		$modelSalesmanPrice =$this->getModelSalesmanPrice();
		$delete = $this->getRequest()->getPost('delete_price');
		if(isset($delete))
		{
		foreach ($delete as $key => $value) {
		$result = $modelSalesmanPrice->delete($key);
		}
		return $this->redirect($this->getModelUrl()->getUrl('grid','salesman_price',['salesman_id'-> $id]));
	}
	}

    public function getSalesmenPrice()
    {
        return $this->salesmenPrice;
    }

    public function setSalesmenPrice($salesmenPrice)
    {
        $this->salesmenPrice = $salesmenPrice;

        return $this;
    }

    public function getModelSalesmanPrice()
    {
        if(!$this->modelSalesmanPrice)
        {
        $this->modelSalesmanPrice = new Model_SalesmanPrice();
        }
        return $this->modelSalesmanPrice;
    }

    public function getSalesmen()
    {
        return $this->salesmen;
    }
    
    public function setSalesmen($salesmen)
    {
        $this->salesmen = $salesmen;

        return $this;
    }
}

?>