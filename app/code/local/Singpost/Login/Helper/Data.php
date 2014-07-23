<?php
class Singpost_Login_Helper_Data extends Mage_Core_Helper_Abstract
{
 	public function logSurvey()
	{
		//email users if they logged-in 3 times 
		$customer_id = Mage::getSingleton('customer/session')->getId();
		$store_id = Mage::app()->getStore()->getStoreId();
		
		$model = Mage::getModel("singpost_login/profile");
        $count = $model->getUserLogCount($customer_id,$store_id);
		
		$data = $model->getLogsProfileInfo($customer_id);
		//detect if the count is 2, because the new logs is not yet being saved!
		if($count['count'] == 2)
		{
			$templateId = 4;
			$emailTemplate = Mage::getModel('core/email_template')->load($templateId);
			
			//$sender = array('name'  => 'user','email' => 'user@ekmedia.asia');                                
			//$vars = array('customer'=>$customer);
			
			$processedTemplate = $emailTemplate->getProcessedTemplate($vars);
			
			$mail = Mage::getModel('core/email');
			$mail->setToEmail($data['email']);
			$mail->setBody($processedTemplate);
			$mail->setSubject('The Sample Store');
			$mail->setFromName("Sample Store");
			$mail->setType('html');// You can use 'html' or 'text'
			
			try {
			    $mail->send();
			    //Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
			    //$this->_redirect('');
			    //return true;
			}
			catch (Exception $e) {
			    //Mage::getSingleton('core/session')->addError('Unable to send.');
			    //$this->_redirect('');
			    //return false;
			}	
		}
	} 
}