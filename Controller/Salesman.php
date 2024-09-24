<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/salesman.php';
require_once 'Model/salesman_address.php';
/**
 * 
 */
class Controller_Salesman extends Controller_Core_Action
{
	protected $salesmen = null;
	protected $modelSalesman = null;
	protected $modelSalesmanAddress = null;

	public function gridAction()
	{
		$modelSalesman =$this->getModelSalesman();
		$salesmen =$modelSalesman->fetchAll();
		$this->setSalesmen($salesmen);
		$this->getTemplete('salesman/grid.phtml');
	}
	public function addAction()
	{
		$this->getTemplete('salesman/add.phtml');
	}
	public function editAction()
	{
		$request = $this->getRequest();
		$id=(int)$request->getParam('salesman_id');
		if(!isset($id))
		{
		throw new Exception("invalid product id.", 1);
		}
		$modelSalesman =$this->getModelSalesman();
		$sql = "SELECT * FROM `salesmen` WHERE `salesman_id`= {$id}";
		$salesman =$modelSalesman->fetchRow($sql);
		$this->setSalesmen($salesman);
		$this->getTemplete('salesman/edit.phtml');
	}
	public function insertAction()
	{
		$request = $this->getRequest();
		if (!$request->isPost())
		{
			throw new Exception("invalid Request.", 1);
		}
		$salesman = $request->getPost('salesman');
		$address = $request->getPost('salesman_address');
		$modelSalesman =$this->getModelSalesman();
		$insert = $modelSalesman->insert($salesman);
		if (!$insert) {
			throw new Exception("Error Processing Request", 1);
		}

		$address['salesman_id'] = $insert; 
		$modelSalesmanAddress = $this->getModelSalesmanAddress();
		$insert2=$modelSalesmanAddress->insert($address);
		return $this->redirect('grid', null, null, true);

	}
	public function deleteAction()
	{
		$request = $this->getRequest();		
		$id =(int)$request->getParam('salesman_id');
		if(!$id)
		{
			throw new Exception("invalid salesman ID.", 1);
		}	

		$modelSalesman =$this->getModelSalesman();
		$delete = $modelSalesman->delete($id);
		return $this->redirect('grid', null, null, true);
	}
	public function updateAction()
	{
		$request = $this->getRequest();
		if (!$request->isPost())
		{
			throw new Exception("invalid Request.", 1);
		}
		$salesman = $request->getPost('salesman');
 			$modelSalesman =$this->getModelSalesman();
		$update = $modelSalesman->update($salesman, $salesman['salesman_id']);
		return $this->redirect('grid', null, null, true);
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

    public function getModelSalesman()
    {
        if(!$this->modelSalesman)
        {
        	$this->modelSalesman = new Model_Salesman();
        }
        return $this->modelSalesman;
    }

    public function getModelSalesmanAddress()
    {
        if(!$this->modelSalesmanAddress)
        {
        	$this->modelSalesmanAddress = new Model_SalesmanAddress();
        }
        return $this->modelSalesmanAddress;
    }
}

?>