<?php
/**
 * Copyright (C) 2019 Paladin Business Solutions Inc.
 *
 */

if(isset($_POST['s_button'])) {
    check_form_scode();
} else {
    $message = "Provide the data for the new subscriber";    
    show_form_scode($message);    
}    

/* =============== */
/* show_form_scode */
/* =============== */
Function show_form_scode($message, $label = "", $color = "green") {	
    global $print_again, $wpdb;  ?>		
	<form action="" method="post" >
	<table class="TableOverride" >
		<tr class="TableOverride">
            <td colspan="2" align="center">
                <h3>Use the form below to sign up for our news feed:</h3>
            </td>
        </tr>
        
        <tr class="TableOverride">
			<td colspan="2" align="center">
<?php	
	if ($print_again == true) {
		echo "<font color='$color'><strong>" . $message . "</strong></font>";
	} else {
	    echo "<font color='$color'><strong>" . $message . "</strong></font>";
	}	
	?></td>
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
			<input type="submit" value="Submit" name="s_button">			
		</td>
	</tr>
	</table>
	</form>
<?php
}

/* ========== */
/* check_form */
/* ========== */
Function check_form_scode() {
	
	global $print_again, $wpdb;
	
	$full_name  = $_POST['full_name'] ;
	$email      = $_POST['email'] ;	
	
	/* =================================== */
    /* data integrity checks, data massage */
    /* =================================== */        
	
    if ($email == "" ) {        
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
    /* ========================== */
    /* end data integrity checks  */
    /* ========================== */	
    
    //   echo "</br> Trimmed mobile: [" . $mobile ."]";
    
	if ($print_again == true) {
	    show_form_scode($return_message, $label, 'red');
	} else {	    
	    /* ========================== */
	    /* prep for saving the data   */
	    /* ========================== */
	    
	    // if only given an email, check to see if we already have it
	    if ($email != "" ) {
	        $result = $wpdb->get_row( $wpdb->prepare("SELECT `nomad_contacts_id`
                    FROM `nomad_contacts` WHERE `email` = %s",
	            $email )
	            );
	        if ($result->nomad_contacts_id) {
	            $return_message = "<br/><center>That email is already on file.</center>" ;
	        } else {
	           	            
	            // save with name	            
	            $wpdb->query( $wpdb->prepare("INSERT INTO
                        `nomad_contacts` (`full_name`, `email`)
                        VALUES (%s, %s )",
                        $full_name, $email )
	            );	            
	            send_welcome_email($email, $wpdb->insert_id, $full_name );
	            $return_message = "<br/><center>Contact Information saved...the new member will still have to opt-in</center>" ;
	        }
	    }
        $color = "red";	    
        show_form_scode($return_message,'',$color) ;	
	}	    
}
?>