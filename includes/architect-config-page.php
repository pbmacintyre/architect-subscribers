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
    $message = __('Adjust settings to your preferences', 'architect-subscribers') ;
    show_form($message);
} 

/* ========= */
/* show_form */
/* ========= */
Function show_form($message, $label = "", $color = "#008EC2") {	
	global $print_again, $wpdb;    ?>    
     <div class="wrap"> 
    <img id='page_title_img' title="Architect Sample Plugin" src="<?= ARCHITECT_LOGO ;?>">
    <h1 id='page_title'><?= esc_html(get_admin_page_title()); ?></h1>
    
	<form action="" method="post" >
	<table class="TableOverride" >
		<tr class="TableOverride">
			<td colspan="2" align="center">
<?php	
	if ($print_again == true) {
		echo "<font color='$color'><strong>" . $message . "</strong></font>";
	} else {
	    echo "<font color='$color'><strong>" . $message . "</strong></font>";
	}
	
	$architect_result = $wpdb->get_row( $wpdb->prepare("SELECT * FROM `architect_control`
        WHERE `architect_control_id` = %d", 1)
	    );
	?></td>
	</tr>	
	<tr class="TableOverride">
		<td class="left_col">
			<p style='display: inline; <?php if ($label == "email_updates") echo "color:red"; ?>' >Send Post Updates by eMail?</p>			
		</td>
		<td class="right_col"><input type="checkbox" name="email_updates" <?php 
		if ($print_again) { 
		    if ($_POST['email_updates'] == "on") {
		      echo 'CHECKED';
		    } 
          } else {             
              if ($architect_result->email_updates == 1) {
                  echo 'CHECKED' ;
                }
          }
            ?>></td>
	</tr>	
	
	<tr class="TableOverride">
		<td class="left_col">
			<p style='display: inline; <?php if ($label == "token_prefix") echo "color:red"; ?>' >Token Prefix:</p>
            <p style='color: red; display: inline'>*</p> 
             
            <?php echo arch_build_help("token_prefix") ; ?>
                         
		</td>
		<td class="right_col"><input type="text" name="token_prefix" style="width: 25%;" value="<?php 
		  if ($print_again) {
		      echo $_POST['token_prefix'];
          } else {             
              if ($architect_result->token_prefix) {
                  echo $architect_result->token_prefix ;
              } 
          }
            ?>"></td>
	</tr>	
	<tr class="TableOverride">
		<td colspan="2" align="center">			
			<br/>
			<?php 
			$btn_attributes = array( "style" => "background: #008ec2; border-color: #006799; color: #fff;" );
			submit_button("Save Settings","","submit","",$btn_attributes); ?>
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
	
	global $print_again, $wpdb;
	
	$label = "" ;
	$message = "" ;	
	$token_prefix = sanitize_text_field($_POST['token_prefix']);
	
	/* data integrity checks */	
			
	if ($token_prefix == "") {
	    $print_again = true;
	    $label = "token_prefix";
	    $message = "<b>token prefix field cannot be blank.</b>";
	}	

	// end data integrity checking
	
	if ($print_again == true) {
		$color = "red" ;
	    show_form($message, $label, $color);
	} else {	    
	    	   
        if ($_POST['email_updates'] == "on") {
            $email_updates = 1;
        } else {
            $email_updates = 0;
        }
        
        $config_sql = $wpdb->prepare("UPDATE `architect_control` SET
            `email_updates` = %d, `token_prefix` = %s
            WHERE `architect_control_id` = %d",
            $email_updates, $token_prefix, 1) ;
        
        $wpdb->query( $config_sql ); 
        
	    $color = "#53DF00";
	    $message = "Configuration Changes have been saved";	   
	    show_form($message, $label, $color) ;
	}
}
?>