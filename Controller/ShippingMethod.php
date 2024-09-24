<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Shipping_method.php';

/**
 * 
 */
class Controller_ShippingMethod extends Controller_Core_Action
{
	protected $shippingMethods = [];
	protected $modelShippingMethod = null;

	public function gridAction()
	{	
		$modelShippingMethod =$this->getModelShippingMethod();
		$shippingMethods =$modelShippingMethod->fetchAll();
		$this->setShippingMethod($shippingMethods);
		$this->getTemplete('shipping_method/grid.phtml');
	}
	public function addAction()
	{
		$this->getTemplete('shipping_method/add.phtml');
	}
	public function editAction()
	{
		$request = $this->getRequest();
		$id=(int)$request->getParam('shiping_method_id');
		if(!isset($id))
		{
		  throw new Exception("invalid product id.", 1);
		}
		$modelShippingMethod =$this->getModelShippingMethod();
		$sql = "SELECT * FROM `shiping_methods` WHERE `shiping_method_id`= {$id}";
		$shipingMethod =$modelShippingMethod->fetchRow($sql);
		$this->setShippingMethod($shipingMethod);
		$this->getTemplete('shipping_method/edit.phtml');
	}
	public function insertAction()
	{
		$request = $this->getRequest();
		if (!$request->isPost())
		{
			throw new Exception("invalid Request.", 1);
		}

		$shipingMethod = $request->getPost('shipping_method');
		$modelShippingMethod =$this->getModelShippingMethod();
		$insert=$modelShippingMethod->insert($shipingMethod);
		return $this->redirect('grid', null, null, true);

	}
	public function deleteAction()
	{
		$request = $this->getRequest();
		$id =(int) $request->getParam('shiping_method_id');
		if(!$id)
		{
			throw new Exception("invalid shiping method ID", 1);
		}
			
		$modelShippingMethod =$this->getModelShippingMethod();
		$delete = $modelShippingMethod->delete($id);
		return $this->redirect('grid', null, null, true);
	}
	public function updateAction()
	{
		$request = $this->getRequest();
		if (!$request->isPost())
		{
			throw new Exception("invalid Request.", 1);
		}
		$shipingMethod = $request->getPost('shiping_method');
		$modelShippingMethod =$this->getModelShippingMethod();
		$update = $modelShippingMethod->update($shipingMethod, $shipingMethod['shiping_method_id']);
		return $this->redirect('grid', null, null, true);
	}
	
    public function getShippingMethod()
    {
        return $this->paymentMethod;
    }

    public function setShippingMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getModelShippingMethod()
    {
        if(!$this->modelShippingMethod)
        {
        	$this->modelShippingMethod = new Model_ShippingMethod();
        }
        return $this->modelShippingMethod;
    }

}

?>