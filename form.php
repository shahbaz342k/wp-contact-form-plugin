<?php 
if (!function_exists('sh_contact_form')) {
	function sh_contact_form(){

		$html = '<div class="container">
					<form method="post" id="frmContact" action="">
						<div class="row">
							<div class="col-sm-6 offset-3">
								<div id="form-status" class="mt-5"></div>
								<div class="form-group">
							        <label><b>Full Name:</b></label>
							        <input type="text" name="name" id="name" class="form-control" />
							    </div>
							    <div class="form-group">
							        <label><b>Email:</b></label>
							        <input type="text" name="email" id="email" class="form-control" />
							    </div>
							    <div class="form-group">
							        <label><b>Mobile:</b></label>
							        <input type="text" name="mobile" id="mobile" class="form-control" />
							    </div>
							    <div class="form-group">
							        <label><b>Subject:</b></label>
							        <input type="text" name="subject" id="subject" class="form-control" />
							    </div>
							    <div class="form-group">
							        <label><b>Message:</b></label>
							        <textarea type="text" name="message" id="message" class="form-control"></textarea>
							    </div>
					    		<input type="submit" name="submit" id="submit" class="mb-5 btn btn-info" value="Submit" />
							</div>
						</div>
					</form>
				</div>';
				return $html;
	}
}
add_shortcode( 'sh_display_contact_form','sh_contact_form' );

?>

