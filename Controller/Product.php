<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/Product.php';

class Controller_Product extends Controller_Core_Action
{
	protected $products = [];

	public function getProduct()
	{
		return $this->products;
	}

    protected function setProduct($products)
	{
		$this->products = $products;
		return $this;
	}
	public function gridAction()
	{	
		$modelProduct = new Model_Product();
		$products =$modelProduct->fetchAll();
		$this->setProduct($products);
		$this->getTemplete('product/grid.phtml');
	}

	public function addAction()
	{
		$this->getTemplete('product/add.phtml');
	}

	public function editAction()
	{
		try {
			$request = $this->getRequest();
			$id=$request->getParam('product_id');
			if(!isset($id))
			{
				throw new Exception("invalid product id.", 1);
			}
			$modelProduct = new Model_Product();
			$sql = "SELECT * FROM `products` WHERE `product_id`= {$id}";
			$product =$modelProduct->fetchRow($sql);
			$this->setProduct($product);
			$this->getTemplete('product/edit.phtml');
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('invalid product id.',  Model_Core_Message::FAILURE);
		}

	}

	public function insertAction()
	{
		try {	$request = $this->getRequest();
				if (!$request->isPost()) {
					throw new Exception("invalid Request.", 1);
				}
		
				$product = $request->getPost('product');
				if(!$product)
				{
					throw new Exception("no data posted.", 1);
				}
		
				$modelProduct = new Model_Product();
				$productId =$modelProduct->insert($product);
				if(!$productId)
				{
					throw new Exception("Error Processing Request", 1);
					
				}
				$this->getMessage()->addMessage('product added successfully',  Model_Core_Message::SUCCESS);
			}
			catch (Exception $e)
			{
				$this->getMessage()->addMessage('product not inserted',  Model_Core_Message::FAILURE);
					
			}
		return $this->redirect('grid', null, null, true);
	}

	public function updateAction()
	{
		try {
			
		$request = $this->getRequest();
		if (!$request->isPost()) {
			throw new Exception("invalid Request.", 1);
		}

		$product = $request->getPost('product');
		if(!$product)
		{
			throw new Exception("no data posted.", 1);
		}

		$modelProduct = new Model_Product();
		$result =$modelProduct->update($product,$product['product_id']);
		if(!$result)
		{
			throw new Exception("Error Processing Request", 1);
			
		}
		$this->getMessage()->addMessage('product updated successfully.',  Model_Core_Message::SUCCESS);

		} catch (Exception $e) {
		$this->getMessage()->addMessage('product not updated.',  Model_Core_Message::FAILURE);

		}
		return $this->redirect('grid', null, null, true);
	}

	public function deleteAction()
	{
	try{
			$request = $this->getRequest();
			if (!$request->isGet()) {
				throw new Exception("invalid Request.", 1);
			}

			$id = $request->getParam('product_id');
			$modelProduct = new Model_Product();
			$result =$modelProduct->delete($id);
			if(!$result)
			{
				throw new Exception("Error Processing Request", 1);
				
			}
			$this->getMessage()->addMessage('product deleted successfully.',  Model_Core_Message::SUCCESS);
		}
	catch(Exception $e){
			$this->getMessage()->addMessage('product not deleted.',  Model_Core_Message::FAILURE);
	}
		return $this->redirect('grid', null, null, true);
	}

	public function testAction()
	{


		
	}

}

?>