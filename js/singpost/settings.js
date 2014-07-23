//jQuery.noConflict();
jQuery(document).ready(function($){
	//this is the event for the fancybox
	$(document).on('click','.edit_pop',function(){
	    $.fn.custombox(this, {
	        effect:         'blur',
	        position:       'center',
	        customClass:    'customslide',
	        speed:          200
	    });
	    e.preventDefault();
	});
	
	$(document).on('click','.close',function(){
		//e.preventDefault();
		$.fn.custombox('close');
	});

	$(document).on('click','#updatePassword',function(){
		$("#frmPassword").validate({
			rules: 
			{
				current_password: {required: true},
				new_password: {required: true, maxlength:100, minlength:8},
				confirm_password: {required: true, maxlength:100, minlength:8, equalTo : "#new_password"},
			},
			messages: 
			{
				current_password: "Please Specify Your Current Password",
				new_password: 
				{
					required: "Please Specify Your Current Password",
					maxlength: "The Maximum Length of the Password is 100 characters",
					minlength: "The Minimum Length of the Password is 8"
				},
				confirm_password:
				{
					required: "Please Enter Your Confirm Password",
					maxlength: "The Maximum Length of the Password is 100 characters",
					minlength: "The Minimum Length of the Password is 8",
					equalTo: "The Password you enter is not equal"
				}
			},submitHandler: function(){changePassword();}
		});// $("#frmPassword").validate({
		function changePassword()
		{
			var current_password = $("#current_password").val();
			var new_password = $("#new_password").val();
			var confirm_password = $("#confirm_password").val();
			//call an ajax function
			$.ajax({
			    url: '/profile/action/changePassword',
			    type: "post",
			    data:{current_password:current_password,new_password:new_password,confirm_password:confirm_password},
			    dataType: 'json',
			    success: function(data) {
					if(data.result == 'error'){
						var length = data.message.length;
						var i;
						for(i = 0; i<length; i++)
						{
							$("#error_msg").show().text(data.message[i]);
							setTimeout(function() {
								$.fn.custombox('close');
							}, 2000);
						}
					}//if(data.result == 'error')
					else
					{	
						$("#error_msg").show().text(data.message);
						//close the form
						setTimeout(function() {
							$.fn.custombox('close');
						}, 2000);
					}
					window.location.href = "/profile/index/settings/";
					//$(".profile-index-settings").load("settingsAjaxLoad");
			    }//end  success: function(data) {
			});//end of ajax*/	
		} 	
	});
		
	//edit email
	$(document).on('click','#updateEmail',function(){
		$("#frmEmail").validate({
			rules: 
			{
				password: {required: true},
				email_address :{required:true,email: true}
			},
			messages: 
			{
				password: "Please Specify Your Current Password",
				email_address:{
					required : "Please Specify Your New Email Address",
					email : "Please Input Valid Email Address"
				}
			},submitHandler: function(){changeEmail();}
		});// $("#frmEmail").validate({
		function changeEmail()
		{
			$("#popup").show();
			var password = $("#user_password").val();
			var email_address = $("#email_address").val();
			$.ajax({
			    url: '/profile/action/changeEmailPost',
			    type: "post",
			    data:{password:password,email_address:email_address},
			    dataType: 'json',
			    success: function(data) {
			    	$("#popup").hide();
					if(data.result == 'error'){
						var length = data.message.length;
						var i;
						for(i = 0; i<length; i++)
						{
							$("#error_msg").show().text(data.message[i]);
							setTimeout(function() {
								$.fn.custombox('close');
							}, 2000);
						}
					}//if(data.result == 'error')
					else
					{	
						$("#error_msg").show().text(data.message);
						//close the form
						setTimeout(function() {
							$.fn.custombox('close');
						}, 2000);
					}
					window.location.href = "/profile/index/settings/";
					//$(".profile-index-settings").load("settingsAjaxLoad");
			    }//end  success: function(data) {
			});//end of ajax*/
		}
	});
	//edit mobile
	$(document).on('click','#updateMobile',function(){
		$("#frmMobile").validate({
			rules: 
			{
				mobile_num: {required: true,maxlength:8, minlength:8,number: true},
			},
			messages: 
			{
				mobile_num:{
					required : "Please Specify Your New Mobile Number",
					maxlength : "Please put your 8 digit mobile number",
					minlength : "Please put your 8 digit mobile number",
					number : "Please Enter Number Character Only"
				}
			},submitHandler: function(){changeMobile();}
		});// $("#frmEmail").validate({	
		function changeMobile()
		{
			var mobile_num = $("#mobile_num").val();
			$.ajax({
			    url: '/profile/action/changeMobilePost',
			    type: "post",
			    data:{mobile_num:mobile_num},
			    dataType: 'json',
			    success: function(data) {
					if(data.result == 'error'){
						$(".clearfix").hide();
						var length = data.message.length;
						var i;
						for(i = 0; i<length; i++)
						{
							$("#error_msg").show().text(data.message[i]);
							setTimeout(function() {
								$.fn.custombox('close');
							}, 2000);
						}
					}//if(data.result == 'error')
					else
					{	
						$("#error_msg").show().text(data.message);
						//close the form
						setTimeout(function() {
							$.fn.custombox('close');
						}, 2000);
					}
					window.location.href = "/profile/index/settings/";
					//$(".profile-index-settings").load("settingsAjaxLoad");
			    }//end  success: function(data) {
			});//end of ajax*/
		}
	});
	//edit country
	var countryFlag = 2;
	$(document).on('click','#others',function(){
		//uncheck the dummy and orig
		$("input[name=country_value]").prop('checked', false);
		//show dropdown and the dummy
		$("#country_dropdown").show();
		$("input[type=radio]").removeClass('error');
		countryFlag = 0;
	});
	$(document).on('click','input[name=country_value]',function(){
		//hide the dummy and display the original
		//uncheck the others and hide the dropdown
		var val = $(this).val();
		$("#others").prop('checked', false);
		$("#country_dropdown").css({ display: "none" });
		$('#country').val(val);
		$("input[type=radio]").removeClass('error');
		countryFlag = 1;
	});
	
	$(document).on('click','#updateCountry',function(e){
		$(".accountError").remove();
		if(countryFlag == 2){
			$("input[type=radio]").addClass('error');
			$(".row").eq(1).before($("<p class='accountError'>Please specify your Country.</p>"));
			return false;
		}
		else if(countryFlag == 1){
			changeCountry();
			return false;
		}
		else{
			$("#frmCountry").validate({
				rules: 
				{
					country: {required: true},
				},
				messages: 
				{
					mobile_num:"Please Specify Your Country"
				},submitHandler: function(){changeCountry();}
			});// $("#frmEmail").validate({		
		}
	});
	function changeCountry()
	{
		var country = $("#country").val();
		$.ajax({
		    url: '/profile/action/changeCountryPost',
		    type: "post",
		    data:{country:country},
		    dataType: 'json',
		    success: function(data) {	
				if(data.result == 'error'){
					var length = data.message.length;
					var i;
					for(i = 0; i<length; i++)
					{
						$("#error_msg").show().text(data.message[i]);
						setTimeout(function() {
							$.fn.custombox('close');
						}, 2000);
					}
				}//if(data.result == 'error')
				else
				{	
					$("#error_msg").show().text(data.message);
					//close the form
					setTimeout(function() {
						$.fn.custombox('close');
					}, 2000);
				}
				window.location.href = "/profile/index/settings/";
				//$(".profile-index-settings").load("settingsAjaxLoad");
		    }//end  success: function(data) {
		});//end of ajax*/
	}
	
	//edit shipping
	$(document).on('click','#updateShipping',function(e){
		$("#frmShipping").validate({
			rules: 
			{
				shipping_address: {required: true, maxlength:250},
			},
			messages: 
			{
				shipping_address:{
					required:"Please Specify Your Shipping Address.",
					maxlength: "You have exceeded on the maximum of 250 characters."
				}
			},submitHandler: function(){changeAddress();}
		});// $("#frmEmail").validate({
		function changeAddress()
		{
			var address = $("#shipping_address").val();
			$.ajax({
			    url: '/profile/action/changeAddressPost',
			    type: "post",
			    data:{address:address},
			    dataType: 'json',
			    success: function(data) {
					if(data.result == 'error'){
						var length = data.message.length;
						var i;
						for(i = 0; i<length; i++)
						{
							$("#error_msg").show().text(data.message[i]);
							setTimeout(function() {
								$.fn.custombox('close');
							}, 2000);
						}
					}//if(data.result == 'error')
					else
					{	
						$("#error_msg").show().text(data.message);
						//close the form
						setTimeout(function() {
							$.fn.custombox('close');
						}, 2000);
					}
					window.location.href = "/profile/index/settings/";
					//$(".profile-index-settings").load("settingsAjaxLoad");
			    }//end  success: function(data) {
			});//end of ajax*/	
		}
	});
	//edit newsletter
	$(document).on('click','#updateNewsletter',function(e){
		var special_offers = $("#special_offers").is(':checked') ? $("#special_offers").val() : '';
		var new_product_information = $("#new_product_info").is(':checked') ? $("#new_product_info").val() : '';
		var result_id = $("#result_id").val();
		//ajax function
		$.ajax({
		    url: '/profile/action/updateNewsLetterPost',
		    type: "post",
		    data:{special_offers:special_offers,new_product_information:new_product_information,result_id:result_id},
		    dataType: 'json',
		    success: function(data) {
				if(data.result == 'error'){
					$(".clearfix").hide();
					var length = data.message.length;
					var i;
					for(i = 0; i<length; i++)
					{
						$("#error_msg").show().text(data.message[i]);
						setTimeout(function() {
							$.fn.custombox('close');
						}, 2000);
					}
				}//if(data.result == 'error')
				else
				{	
					$("#error_msg").show().text(data.message);
					//close the form
					setTimeout(function() {
						$.fn.custombox('close');
					}, 2000);
				}
				window.location.href = "/profile/index/settings/";
				//$(".profile-index-settings").load("settingsAjaxLoad");
		    }//end  success: function(data) {
		});//end of ajax*/	
	});
});//end of document ready

   