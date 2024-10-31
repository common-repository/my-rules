<?php 
/**
	 * Plugin Name: My Rules - The Front-End Access Manager
	 * Plugin URI: http://scriptsbundle.com
	 * Description: This plugin allow to manage user roles with post types very effectively.
	 * Version: 1.0
	 * Author: Muhammad Jawad Arshad
	 * Author URI: http://scriptsbundle.com
	 * License: GPL2
**/
 
 // Run on plugin activation
 register_activation_hook( __FILE__, 'myRulesActivation' );
 function myRulesActivation()
 {
	 // settings default options
	update_option('my_rulues_notification_bar_color' , '#1e73be');
	update_option('my_rulues_notification_text_color' , '#ffffff');
	update_option('my_rulues_notification' , "You're not authorized to access this page.");
	update_option('my_rulues_close_color' , '#000000');
	update_option('rules_activate_yes' , 'yes');
	
	delete_option( 'my_rules_redirect_url' );

	global $wpdb;
	$table_name = $wpdb->prefix . "myRules";
	$sql = "CREATE TABLE $table_name (
	  id INTEGER NOT NULL AUTO_INCREMENT,
	  userRole VARCHAR(55) NOT NULL,
	  pid INTEGER ,
	  pType VARCHAR(55) DEFAULT '' NOT NULL,
	  IsAccess VARCHAR(55) DEFAULT '' NOT NULL,
	  PRIMARY KEY (id)
	);";
	// Creating new roleon plugin activation
	add_role('guest_visitors', 'Not logged in Users', array(
    'read' => true, // True allows that capability
    'edit_posts' => false,
    'delete_posts' => false, // Use false to explicitly deny
));
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	foreach (get_editable_roles() as $role_name => $role_info):
		if($role_name=='check all roles') 
		{
			continue; 
		} 
		else
		{
			$post_types = get_post_types(array('public'   => true));
			foreach($post_types as $type):
				$wpdb->insert( 
					$table_name, 
					array( 
						'userRole' => $role_name, 
						'pType' => $type, 
						'IsAccess' => '1'
					), 
					array( 
						'%s',
						'%s', 
						'%d' 
					) 
				);
			endforeach;
		}
	endforeach;
	
	
 }
 
 // Load JS and CSS
add_action( 'admin_enqueue_scripts', 'my_rules_admin_scripts' );
function my_rules_admin_scripts( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/j-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}


//Menu Page
add_action( 'admin_menu', 'myRulesCustomMenu' );

function myRulesCustomMenu(){
    add_menu_page( 'My Rules', 'My Rules', 'manage_options', 'MyRules', 'myRulesCustomMenuHandler'); 
	add_submenu_page( 'MyRules', 'My Rules Settings', 'My Rules Settings', 'manage_options', 'MyRulesSettings', 'myRulesCustomSubMenuHandler' );
}
function myRulesCustomSubMenuHandler()
{
if(isset($_POST['myrules_settings']))
{
		update_option('rules_activate_settings' , $_POST['rules_activate_settings']);
		update_option('rules_activate_yes' , $_POST['rules_activate_yes']);
		update_option('my_rulues_notification_bar_color' , $_POST['my_rulues_notification_bar_color']);
		update_option('my_rulues_notification_text_color' , $_POST['my_rulues_notification_text_color']);
		update_option('my_rulues_notification' , $_POST['my_rulues_notification']);
		update_option('my_rulues_close_color' , $_POST['my_rulues_close_color']);
		update_option('my_rulues_notification_bar_hide' , $_POST['my_rulues_notification_bar_hide']);
}
?>
<form method="post">
       <div style="width:92%;display:table;" class="wrap">
		   <?php
           if(isset($_POST['myrules_settings'])){
            ?>
           <div id="message" class="updated below-h2" style="width:92%;"><p>Settings Updated Successfully.</p></div>
           <?php
           }
           ?>
           <h2>General Settings</h2>
           <hr />
           <input type="submit" name="myrules_settings" value="Update Settings" class="button button-primary button-large">
           <h4>
                <input type="checkbox" id="rules_activate_yes" name="rules_activate_yes" <?php if(esc_attr(get_option('rules_activate_yes'))=='yes') echo 'checked'; ?> value="yes" /> Enable Plugin.
           </h4>
           <h4>
                <input type="checkbox" id="rules_activate_settings" name="rules_activate_settings" <?php if(esc_attr(get_option('rules_activate_settings'))=='yes') echo 'checked'; ?> value="yes" /> Delete all settings on plugin deactivation.
           </h4>
           <h2>Notification Bar Settings</h2>
           <hr />
            <p>
            Notification Bar Color :
            <br />
               <input type="text" value="<?php echo(esc_attr(get_option('my_rulues_notification_bar_color'))); ?>"
                id="my_rulues_notification_bar_color" name="my_rulues_notification_bar_color"  data-default-color="<?php echo(esc_attr(get_option('my_rulues_notification_bar_color'))); ?>" />
            </p>
            <p>
            Notification Bar Text Color :
            <br />
               <input type="text" value="<?php echo(esc_attr(get_option('my_rulues_notification_text_color'))); ?>"
                id="my_rulues_notification_text_color" name="my_rulues_notification_text_color"  data-default-color="<?php echo(esc_attr(get_option('my_rulues_notification_text_color'))); ?>" />
            </p>
            <p>
            Notification Bar Message :
            <br />
               <input type="text" size="150" value="<?php echo(stripslashes_deep(esc_attr(get_option('my_rulues_notification')))); ?>"
                id="my_rulues_notification" name="my_rulues_notification"  data-default-color="<?php echo(esc_attr(get_option('my_rulues_notification'))); ?>" />
            </p>
            <p>
            Notification Bar Close Icon Color :
            <br />
               <input type="text" value="<?php echo(esc_attr(get_option('my_rulues_close_color'))); ?>"
                id="my_rulues_close_color" name="my_rulues_close_color"  data-default-color="<?php echo(esc_attr(get_option('my_rulues_close_color'))); ?>" />
            </p>
            <p>
            Notification Bar Hide After:
            <br />
               <input type="number" style="width:200px;" min="1" max="60" value="<?php echo(stripslashes_deep(esc_attr(get_option('my_rulues_notification_bar_hide')))); ?>"
                id="my_rulues_notification_bar_hide" name="my_rulues_notification_bar_hide"  data-default-color="<?php echo(esc_attr(get_option('my_rulues_notification_bar_hide'))); ?>" /> Seconds
            </p>
            <p>
                <input type="submit" name="myrules_settings" value="Update Settings" class="button button-primary button-large">
            </p>

        </div>
</form>
    <?php
}
function myRulesCustomMenuHandler(){
	global $wpdb;
	$table_name = $wpdb->prefix . "myRules";
if(isset($_POST['total_chks']))
{
		$total	=	$_POST['total_chks'];
	foreach (get_editable_roles() as $role_name => $role_info):
		if($role_name=='check all roles') 
		{
			continue; 
		} 
		else
		{
			$post_types = get_post_types(array('public'   => true));
			foreach($post_types as $type):
				if(isset($_POST[$role_name . '_' . $type]))
				{
					$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pType = '$type' AND userRole = '$role_name'");
					if(isset($row->id))
					{
						$wpdb->update( 
							$table_name,
							array( 
								'userRole' => $role_name, 
								'pType' => $type, 
								'IsAccess' => '1'
							), 
							array( 'id' => $row->id ), 
							array( 
								'%s',
								'%s', 
								'%d' 
							), 
							array( '%d' ) 
						);				
					}
					else
					{
						$wpdb->insert( 
							$table_name, 
							array( 
								'userRole' => $role_name, 
								'pType' => $type, 
								'IsAccess' => '1'
							), 
							array( 
								'%s',
								'%s', 
								'%d' 
							) 
						);
					}	
				}
				else
				{
					$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pType = '$type' AND userRole = '$role_name'");
					if(isset($row->id))
					{
						$wpdb->update( 
							$table_name,
							array( 
								'userRole' => $role_name, 
								'pType' => $type, 
								'IsAccess' => '0'
							), 
							array( 'id' => $row->id ), 
							array( 
								'%s',
								'%s', 
								'%d' 
							), 
							array( '%d' ) 
						);				
					}
					else
					{
						$wpdb->insert( 
							$table_name, 
							array( 
								'userRole' => $role_name, 
								'pType' => $type, 
								'IsAccess' => '0'
							), 
							array( 
								'%s',
								'%s', 
								'%d' 
							) 
						);
					}	
				}
			endforeach;
		}
	endforeach;
}
include('css/style.php');
   ?>
   <form method="post">
   <div style="width:92%;display:table;" class="wrap">
   <?php
   if(isset($_POST['total_chks'])){
	?>
   <div id="message" class="updated below-h2" style="width:92%;"><p>Access Level Updated Successfully.</p></div>
   <?php
   }
   ?>
   <h2>Front-End Access Manager<hr /></h2>
   <p>
      *<em><strong>These rules will be apply on FRONT-END.</strong></em>
   </p>
  <?php
  		$i=0;
		global $wp_roles;
		foreach (get_editable_roles() as $role_name => $role_info):

  ?>
  <?php 	if($role_name=='check all roles') { continue; } // leave all checks if current user is admin.
  			else
			{
			?>
            <div style="width:350px;float:left;margin-top:10px;">
            
            <?php
				$post_types = get_post_types(array('public'   => true));
				echo '<h3 style="margin-bottom:-5px;"><input type="checkbox" id="' . $role_name . '" onclick="return selectAllCPT(' . count($post_types) . ',' . "'" . $role_name . "'" . ');" /><label class="class_label" for="' .$role_name . '">' . strtoupper($wp_roles->roles[$role_name]['name']) . '</label></h3> <br />'; 
				
				$count	=	1;
				$count_checked	=	1;
				foreach($post_types as $type):
				$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pType = '$type' AND userRole = '$role_name' AND isAccess='1' AND pid IS NULL");
				$checked	=	'';
				if(isset($row->id))
				{
					$checked	=	'checked="checked"';
					$count_checked++;
				}
				?>
                <div class="checkbox">
                	<input type="checkbox" id="<?php echo $role_name . '_' . $count; ?>" name="<?php echo $role_name . '_' . $type; ?>" <?php echo $checked; ?>  value="<?php echo($role_name . '|' . $type); ?>" />
                    <label class="class_label" for="<?php echo $role_name . '_' . $count; ?>">
						<?php echo '<strong>' . strtoupper($role_name) . '</strong><small> can access </small>'; ?>
						<?php echo('<strong>' .strtoupper($type) . '(s)</strong>?'); ?>
                    </label>
                    
                </div>
                <?php
				$count++;
				$i++;
				endforeach;
				$total_checked	=	$count_checked-1;
				if(count($post_types)==$total_checked)
				{
				?>
                	<script type="text/javascript">
						document.getElementById('<?php echo $role_name; ?>').checked	=	true;
					</script>
			<?php
				}
			}
			?>
            </div>
            <?php
		 endforeach; 
  ?>
  </div>
  <br />
  <input type="submit" value="Update Permission(s)" class="button button-primary button-large">
  <input type="hidden" value="<?php echo($i); ?>" name="total_chks" />
  </form>
  <script type="text/javascript">
  	function selectAllCPT(count,role)
	{
	if(document.getElementById(role).checked)
	{
		for(var i=1; i<=count; i++)
		{
			document.getElementById(role + '_' + i).checked	=	true;
		}
	}
	else
	{
		for(var i=1; i<=count; i++)
		{
			document.getElementById(role + '_' + i).checked	=	false;
		}
	}
	}
  </script>
<?php
}
// Add action for check permission
add_action('wp' , 'myRulesCheckPermission');
function myRulesCheckPermission($contents)
{
	if(esc_attr(get_option('rules_activate_yes'))!='yes')
		return $contents;
	if(is_singular())
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "myRules";
		$redirect_url	=	get_site_url();
		if(is_user_logged_in())
		{
			$current_user = wp_get_current_user();
			if ( !($current_user instanceof WP_User) )
			return;
			$roles = $current_user->roles;  //$roles is an array
			$role_name	=	$roles['0'];
			if($role_name == 'check all roles')
			{
			}
			else
			{
				if(@get_the_ID()!="")
				{
					$post_id	=	get_the_ID(); 
					$post_type	=	get_post_type($post_id);
					$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pType = '$post_type' AND userRole = '$role_name'
					 AND IsAccess=0 AND pid IS NULL");
					if(isset($row->id))
					{
						$_SESSION['access_denied']	=	'not_authorized';
						wp_redirect( $redirect_url ); exit;
					}
					else
					{
						$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = '$post_id' AND userRole = '$role_name'
						 AND IsAccess=0");
						if(isset($row->id))
						{
							$_SESSION['access_denied']	=	'not_authorized';
							wp_redirect( $redirect_url ); exit;
						}
					}
				}
			}
		}
		else
		{
			if(@get_the_ID()!="")
			{
				$post_id	=	get_the_ID(); 
				$post_type	=	get_post_type($post_id);
				$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pType = '$post_type' AND IsAccess=0 AND userRole = 'guest_visitors' AND pid IS NULL");
				if(isset($row->id))
				{
					$_SESSION['access_denied']	=	'not_authorized';
					wp_redirect( $redirect_url ); exit;
				}
				else
				{
					$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = '$post_id' AND userRole = 'guest_visitors' AND IsAccess=0");
					if(isset($row->id))
					{
						$_SESSION['access_denied']	=	'not_authorized';
						wp_redirect( $redirect_url ); exit;
					}
				}
			}
		}
	}
	return $contents;
	}
 
 // register the meta box
add_action( 'add_meta_boxes', 'myRulesMetaBox' );
function myRulesMetaBox($postType) {
    add_meta_box(
        'my_meta_box_id',          // this is HTML id of the box on edit screen
        'Check for disbale access of particular role on this ' . $postType,    // title of the box
        'myRuleBoxContent',   // function to be called to display the checkboxes, see the function below
       $postType,        // on which edit screen the box should appear
        'normal',      // part of page where the box should appear
        'default'      // priority of the box
    );
}

// display the metabox
function myRuleBoxContent(  ) {
	global $wpdb;
	global $wp_roles;
	$table_name = $wpdb->prefix . "myRules";
    // nonce field for security check, you can have the same
    // nonce field for all your meta boxes of same plugin
	@$post_id	=	$_GET['post'];
	if(isset($post_id))
	{
		foreach (get_editable_roles() as $role_name => $role_info):
			if($role_name=='check all roles') { continue; } 
			else
			{
				$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = '$post_id' AND userRole = '$role_name' AND IsAccess=0");
				$checked	=	'';
				if(isset($row->id))
				{
					$checked	=	'checked="checked"';
				}
			?>
				<input type="checkbox" name="<?php echo($role_name); ?>" <?php echo($checked); ?> value="<?php echo($role_name); ?>" />
                <?php echo($wp_roles->roles[$role_name]['name']); ?>
                <br />
			<?php
			
			}
		endforeach;
	}
	else
	{
		foreach (get_editable_roles() as $role_name => $role_info):
			if($role_name=='check all roles') { continue; } 
			else
			{
			?>
    <input type="checkbox" name="<?php echo($role_name); ?>" value="<?php echo($role_name); ?>" />
	<?php echo($wp_roles->roles[$role_name]['name']); ?>
    <br />
    <?php
			}
		endforeach;
	}
}

// save data from checkboxes
add_action( 'init', 'myRulesCreateMetaBoxes');
function myRulesCreateMetaBoxes() 
{
    // types will be a list of the post type names
    $types = get_post_types(array('public' => true));

    // get the registered data about each post type with get_post_type_object
    foreach( $types as $type )
    {
		add_action("publish_" . $type, 'myRulesUpdateAccess' );
    }
}
	
function myRulesUpdateAccess($post_id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . "myRules";
	$post_type	=	get_post_type($post_id);
    // now store data in custom fields based on checkboxes selected
		foreach (get_editable_roles() as $role_name => $role_info):
			if($role_name=='check all roles') { continue; } 
			else
			{
				if($_POST[$role_name])
				{
					$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = '$post_id' AND userRole = '$role_name'");
					if(isset($row->id))
					{
						$wpdb->update( 
							$table_name,
							array( 
								'userRole' => $role_name, 
								'pType' => $post_type, 
								'pid' => $post_id, 
								'IsAccess' => '0'
							), 
							array( 'id' => $row->id ), 
							array( 
								'%s',
								'%s', 
								'%d', 
								'%d' 
							), 
							array( '%d' ) 
						);				
					}
					else
					{
						$wpdb->insert( 
							$table_name, 
							array( 
								'userRole' => $role_name, 
								'pType' => $post_type, 
								'pid' => $post_id, 
								'IsAccess' => '0'
							), 
							array( 
								'%s',
								'%s', 
								'%d', 
								'%d' 
							) 
						);
					}	
				}
				else
				{
					$row = $wpdb->get_row("SELECT * FROM $table_name WHERE pid = '$post_id' AND userRole = '$role_name'");
					if(isset($row->id))
					{
						$wpdb->update( 
							$table_name,
							array( 
								'userRole' => $role_name, 
								'pType' => $post_type, 
								'pid' => $post_id, 
								'IsAccess' => '1'
							), 
							array( 'id' => $row->id ), 
							array( 
								'%s',
								'%s', 
								'%d', 
								'%d' 
							), 
							array( '%d' ) 
						);				
					}
				}
			}
	   endforeach;
}

// Get current page URL
if(!function_exists('curPageURL'))
{
	function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
}

// Access denied bar notification bar
add_action( 'init', 'showNotificationBar');
function showNotificationBar()
{ 
	session_start();
if($_SESSION['access_denied']!='')
{
	unset($_SESSION['access_denied']);
	if(esc_attr(get_option('rules_activate_yes'))!='yes')
	{}
	else
	{
	include('css/style.php');
	wp_enqueue_script( 'MyRules_JS', plugins_url('js/all.js', __FILE__));
?>
<div id="wpNotificationBar">
    <?php echo(stripslashes_deep(esc_attr(get_option('my_rulues_notification')))); ?> 
    <a id="closeThisBar" class="myrules_close">&times;</a>
</div>
<?php
add_action('wp_head', 'myRules_header_code');
function myRules_header_code()
{	
?>
<?php
	if(esc_attr(get_option('my_rulues_notification_bar_hide'))!=0)
	{
		$time	=	esc_attr(get_option('my_rulues_notification_bar_hide')) . '000';
		?>
        	<script type="text/javascript">
				jQuery(document).ready(function($){
					setTimeout(function() {
      					$('#wpNotificationBar').fadeOut(3000);
					}, <?php echo $time; ?>);
				});				
			</script>
        <?php
	}
}
	}
}
}
// On Deavtivation 
register_deactivation_hook( __FILE__, 'myRulesDeactivate' );
function myRulesDeactivate()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "myRules";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
	if(esc_attr(get_option('rules_activate_settings'))=='yes')
	{
		remove_role( 'guest_visitors' );
		delete_option( 'my_rules_redirect_url' );
		delete_option( 'rules_activate_yes' );
		delete_option( 'rules_activate_settings' );
		
		delete_option('my_rulues_notification_bar_color');
		delete_option('my_rulues_notification_text_color');
		delete_option('my_rulues_notification');
		delete_option('my_rulues_close_color');
		delete_option('my_rulues_notification_bar_hide');
	}

}

function redirect_home( $redirect_to, $request, $user )
{
    return $redirect_to;
}
add_filter( 'login_redirect', 'redirect_home' );
 ?>