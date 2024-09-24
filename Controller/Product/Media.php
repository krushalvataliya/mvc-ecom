<?php 
require_once 'Controller/Core/Action.php';
require_once 'Model/product_media.php';
class Controller_Product_Media extends Controller_Core_Action
{
	protected $productMedia = [];
	protected $modelProductMedia = null;
	function gridAction()
	{
		$request = $this->getRequest();
		$productId=(int)$request->getParam('product_id');
		$sql ="SELECT * FROM `media` WHERE `product_id`= $productId ;";
		$modelProductMedia =$this->getModelProductMedia();
		$results =$modelProductMedia->fetchAll($sql);
		

		$this->setProductMedia($results);
		$this->getTemplete('product_media/grid.phtml');
	}
	function addAction()
	{
		$this->getTemplete('product_media/add.phtml');
	}
	function insertAction()
	{
		$request = $this->getRequest();
		$productId =(int) $request->getParam('product_id');
		if(!isset($productId))
		{
			throw new Exception("invalid product ID.", 1);
			
		}
		$targetDir = "View/product_media/image/";
		$file = basename($_FILES["fileToUpload"]["name"]);
		$fileArray = explode('.', $file);
		print_r($_FILES);
		$media = $request->getPost('media');
		$targetName='IMG_'.time().'.'.$fileArray[1];
		$targetFile = $targetDir .$targetName;
		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile);
		$media['img'] = $targetName;
		$modelProductMedia =$this->getModelProductMedia();
		$insert=$modelProductMedia->insert($media);
		if(!$insert)
		{
			throw new Exception("data not inserted.", 1);
			
		}
		echo $this->getModelUrl()->getUrl('grid','product_media',['product_id'-> $productId]);
		// die;
		// return $this->redirect($this->getModelUrl()->getUrl('grid','product_media',['product_id'-> $productId]));		
		return $this->redirect('grid','product_media',['product_id'-> $productId]);		

	}
	
	function updateAction()
	{
		$request = $this->getRequest();
		$button = $request->getPost('button');
		if($button == 'delete')
		{
			return $this->deleteAction();
		}
		$request = $this->getRequest();
		$productId['product_id'] =(int)$request->getParam('product_id');
		$gallaryId = $request->getPost('gallary');
		$thumbnail = (int)$request->getPost('thumbnail');
		$midium = (int)$request->getPost('midium');
		$large = (int)$request->getPost('large');
		$small = (int)$request->getPost('small');
		$modelProductMedia =$this->getModelProductMedia();
		$resetValue = array('thumbnail' => 0, 'base' => 0 ,'midium' => 0 ,'large' => 0, 'small' => 0, 'gallary' => 0);
		$result =$modelProductMedia->update($resetValue, $productId);
		$setThumbnail = array('thumbnail' => 1);
		$result =$modelProductMedia->update($setThumbnail, $thumbnail);
		$setMidium = array('midium' => 1);
		$result =$modelProductMedia->update($setMidium, $midium);
		$setLarge = array('large' => 1);
		$result =$modelProductMedia->update($setLarge, $large);
		$setSmall = array('small' => 1);
		$result =$modelProductMedia->update($setSmall, $small);
		$setGallary = array('gallary' => 1);
		foreach ($gallaryId as $key => $value)
		{
			$result =$modelProductMedia->update($setGallary, $value);
		}
		
		return $this->redirect('grid','product_media',['product_id'-> $productId]);		
	}
	
	function deleteAction()
	{	
		$request = $this->getRequest();
		$productId =(int)$request->getParam('product_id');
		if(!$productId)
		{
			throw new Exception("invalid product ID.", 1);
			
		}
		$deleteImageId = $request->getPost('delete_image');
		if($deleteImageId != null)
		{
		$modelProductMedia =$this->getModelProductMedia();
		foreach ($deleteImageId as $key => $value) {
			$sql ="SELECT * FROM `media` WHERE `media_id`= $value ;";
			$imageName =$modelProductMedia->fetchRow($sql);
			$result =$modelProductMedia->delete($value);
			$image = 'View/product_media/image/'.$imageName['img'];
			if (file_exists($image))
			{
				unlink($image);
			}
		}

		}
		return $this->redirect('grid','product_media',['product_id'-> $productId]);		
	}

    public function getModelProductMedia()
    {
        if(!$this->modelProductMedia)
        {
        	$this->modelProductMedia = new Model_ProductMedia();
        }
        return $this->modelProductMedia;
    }
    public function getProductMedia()
    {
        return $this->productMedia;
    }

    public function setProductMedia($productMedia)
    {
        $this->productMedia = $productMedia;

        return $this;
    }
}

?>