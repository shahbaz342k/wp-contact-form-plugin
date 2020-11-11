<?php 

// THE AJAX ADD ACTIONS
add_action( 'wp_ajax_set_form', 'set_form' );    //execute when wp logged in
add_action( 'wp_ajax_nopriv_set_form', 'set_form'); //execute when logged out

function set_form(){

	if ($_POST) {
		$name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
		$email =filter_var($_POST["email"], FILTER_SANITIZE_STRING);
		$mobile =filter_var($_POST["mobile"], FILTER_SANITIZE_STRING);
		$subject =filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
		$message =filter_var($_POST["message"], FILTER_SANITIZE_STRING);

		if(empty($name)) {
			$empty[] = "<b>Name</b>";		
		}
		if(empty($email)) {
			$empty[] = "<b>Email</b>";
		}
		if(empty($mobile)) {
			$empty[] = "<b>Mobile</b>";
		}
		
		if(empty($message)) {
			$empty[] = "<b>Message</b>";
		}
		if(!empty($empty)) {
			$output = json_encode(array('type'=>'error', 'text' => implode(", ",$empty) . ' fields Required!')); // convert $empty array into string separte with , 
        	die($output);
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ //email validation
		    $output = json_encode(array('type'=>'error', 'text' => '<b>'.$email.'</b> is an invalid Email, please correct it.'));
			die($output);
		}

		if(preg_match('/^\d{10}$/',$mobile)) { // phone number is valid
	      $mobile = $mobile;
	    }
	    else { // phone number is not valid
	     	$output = json_encode(array('type'=>'error', 'text' => '<b>'.$mobile.'</b> is an invalid Mobile, please correct it.'));
			die($output);
	    }

		global $wpdb;
		$table_name = 'sh_contact';
		$table = $wpdb->prefix.$table_name;

		$data = [
		    'name' => $name,
		    'email' => $email,
		    'mobile' => $mobile,
		    'subject' => $subject,
		    'message' => $message,
		    'timestamp' => date("Y-m-d h:i:s"),
		];
		//$format = array('%s','%s','%s');
		$wpdb->insert($table,$data);
		$my_id = $wpdb->insert_id;
		//var_dump($run);
		if ($my_id) {		
		    $output = json_encode( array('type'=>'success', 'text' => '<b>Hi '.$name.'</b>, thank you for contacting us') );
		    die($output);
		} else {
		    $output = json_encode(array('type'=>'error', 'text' => 'Unable to send email, please contact'));
		    die($output);
		}
	}	

	//$admin =get_option('admin_email');
	
	// // wp_mail($email,$name,$message);  main sent to admin and the user
	// if(wp_mail($email, $name, $message)  &&  wp_mail($admin, $name, $message) )
 //       {
 //           echo "mail sent";
 //   	}else {
 //          echo "mail not sent";
 //   	}
	die();

}