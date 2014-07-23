<?php
class Singpost_Profile_ActionController extends Mage_Core_Controller_Front_Action
{
	public function changePasswordAction()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//get id
			$error = array();
			//validate if empty, just to check if we miss in the javascript
			$currPass = !empty($this->getRequest()->getPost('current_password')) ? $this->getRequest()->getPost('current_password') : array_push($error,'Please Enter Your Password');
			$newPass = !empty($this->getRequest()->getPost('new_password')) ? $this->getRequest()->getPost('new_password') : array_push($error,'Please Enter Your New Password');
			$confPass = !empty($this->getRequest()->getPost('confirm_password')) ? $this->getRequest()->getPost('confirm_password') : array_push($error,'Please Confirm Your Password');

			//check if the data is empty, return if there is empty
			if(count($error) > 0)
			{
				echo json_encode(array('result'=>'error','message'=>$error));
				exit;
			}
			
			$model = Mage::getModel("profile/action");
			if($model->_checkFbUser() === true){
				echo json_encode(array('result'=>'error','message'=>array('User from Facebook cannot change password.')));
				exit;
			}

			//validate if the new password is equal to the confirm password
			if($new_password != $confirm_password){
				echo json_encode(array('result'=>'error','message'=>array('Passwords does not match.')));
			}//end if
			else {
				//check if the password is correct.
				//1. Get the user id of the user
				$customer_id = Mage::getSingleton('customer/session')->getId();
				//2. check user data with password, get by id
				$customer = Mage::getModel("customer/customer"); 
				$customer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
				$customer->load($customer_id);
				
				// 3. get the salt from old password
				$customer_session = Mage::getSingleton('customer/session')->getCustomer();
				$oldPass = $customer_session->getPasswordHash();
				if (Mage::helper('core/string')->strpos($oldPass, ':')) {
				    list($_salt, $salt) = explode(':', $oldPass);
				} else {
				    $salt = false;
				}
				
				//4.check if the password is equal to the old password
				if ($customer_session->hashPassword($currPass, $salt) == $oldPass) 
				{
				    $customer_session->setPassword($newPass);
				    $customer_session->setConfirmation($confPass);
					try{
						$customer_session->save();
						echo json_encode(array('result'=>'success','message'=>'Password Changed.'));
					}
					catch (Exception $e) {
						echo json_encode(array('result'=>'error','message'=>array($e)));
					}
					//echo json_encode(true);
				} //if ($customer->hashPassword($currPass, $salt) == $oldPass) 
				else 
				{
					echo json_encode(array('result'=>'error','message'=>array('Invalid current password')));
				}
			}//end else
		}
		else{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 
	}

	public function changeEmailPostAction()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			$model = Mage::getModel("profile/action");
			//1.validate if empty
			$error = array();
			$password = !empty($this->getRequest()->getPost('password')) ? $this->getRequest()->getPost('password') : array_push($error,'Please Enter Your Password');	
			$email = !empty($this->getRequest()->getPost('email_address')) ? $this->getRequest()->getPost('email_address') : array_push($error,'Please Enter Your Email');
			//2.Check if there is error and return the message
			if(count($error) > 0)
			{
				echo json_encode(array('result'=>'error','message'=>$error));
				exit;
			}
			
			if($model->_checkFbUser() === true){
				echo json_encode(array('result'=>'error','message'=>array('User from Facebook cannot change password.')));
				exit;
			}
			//3.validate if the email address is existing in the database
			$customer = Mage::getModel('customer/customer');
			$customer->loadByEmail($email);
			if (!$customer->getId()) 
			{
				//get customer info by id
				$customer_info = Mage::getModel("customer/customer"); 
				$customer_info->setWebsiteId(Mage::app()->getWebsite()->getId()); 
				$customer_info->load($this->_customerId());
				$hash = $customer_info->getPasswordHash();
				//4. check if valid password using the core helper
		        if (Mage::helper('core')->validateHash($password, $hash) === true) 
		        {
		        	//save the details and create a random generate numbers (md5 + sha1 of the )
		        	$random = substr(number_format(time() * rand(),0,'',''),0,10);
		        	$uniq_key = $this->_encrypt($random);
					if($uniq_key == false){
						echo json_encode(array('result'=>'error','message'=>array('Cannot process your request. Please contact the administrator')));
					}
					else {
						//insert the data to the database
						$data = array(
							'customer_id'=>$this->_customerId(),
							'store_id'=>$this->_storeId(),
							'email'=>$email,
							'activation_key'=>$uniq_key,
						);
						
						$query = $model->editEmail($data);
						if($query == true)
						{
							//query is save send the email from the helper
							if(Mage::helper('profile')->sendNewEmailVerification($email,$uniq_key) === true)
							{
								echo json_encode(array('result'=>'success','message'=>'Activation key has been sent to your new email address.'));	
							}
							else{
								echo json_encode(array('result'=>'error','message'=>array('Failure Sending Verification Email. Please contact the administrator')));
							}
						}//if($query == true)
						else{
							echo json_encode(array('result'=>'error','message'=>array('Cannot update your request. Please contact the administrator')));
						}
					}//else {
		        }
				else{
					//return error message that the password is invalid
					echo json_encode(array('result'=>'error','message'=>array('Passwords is Invalid.')));
				}
			}//if (Mage::helper('core')->validateHash($password, $hash) === true) 
			else {
				//return error message that the user is registered
				echo json_encode(array('result'=>'error','message'=>array('The Email Address in used!')));
			}
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 
	}
	
	public function changeMobilePostAction()
	{
		//check if ajax request
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			$error = array();
			$mobile = !empty($this->getRequest()->getPost('mobile_num')) ? $this->getRequest()->getPost('mobile_num') : array_push($error,'Please Enter Your 8 Digit Mobile Number');
			//count if there is any error
			if(count($error) > 0){
				echo json_encode(array('result'=>'error','message'=>$error));
				exit;			
			}
			
			//1. eliminate invalid characters
			$mobile =& preg_replace("/[^0-9]/", '', $mobile);
			//2..count if valid 8 digit mobile number after the preg_replace
			if(strlen($mobile) != 8){
				echo json_encode(array('result'=>'error','message'=>array('Invalid Mobile Number')));
				exit;
			}
			//3.load the model to insert the data
			$model = Mage::getModel("profile/action");
			$query = $model->editSettings($mobile,'telephone');
			echo json_encode($query);
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 
	}
	
	public function changeCountryPostAction()
	{
		//check if ajax request
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//check if empty and put to a string
			$country = !empty($this->getRequest()->getPost('country')) ? $this->getRequest()->getPost('country') : '';
			//1.validate if the data is empty and return message if empty
			if($country == ''){
				echo json_encode(array('result'=>'error','message'=>'Please define your country of origin'));
				exit;
			}
			//2. get the model
			//echo json_encode($country);
			$model = Mage::getModel("profile/action");
			$query = $model->editSettings($country,'country_id');
			echo json_encode($query);
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 
	}
	
	public function updateNewsLetterPostAction()
	{
		//validate if ajax request
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//validate data if empty or not
			$special_offers = !empty($this->getRequest()->getPost('special_offers')) ? $this->getRequest()->getPost('special_offers') : '';
			$new_product_information = !empty($this->getRequest()->getPost('new_product_information')) ? $this->getRequest()->getPost('new_product_information') : '';
			$result_id = !empty($this->getRequest()->getPost('result_id')) ? $this->getRequest()->getPost('result_id') : '';
			//result id must not be 0 or empty
			if(!$result_id > 0){
				echo json_encode(array('result'=>'error','message'=>'Update Failed. Please contact the web administrator.'));
				exit;
			}
			$model = Mage::getModel("profile/action");
			$query = $model->editNewsLetter($special_offers,$new_product_information,$result_id);
			echo json_encode($query);
		}
		else{
			//redirect if not ajax request
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));
		}
		
	}
	public function changeAddressPostAction()
	{
		//check if ajax request
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//check if empty and put to a string
			$address = !empty($this->getRequest()->getPost('address')) ? $this->getRequest()->getPost('address') : '';
			//1. check if the address is empty
			if($address == ''){
				echo json_encode(array('result'=>'error','message'=>'Please define your shipping address'));
				exit;
			}
			
			$model = Mage::getModel("profile/action");
			$query = $model->editSettings($address,'shipping_address');
			echo json_encode($query);
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 
	}
	
	public function updateFacebookUsersPostAction()
	{
		//check if ajax request
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
		{
			//array for the message;
			$result = array(
				'country' => '',
				'dob' => '', 
				'errors' => array()
			);
			$model = Mage::getModel("profile/action");
			
			$dob = $this->getRequest()->getPost('dob');
			$country = $this->getRequest()->getPost('country');
			
			$tnc = $this->getRequest()->getPost('tnc');
			$singpost_special = $this->getRequest()->getPost('singpost_special');
			$third_party = $this->getRequest()->getPost('third_party');
			$webform_id = $this->getRequest()->getPost('webform_id');
			
			$dob = (!empty($dob)) ? $dob : '';
			$country = (!empty($country)) ? $country : '';
			$tnc = (!empty($tnc)) ? $tnc : '';
			$singpost_special = (!empty($singpost_special)) ? $singpost_special : '';
			$third_party = (!empty($third_party)) ? $third_party : '';
			$webform_id = (!empty($webform_id)) ? $webform_id : '';
			
			//validate if the edit is the bday!
			if($dob == ''){
				echo json_encode(array('response'=>'error','result'=>'Date of birth is not specified'));
			}
			else 
			{
				//do the process of updating of dob
				//dob = month+'-'+day+'-'+year;
				$date = explode("-",$dob);
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

				$action = $model->editProfileModel($dob, 'dob');
				if($action == true)$result['dob'] = true;
				else $result['errors'] = 'Date of birth doesnt save';
			}
			
			if($country == ''){
				echo json_encode(array('response'=>'error','result'=>'Country is not specified'));
			}
			else{
				//do the process of updating of the country
				$customAddress = Mage::getModel('customer/address');
				$customAddress->setData('country_id', 'sg')
				            ->setCustomerId($this->_customerId())
		           			->setSaveInAddressBook('1');
				try {
				    $customAddress->save();
					$result['country'] = true;
				}
				catch (Exception $ex) {
				    //Zend_Debug::dump($ex->getMessage());
				    $result['errors'] = 'Country doesnt save';
				}
			}
			
			if($webform_id == '' || $tnc == ''){
				echo json_encode(array('response'=>'error','result'=>'Terms and Condition is not specified'));
			}
			else{
				try {
					$iplong = ip2long(Mage::helper('webforms')->getRealIp());
					$webform = Mage::getModel('webforms/results');
					//$result->setData('field', $postData['field'])
					$webform->setWebformId($webform_id)
	                ->setStoreId(Mage::app()->getStore()->getId())
	                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
	                ->setCustomerIp($iplong)
	                ->setApproved(1)
	                ->save();
					
					//process steps
					//1. Check if empty, if not insert to array
					$array = array();
					array_push($array,array('result_id'=>$webform->getId(),'field_id'=>$tnc,'value'=>"I hereby (1) acknowledge and accept the Terms and Conditions of use of The Sample Stores sampling services, the Terms of Use of this website and The Sample Stores Privacy Policy, and (2) agree for The Sample Store to collect and use my personal data to open a User Account for me."));
					
					if($singpost_special != ''){
						//array
						array_push($array,array('result_id'=>$webform->getId(),'field_id'=>$singpost_special,'value'=>"I would like to receive up to date new product information and special offers from The Sample Store and the SingPost Group of Companies."));
					}
					
					if($third_party != ''){
						//array
						array_push($array,array('result_id'=>$webform->getId(),'field_id'=>$third_party,'value'=>"I would like to receive up to date, new product information and special offers from external third parties."));
					}
					$write = Mage::getSingleton('core/resource')->getConnection('core_write');
					for($i=0; $i < count($array); $i++){
						$sql = "INSERT INTO `webforms_results_values` (`result_id`, `field_id`, `value`)
						VALUES ({$array[$i]['result_id']}, {$array[$i]['field_id']}, '{$array[$i]["value"]}')";
						$write->query($sql);
					}
					$result['terms_and_condtion'] = true;
				}
				catch (Exception $ex) {
				    //Zend_Debug::dump($ex->getMessage());
				    $result['errors'] = 'Terms and Condition does not save.';
				}
			}
			echo json_encode($result);
		}
		else
		{
			//if not valid ajax request, set a redirect 
			Mage::app()->getResponse()->setRedirect(Mage::getUrl('/'));	
		} 
	}
	
	public function _customerId()
	{
		return Mage::getSingleton('customer/session')->getId();
	}
	
	public function _storeId()
	{
		return Mage::app()->getStore()->getStoreId();
	}
	public function _encrypt($key)
	{
		//return substr(sha1($this->_customerId()), 0, 8);
		if(!empty($key)){
			return md5(uniqid($key, true)).substr(sha1($this->_customerId()), 0, 8);
		}
		else {
			return false;
		}
		
	}
}
