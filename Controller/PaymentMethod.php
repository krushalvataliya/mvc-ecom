<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Payment_method.php';
/**
 * 
 */
class Controller_PaymentMethod extends Controller_Core_Action
{
	protected $paymentMethods = [];
	protected $modelPaymentMethod = null;
	public function gridAction()
	{
		$modelPaymentMethod =$this->getModelPaymentMethod();
		$paymentMethods =$modelPaymentMethod->fetchAll();
		$this->setPaymentMethod($paymentMethods);
		$this->getTemplete('payment_method/grid.phtml');
	}
	
	public function addAction()
	{
		$this->getTemplete('payment_method/add.phtml');
	}

	public function editAction()
	{
		$request = $this->getRequest();
		$id=$request->getParam('payment_method_id');
		if(!isset($id))
		{
		throw new Exception("invalid product id.", 1);
		}

		$modelPaymentMethod =$this->getModelPaymentMethod();
		$sql = "SELECT * FROM `payment_methods` WHERE `payment_method_id`= {$id}";
		$paymentMethod =$modelPaymentMethod->fetchRow($sql);
		$this->setPaymentMethod($paymentMethod);
		$this->getTemplete('payment_method/edit.phtml');
	}

	public function insertAction()
	{
		$request = $this->getRequest();
		if (!$request->isPost())
		{
			throw new Exception("invalid Request.", 1);
		}

		$paymentMethod = $request->getPost('payment_method');

		$modelPaymentMethod =$this->getModelPaymentMethod();
		$insert=$modelPaymentMethod->insert($paymentMethod);
		return $this->redirect('grid', null, null, true);
	}
	public function deleteAction()
	{
		$request = $this->getRequest();
		$id = (int) $request->getParam('payment_method_id');
		$modelPaymentMethod =$this->getModelPaymentMethod();
		$delete = $modelPaymentMethod->delete($id);
		return $this->redirect('grid', null, null, true);
	}
	public function updateAction()
	{
		$request = $this->getRequest();
		if (!$request->isPost())
		{
			throw new Exception("invalid Request.", 1);
		}
		
		$paymentMethod = $request->getPost('payment_method');
		$modelPaymentMethod =$this->getModelPaymentMethod();
		$update = $modelPaymentMethod->update($paymentMethod,$paymentMethod['payment_method_id']);
		return $this->redirect('grid', null, null, true);
	}

    public function getPaymentMethod()
    {
        return $this->paymentMethods;
    }

    public function setPaymentMethod($paymentMethods)
    {
        $this->paymentMethods = $paymentMethods;

        return $this;
    }

    public function getModelPaymentMethod()
    {
        if(!$this->modelPaymentMethod)
        {
        	$this->modelPaymentMethod = new Model_PaymentMethod();
        }
        return $this->modelPaymentMethod;
    }

}

?>