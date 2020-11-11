<?php 

global $wpdb;
$table  = $wpdb->prefix.'sh_contact';
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 10; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM $table" );
$num_of_pages = ceil( $total / $limit );

if (isset($_GET['startDate']) && isset($_GET['enddate']) ) {

	$startDate = $_GET['startDate'];
	$enddate = $_GET['enddate'];

	$sql = "SELECT * FROM $table WHERE $table.timestamp BETWEEN '{$startDate} 00:00:00' AND '{$enddate} 23:59:59' ORDER BY `id` DESC";
	// echo $sql;
}else{
	$sql = "SELECT * FROM $table Order by id desc LIMIT $offset,$limit";
	// echo $sql;
}

$result = $wpdb->get_results( $sql );

if (isset($_GET['export'])) {
	// echo "<pre>";
	// print_r($result);
	// die;
	$file = time().'contact.csv'; 
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="'.$file.'"');
	header( 'Pragma: no-cache' ); // no cache
    header( "Expires: Sat, 26 Jul 1997 05:00:00 GMT" ); // expire date
	

	$fp = fopen('php://output', 'w');
	fputcsv($fp, array('id', 'name', 'email', 'mobile', 'subject', 'message', 'date' ));

	foreach ($result as $contact_data) {
		fputcsv($fp, array($contact_data->id,$contact_data->name,$contact_data->email, $contact_data->mobile, $contact_data->subject, $contact_data->message, $contact_data->timestamp));
	}
	fclose($fp);
	exit;
}

//echo "<pre>";
//print_r($result);
?>

<h2>Contact Form Data</h2>

<form method="get" action="" style="float: right;margin-bottom: 20px;margin-right: 10px;">
        <input type="hidden" name="page" value="sh-contact-form" />    
        <label><strong>From</strong>  </label>
        <input type="date" name="startDate" value="<?php echo $_GET['startDate'] ?>" /> 
        <label><strong>To</strong>  </label>
        <input type="date" name="enddate" value="<?php echo $_GET['enddate'] ?>" /> 
        <input type="submit" name="submit" class="button button-primary" >
        <?php if (isset($_GET['startDate']) && isset($_GET['enddate'])):?>
        <br><br>
         <a class="button button-primary" href="<?php echo site_url() ?>/wp-admin/admin.php?page=sh-contact-form" style="float: right;">Clear </a>
         <?php endif;?>
    </form>
  
    <?php 
    if (isset($_GET['startDate']) && isset($_GET['enddate'])):?>
    	<a href="<?php echo site_url() ?>/wp-admin/admin.php?page=sh-contact-form&startDate=<?php echo isset($_GET['startDate']) ? $_GET['startDate'] : '';?>&enddate=<?php echo isset($_GET['enddate']) ? $_GET['enddate'] : '';?>&export=table&noheader=1" target="_blank">
    		<button class="csv-export button button-primary"> Export</button>
    	</a> 
	<?php else:?>
		<a href="<?php echo site_url() ?>/wp-admin/admin.php?page=sh-contact-form&pagenum=<?php echo $pagenum; ?>&export=table&noheader=1" target="_blank">
			<button class="csv-export button button-primary"> Export</button>
		</a> 
    <?php endif;?>

<table class="wp-list-table widefat fixed striped posts">
	<thead>
		<tr>
			<td width="5%"><label>#SNo</label></td>
			<td width="15%"><label>Name</label></td>
			<td width="10%"><label>Email</label></td>
			<td width="10%"><label>Mobile</label></td>
			<td width="10%"><label>Subject</label></td>
			<td width="40%"><label>Message</label></td>
			<td width="20%"><label>Date & Time</label></td>
		</tr>
	</thead>
	<tbody id="the-list" data-wp-lists="list:post">
		<?php $i = 1;?>
		<?php if(!empty($result)): foreach ($result as $key => $value):?>
			<tr>
				<td><?= $i++; ?></td>
				<td><?= $value->name; ?></td>
				<td><?= $value->email; ?></td>
				<td><?= $value->mobile; ?></td>
				<td><?= $value->subject; ?></td>
				<td><?= $value->message; ?></td>
				<td><?php $time = strtotime($value->timestamp); echo date("d-F-Y h:i:s", $time); ?></td>
			</tr>

		<?php endforeach;?>
		<?php else:?>
			<tr><td colspan="4">No data available</td></tr>
		<?php endif;?>
	</tbody>
</table>


<?php 
	$page_links = paginate_links( array(
	    'base' => add_query_arg( 'pagenum', '%#%' ),
	    'format' => '',
	    'prev_text' => __( '&laquo;', 'text-domain' ),
	    'next_text' => __( '&raquo;', 'text-domain' ),
	    'total' => $num_of_pages,
	    'current' => $pagenum
	) );

if ( $page_links ) {
    echo '<div class="tablenav report_page_liks"><div class="tablenav-pages" style="margin: 1em 0;padding: 10px;">' . $page_links . '</div></div>';
}
?>

<style type="text/css">
	.report_page_liks .page-numbers{
		font-size: 24px;
	}
</style>
