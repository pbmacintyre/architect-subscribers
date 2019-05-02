<?php
/**
 * Copyright (C) 2019 Paladin Business Solutions Inc.
 *
 */

/* ============= */
/*  --- MAIN --- */
/* ============= */
if(isset($_POST['submit'])) {
    check_form();
} else {
    $message = "Provide the data for the new subscriber";
    show_form($message, FALSE);
} 

/* ========= */
/* show_form */
/* ========= */
Function show_form($message, $print_again, $label = "", $color = "green") {	?>		
	<div class="wrap">
    <img id='page_title_img' title="Architect Sample Plugin" src="<?= ARCHITECT_LOGO ;?>">
    <h1 id='page_title'><?= esc_html(get_admin_page_title()); ?></h1>    
    
    <form action="" method="post" >
	<table class="TableOverride" >
	<tr class="TableOverride">
		<td colspan="2" align="center">
          <?php echo "<font color='$color'><strong>" . $message . "</strong></font>";	?>
        </td>
	</tr>	
	<tr class="TableOverride">
		<td class="left_col">
			<p style='display: inline; <?php if ($label == "full_name") echo "color:red"; ?>' >Full Name:</p>
			<p style='color: red; display: inline'>*</p>
		</td>
		<td class="right_col">
			<input type="text" name="full_name" value="<?php if ($print_again) { echo $_POST['full_name']; } ?>">
		</td>
	</tr>
	<tr class="TableOverride">
		<td class="left_col">
			<p style='display: inline; <?php if ($label == "email") echo "color:red"; ?>' >email:</p>
			<p style='color: red; display: inline'>*</p>
		</td>
		<td class="right_col">
			<input type="text" name="email" value="<?php if ($print_again) { echo $_POST['email']; } ?>">
		</td>
	</tr>	
	<tr class="TableOverride">
		<td colspan="2" align="center">			
			<br/>
			<?php 
			$other_attributes = array( "style" => "background: #008ec2; border-color: #006799; color: #fff;" );
			submit_button("Save Settings","","submit","",$other_attributes); ?>
			<br/><br/>
		</td>
	</tr>
	</table>
	</form>
</div>
<?php
}

/* ========== */
/* check_form */
/* ========== */
Function check_form() {
	
	global $wpdb;	
	$print_again = false;

	$full_name  = sanitize_text_field($_POST['full_name']) ;
	$email      = sanitize_email($_POST['email']) ;
		
	/* =================================== */
    /* data integrity checks, data massage */
    /* =================================== */        

	if ($email == "") {
	    $print_again = true;
	    $label = "email";
	    $return_message = "eMail cannot be blank.";
	} 
    if ($email !== "" && filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE){
        $print_again = true;
        $label = "email";
        $return_message = "eMail is malformed";
    }
    if ($full_name == "") { 
        $print_again = true;
        $label = "full_name";
        $return_message = "Full Name cannot be blank."; 
    }  
    if ($full_name == "" && $email == "") {
        $print_again = true;        
        $return_message = "Full Name and eMail cannot both be blank, we need something...";
    }
    // check to see if we already have the email on file
    $result = $wpdb->get_row( $wpdb->prepare("SELECT `architect_contacts_id`
        FROM `architect_contacts` WHERE `email` = %s", $email ) );
    
    if ($result) {
        $print_again = true; 
        $label = "email";
        $return_message = "That email is already on file." ;
    }
    /* ========================== */
    /* end data integrity checks  */
    /* ========================== */	
    
	if ($print_again == true) {
	    show_form($return_message, $print_again,  $label, 'red');
	} else {	    
	    /* ========================== */
	    /* prep for saving the data   */
	    /* ========================== */
	    $uniq_token = arch_unique_token();
	    
        // save with name	            
        $wpdb->query( $wpdb->prepare("INSERT INTO
                `architect_contacts` (`architect_token`, `full_name`, `email`)
                VALUES (%s, %s, %s )",
                $uniq_token, $full_name, $email )
        );        
        
        arch_send_welcome_email($email, $uniq_token, $full_name );
        
        $return_message = "<br/><center>Contact Information saved...the new member will still have to opt-in</center>" ;    	    
        $color = "red";	    
	    show_form($return_message, $print_again,'',$color) ;	
	}	    
}

?>