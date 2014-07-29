<?php
class Singpost_Profile_NotificationsController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		//validate user  if login
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
		   	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
		    return;
		}
		else {
	        $model = Mage::getModel("singpost_login/profile");
	        $data = $model->getProfile($this->_customerId());
	        if($data[0]['count'] == 0)
			{
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
			}
		}
		$collection = Mage::getModel('sales/order')
	                ->getCollection()
	                ->addAttributeToFilter('customer_email',array('like'=>$this->_customerEmail()));
		// load the page of the notification.
		$this->loadLayout();
		//passing of data to template
		$layout = $this->getLayout();
		$block = $layout->getBlock('profile');
		$block->setNotification($collection);    
		// $block->setPopup($notif_logs);    
		$this->renderLayout();
		
	}
	
	public function _customerId()
	{
		return Mage::getSingleton('customer/session')->getId();
	}
	
	public function _customerEmail()
	{
		return Mage::getSingleton('customer/session')->getCustomer()->getEmail();
	}
}?>