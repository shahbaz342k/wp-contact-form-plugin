/*contact ajax*/
jQuery(document).ready(function($){
	jQuery('#frmContact').on('submit', function(e){
	   e.preventDefault();
	   jQuery("#form-status").hide();
	   var name = jQuery('#name').val();
	   var email = jQuery('#email').val();
	   var mobile = jQuery('#mobile').val();
	   var subject = jQuery('#subject').val();
	   var message = jQuery('#message').val();
	   jQuery.ajax({
	      url: shcontactAjax.ajaxurl,
	      type: "POST",
		  dataType:'json',
	      data: {
	         action:'set_form',
	         name:name,
	         email:email,
	         mobile:mobile,
	         subject:subject,
	         message:message,
	    },   success: function(response){
				// alert(response.type)
				jQuery("#form-status").show();
				if(response.type == "success"){
					//alert(response.type)
					jQuery("#form-status").show();
					jQuery("#form-status").addClass("success");
					jQuery('#frmContact')[0].reset();
					setTimeout(function() {
	                  location.reload();
	                }, 5000);							
				}
				else if(response.type == "error") {
					//jQuery("#form-status").attr("class","error");				
					jQuery("#form-status").addClass("error");				
				}  
				jQuery("#form-status").html(response.text);	
			},error: function(){} 
	   });
		
		//jQuery('#frmContact')[0].reset();
  	});
});