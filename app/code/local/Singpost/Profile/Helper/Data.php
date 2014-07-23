<?php
class Singpost_Profile_Helper_Data extends Mage_Core_Helper_Abstract
{
 	public function sendNewEmailVerification($email,$verification)
	{
			$templateId = 4;
			$emailTemplate = Mage::getModel('core/email_template')->load($templateId);
			$verification_link = Mage::getBaseUrl()."/profile/activation/email/key/$verification";
			$mail = Mage::getModel('core/email');
			$mail->setToEmail($email);
			$mail->setBody($verification_link);
			$mail->setSubject('The Sample Store');
			$mail->setFromName("Sample Store");
			$mail->setType('html');// You can use 'html' or 'text'
			try {
			    $mail->send();
				return true;
			}
			catch (Exception $e) {
				return $e;
			}	
	} 
}