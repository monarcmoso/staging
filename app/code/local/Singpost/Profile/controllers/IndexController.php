<?php
class Singpost_Profile_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	$customer_id = Mage::getSingleton('customer/session')->getId();
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
		   	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
		    return;
		}
		else {
	        $model = Mage::getModel("singpost_login/profile");
	        $data = $model->getProfile($customer_id);
	        if($data[0]['count'] == 0)
			{
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
			}
		}
		
		$dashboard_model = Mage::getModel("profile/dashboard");
		$dashboard = $dashboard_model->getDashboard();
		
		//check if already close forever
		$notif_logs = $model->getNotificationLogs($customer_id);
		$this->loadLayout();
		//passing of data to template
		$layout = $this->getLayout();
		$block = $layout->getBlock('profile');
		$block->setDashboard($dashboard);    
		$block->setPopup($notif_logs);    
		$this->renderLayout();
    }
	
	public function accountAction()
	{
		$customer_id = Mage::getSingleton('customer/session')->getId();
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
		   	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
		}//end if
		else 
		{
			$customer_id = Mage::getSingleton('customer/session')->getId();
	        $model = Mage::getModel("singpost_login/profile");
	        $data = $model->getProfile($customer_id);
	        if($data[0]['count'] == 0)
			{
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
			}
		}//end else
		
		//query of the user profile on the web form database
		$accountModel = Mage::getModel("profile/dashboard");
		$account = $accountModel->getUserProfile($customer_id);
		
		//get data of the user by id
		$customer = Mage::getModel("customer/customer"); 
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
		$customer->load($customer_id);
		
		$user_info = array('full_name'=>$customer->getFullName(),'gender'=>$customer->getGender(),'dob'=>$customer->getDob(),'medma_avatar'=>$customer->getMedmaAvatar());
		//load layout
		$this->loadLayout(); 
	
		//passing of data to template
		$layout = $this->getLayout();
		$block = $layout->getBlock('profile');
		$block->setAccoutProfile($account);
		$block->setUserData($user_info);  
		//render layout that has been loaded    
		$this->renderLayout();
	}
	
	public function settingsAction()
	{
		$customer_id = Mage::getSingleton('customer/session')->getId();
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
		   	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
		    return;
		}//end if
		else 
		{
			$customer_id = Mage::getSingleton('customer/session')->getId();
	        $model = Mage::getModel("singpost_login/profile");
	        $data = $model->getProfile($customer_id);
	        if($data[0]['count'] == 0)
			{
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
			}
		}//end else

		//get data of the user by id
		$customer = Mage::getModel("customer/customer");  
		$customer->load($customer_id);
		$address = array();
		foreach ($customer->getAddresses() as $data){
			$address = $data->toArray();
		}
		//model of the newsletter
		$accountModel = Mage::getModel("profile/dashboard");
		$newsLetter = $accountModel->getNewsletterSubscription($customer_id);
		$userNewsLetter = $accountModel->getUsersNewsLetter($customer_id);
		
		$this->loadLayout();
		//passing of data to template
		$layout = $this->getLayout();
		$block = $layout->getBlock('profile');
		$block->setNewsletter($newsLetter);
		$block->setUserNewsletter($userNewsLetter);
		$block->setAddress($address);
		$this->renderLayout();
	}
	
	// public function customerAction()
	// {
		// $customer_email = "sam@ekmedia.asia"; 
		// $customer = Mage::getModel("customer/customer"); 
		// $customer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
		// $customer->loadByEmail($customer_email); 
		// //load customer by email id //use 
		// //echo $customer->getId(); 
		// //echo $customer->getFirstName(); 
		// //echo $customer->getFullName(); 
		// // print "<pre>";
		// // print_r($customer->getData());
	// }
	
	// public function getUserById()
	// {
		// $customer_email = "sam@ekmedia.asia"; 
		// $customer = Mage::getModel("customer/customer"); 
		// $customer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
		// $customer->load('149');
		// //load customer by email id //use 
		// //echo $customer->getId(); 
		// //echo $customer->getFirstName(); 
		// //echo $customer->getFullName(); 
		// print_r($customer->getData());
	// }
	
	// public function testAction()
	// {
		 // $helper = Mage::helper('profile');
		 // echo $helper->userInvitation();
	// }
	
	// public function testAction()
	// {
		// $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode(1, 'gender');
		// //echo $attributeModel->getAttributeId();
		// //echo $attributeModel->getBackendType();
		// print "<pre>";
		// print_r($attributeModel);
		// print "</pre>";
	// }
	
	public function profileAjaxLoadAction()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//get id
			$customer_id = Mage::getSingleton('customer/session')->getId();
			
			$accountModel = Mage::getModel("profile/dashboard");
			$account = $accountModel->getUserProfile($customer_id);
			
			//get data of the user by id
			$customer = Mage::getModel("customer/customer"); 
			$customer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
			$customer->load($customer_id);
			
			$user_info = array('full_name'=>$customer->getFullName(),'gender'=>$customer->getGender(),'dob'=>$customer->getDob(),'medma_avatar'=>$customer->getMedmaAvatar());
			//load layout
			$this->loadLayout(); 
		
			//passing of data to template
			$layout = $this->getLayout();
			echo $layout->createBlock('profile/profile')->setTemplate('singpost/account.phtml')->setAccoutProfile($account)->setUserData($user_info)->toHtml();
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 
	}

	public function settingsAjaxLoadAction()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//load layout
			$this->loadLayout(); 
		
			//passing of data to template
			$layout = $this->getLayout();
			echo $layout->createBlock('profile/profile')->setTemplate('singpost/settings.phtml')->toHtml();
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 
	}

	public function editProfileAction()
	{
		//this will be the process of the ajax call for updating user profile!
		//validate the request is ajax request
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//if its valid ajax request do the process
			//check if the values are empty. the value should not have any empty variable
			$error = array();
			//$attribute_code = !empty($this->getRequest()->getPost('attr')) ? $this->getRequest()->getPost('attr') : array_push($error,'Update Failed. Please Contact the website Administrator.');
			$attribute_code = !empty($this->getRequest()->getPost('attr')) ? $this->getRequest()->getPost('attr') : array_push($error,'Update Failed. Please Contact the website Administrator.');
			$value = !empty($this->getRequest()->getPost('value')) ? $this->getRequest()->getPost('value') : array_push($error,'Please speciy your changes!');
			
			//validate the empty varialbles
			if(count($error) > 0)
			{
				//return error message
				echo json_encode(array('response'=>'error','result'=>$error));
				exit;
			}
			
			//validate if the edit is the bday!
			if($attribute_code == 'dob')
			{
				//validate the data if correct
				//yy+'-'+mm+'-'+dd;
				$date = explode("-",$value);
				if($date[1] > 12 || $date[1] < 01){
					echo json_encode(array('response'=>'error','result'=>'Invalid Date format'));
					exit;
				}
				else if($date[2] > 31 || $date[2] < 01){
					echo json_encode(array('response'=>'error','result'=>'Invalid Date format'));
					exit;
				}
				else if($date[0] > 2004 || $date[0] < 1900){
					echo json_encode(array('response'=>'error','result'=>'Invalid Date format'));
					exit;
				}
				//change the format of the dob value
				//$value =& date('Y-d-m H:i:s', strtotime($value));
			}

			//load model
			$actionModel = Mage::getModel("profile/action");
			$action = $actionModel->editProfileModel($value, $attribute_code,$this->getRequest()->getPost('oldVal'));
			if($action == true)$message = 'Changes has been saved.';
			else $message = 'Update Failed. Please try again!';
			echo json_encode(array('response'=>'update','result'=>array('submit'=>$action,'message'=>$message)));
			//$this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('response'=>'update','result'=>array('submit'=>$action,'message'=>$message))));
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));	
		} 
	}

	public function welcomeAction()
	{
		//only display once
		//redirect to this page.
		//customer id
		$customer_id = Mage::getSingleton('customer/session')->getId();
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
		   	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
		    return;
		}
		
		//get login count
		$model = Mage::getModel("singpost_login/profile");
		$notif_logs = $model->getNotificationLogs($customer_id);
		if(!$notif_logs > 0){
			//display the page
			//load layout
			$this->loadLayout(); 
			//passing of data to template
			$layout = $this->getLayout();
			$this->renderLayout();
		}
		else{
			// //redirect the page to account page
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
		}
	}

	public function fbProfileAction()
	{
		//1. check if fb registration
		//2. check the flag of fb registration
		//3. check the belvg_facebook_customer count, if count == 1 display this page
		//4. validate by date of birth and when submit update the date to existing model.
		$customer_id = Mage::getSingleton('customer/session')->getId();
		//check if the user is login
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
		   	Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
		    return;
		}
		//model to check if fb user
		$model = Mage::getModel("facebookfree/facebookfree");
		$fbLoginCount = $model->countUserFbLogin();
		if($fbLoginCount > 0){
			$customer = Mage::getModel("customer/customer"); 
			$customer->load($customer_id);
			if($customer->getDob() == '1660-01-12 00:00:00'){
				//date of birth is not define
				//do the display of the page
				$this->loadLayout(); 
				//passing of data to template
				$layout = $this->getLayout();
				$this->renderLayout();
			}else{
				Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/index'));
			}
		}
		else{
			//user is not fb login by FB
			//redirect to the profile settings
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('profile/settings'));
		}
	}
}