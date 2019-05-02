<?php
/**
 * Copyright (C) 2019 Paladin Business Solutions Inc.
 *
 */
class arch_contacts_widget extends WP_Widget {
    // instantiate the class
    function __construct() {
        $widget_ops = array(
          'classname' => 'architect_contacts_widget_class',
          'description' => 'Collect contact information When signing up for new Post Notifications.'
        );
        $this->WP_Widget(
            'architect_contacts_widget',
            'Architect Contacts Widget', // display text on widgets page
            $widget_ops );
    }
    
    // build the widget settings form
    function form($instance) {
        $defaults = array('title' => 'News Feed Sign Up' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = $instance['title'];    ?>
        <p>Title: <input type="text" class="widefat"
        	name="<?php echo $this->get_field_name( 'title' ); ?>" 
        	value="<?php echo esc_attr($title) ; ?>" /></p>      
<?php }
    // save widget settings function on admin widget
    function update($new_instance, $old_instance) {
        $instance = $old_instance ;
        $instance['title'] = strip_tags($new_instance['title']) ;
        return $instance ;        
    }
    
    // display the widget
    function widget ($args, $instance) {        
        extract($args);
        echo $before_widget;
        $title = apply_filters ('widget_title', $instance['title']);        
        if (!empty($title) ) { echo $before_title . $title . $after_title; } ;
       
         ?> 
        <form action="" method="post" >  
              		
        <p> Full Name: <input type="text" class="widefat" name="full_name" value="" /></p>
                                
        <p> eMail: <input type="text" class="widefat" name="email" value="" /></p>            
                 
		<br/><br/>		
		<input type="submit" name="submit" value="Join List" style="background: #008ec2; border-color: #006799; color: #fff;">		
        </form>
<?php           
        // check that the form was submitted
        if(isset($_POST['submit']) && $_POST['submit'] == "Join List" ) {
            echo $this->public_save($_POST);
        }
        
        echo $after_widget ;  
    }
    /* ===================== */
    /* save public form data */
    /* ===================== */
    function public_save($FormData) {
        global $wpdb;
        $contacts_widget_return_message = "";
        
        $full_name  = sanitize_text_field($FormData['full_name']) ;
        $email      = sanitize_email($FormData['email']) ;
                        
        /* =================================== */
        /* data integrity checks, data massage */
        /* =================================== */        
    	    	
        if ($email == "") {        
            $print_again = true;
            $label = "email";
            $contacts_widget_return_message = "eMail cannot be blank.";
        }
        if ($email !== "" && filter_var($email, FILTER_VALIDATE_EMAIL) == FALSE){
            $print_again = true;
            $label = "email";
            $contacts_widget_return_message = "eMail is malformed";
        }        
        if ($full_name == "") { 
            $print_again = true;
            $label = "full_name";
            $contacts_widget_return_message = "Full Name cannot be blank."; 
        }    
        /* ========================= */
        /* end data integrity checks */
        /* ========================= */	
        if (!$print_again) {
            
            /* ======================== */
            /* prep for saving the data */
            /* ======================== */
                       
            // if only given an email, check to see if we already have it
            if ($email != "") {                
                $result = $wpdb->get_row( $wpdb->prepare("SELECT `architect_contacts_id` 
                    FROM `architect_contacts`
                    WHERE `email` = %s",
                    $email )
                    );       
                if ($result->architect_contacts_id) {
                    $contacts_widget_return_message = "<br/><center>That email is already on file.<br/>Thanks for previously joining us!</center>" ;              
                } else {
                    // save with name
                    $wpdb->query( $wpdb->prepare("INSERT INTO 
                        `architect_contacts` (`full_name`, `email`) 
                        VALUES (%s, %s )",
                        $full_name, $email ) 
                    );            
                    send_welcome_email($email, $wpdb->insert_id, $full_name );                   
                    $contacts_widget_return_message = "<br/><center><font color='green'>Contact Information saved...<br/>Check your email to confirm<br/>Thanks for joining us!</font></center>" ;                    
                }           
            }  //end if already exists test        
        } else {
            // end data intergity fail tests
            $contacts_widget_return_message = "<br/><center><font color='red'>" . $contacts_widget_return_message . "</font></center>";            
        }
        
        // show message on widget area
        return $contacts_widget_return_message ;
    } // end public_save method
    
} // end of class definition
?>