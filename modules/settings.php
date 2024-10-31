<?php 

 
class vooSettingsClassMainCSV{
	
	var $setttings_parameters;
	var $settings_prefix;
	var $message;
	
	function __construct( $prefix ){
		$this->setttings_prefix = $prefix;	
		
		if(  wp_verify_nonce($_POST['save_settings_field'], 'save_settings_action') ){
			$options = array();
			foreach( $_POST as $key=>$value ){
				$options[$key] = sanitize_text_field( $value );
			}
			update_option( $this->setttings_prefix.'_options', $options );
			
			$this->message = '<div class="alert alert-success">Settings saved</div>';
			
		}
	}
	
	function get_setting( $setting_name ){
		$inner_option = get_option( $this->setttings_prefix.'_options');
		return $inner_option[$setting_name];
	}
	
	function create_menu( $parameters ){
		$this->setttings_parameters = $parameters;		
			
		add_action('admin_menu', array( $this, 'add_menu_item') );
		
	}
	
	 
	
	
	function add_menu_item(){
		
		foreach( $this->setttings_parameters as $single_option ){
			if( $single_option['type'] == 'menu' ){
				add_menu_page(  			 
				$single_option['page_title'], 
				$single_option['menu_title'], 
				$single_option['capability'], 
				$single_option['menu_slug'], 
				array( $this, 'show_settings' ) 
				);
			}
			if( $single_option['type'] == 'submenu' ){
				add_submenu_page(  
				$single_option['parent_slug'],  
				$single_option['page_title'], 
				$single_option['menu_title'], 
				$single_option['capability'], 
				$single_option['menu_slug'], 
				array( $this, 'show_settings' ) 
				);
			}
			if( $single_option['type'] == 'option' ){
				add_options_page(  				  
				$single_option['page_title'], 
				$single_option['menu_title'], 
				$single_option['capability'], 
				$single_option['menu_slug'], 
				array( $this, 'show_settings' ) 
				);
			}
		}
		 
	}
	
	function show_settings(){
		?>
		<div class="wrap tw-bs4">
		
		
		
		<h2><?php _e('Settings', 'sc'); ?></h2>
		<hr/>
		<?php 
			echo $this->message;
		?>
		
		<form class="form-horizontal" method="post" action="">
			
		<?php 
		wp_nonce_field( 'save_settings_action', 'save_settings_field'  );  
		$config = get_option( $this->setttings_prefix.'_options'); 
		?>  
		<fieldset>

			<?php 
		foreach( $this->setttings_parameters as $single_page ){	
			foreach( $single_page['parameters'] as $key=>$value ){
				switch( $value['type'] ){
					case "separator":
						$out .= '
						<div class="lead">'.$value['title'].'</div> 
						';
					break;
					case "text":
						$out .= '
						<div class="form-group">  
							<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
							
							  <input type="text"  class="form-control '.$value['class'].'"  name="'.$value['name'].'" id="'.$value['id'].'" placeholder="'.$value['placeholder'].'" value="'.esc_html( stripslashes( $config[$value['name']] ) ).'">  
							  <p class="help-block">'.$value['sub_text'].'</p>  
							
						  </div> 
						';
					break;
					case "button":
						$out .= '
						<div class="form-group">  
							<label class="control-label" for="">&nbsp;</label>  
							
							  <a class="btn btn-success" href="'.$value['href'].'"   >'.$value['title'].'</a>  
							  
							
						</div> 
						';
					break;
					case "select":
						$out .= '
						<div class="form-group">  
							<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
							 
							  <select  style="'.$value['style'].'" class="form-control '.$value['class'].'" name="'.$value['name'].'" id="'.$value['id'].'">' ; 
							  if( count( $value['value'] ) > 0 )
							  foreach( $value['value'] as $k => $v ){
								  $out .= '<option value="'.$k.'" '.( $config[$value['name']]  == $k ? ' selected ' : ' ' ).' >'.$v.'</option> ';
							  }
						$out .= '		
							  </select>  
							  <p class="help-block">'.$value['sub_text'].'</p> 
							</div>  
						 
						';
					break;
					case "checkbox":
						$out .= '
						<div class="form-group">  
							<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
						
							  <label class="checkbox">  
								<input  class="'.$value['class'].'" type="checkbox" name="'.$value['name'].'" id="'.$value['id'].'" value="on" '.( $config[$value['name']] == 'on' ? ' checked ' : '' ).' > &nbsp; 
								'.$value['text'].'  
								<p class="help-block">'.$value['sub_text'].'</p> 
							  </label>  
							 
						  </div>  
						';
					break;
					case "radio":
						$out .= '
						<div class="form-group">  
							<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>';
								foreach( $value['value'] as $k => $v ){
									$out .= '
									<label class="radio">  
										<input  class="'.$value['class'].'" type="radio" name="'.$value['name'].'" id="'.$value['id'].'" value="'.$k.'" '.( $config[$value['name']] == $k ? ' checked ' : '' ).' >&nbsp;  
										'.$v.'  
										<p class="help-block">'.$value['sub_text'].'</p> 
									  </label> ';
								}
							$out .= '
							
						  </div>  
						';
					break;
					case "textarea":
						$out .= '
						<div class="form-group">  
							<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
						
							  <textarea style="'.$value['style'].'" class="form-control '.$value['class'].'" name="'.$value['name'].'" id="'.$value['id'].'" rows="'.$value['rows'].'">'.esc_html( stripslashes( $config[$value['name']] ) ).'</textarea>  
							  <p class="help-block">'.$value['sub_text'].'</p> 
						 
						  </div> 
						';
					break;
					case "multiselect":
						$out .= '
						<div class="form-group">  
							<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
							 
							  <select  multiple="multiple" style="'.$value['style'].'" class="form-control '.$value['class'].'" name="'.$value['name'].'[]" id="'.$value['id'].'">' ; 
							  foreach( $value['value'] as $k => $v ){
								  $out .= '<option value="'.$k.'" '.( @in_array( $k, $config[$value['name']] )   ? ' selected ' : ' ' ).' >'.$v.'</option> ';
							  }
						$out .= '		
							  </select>  
							  <p class="help-block">'.$value['sub_text'].'</p> 
							 
						  </div>  
						';
					break;
					case "wide_editor":
					$out .= '<div class="form-group">  
						<label class="control-label" for="input01">'.$value['title'].'</label>
						<div class="form-control1">
						';  
						 
						ob_start();
						wp_editor( $config[$value['name']], $value['name'] );
						$editor_contents = ob_get_clean();	
					 
						$out .= $editor_contents;  
					$out .= '
						</div>
					  </div> ';	 
					 
					break;
					case "file":
						$out .= '
						<div class="form-group">  
							<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
				 
							<input type="file" class="form-control-file '.$value['class'].'" name="'.$value['name'].''.( $value['multi'] ? '[]' : '' ).'" id="'.$value['id'].'" '.( $value['multi'] ? ' multiple ' : '' ).' >
							  
							  <p class="help-block">'.$value['sub_text'].'</p> 
						 
						  </div> 
						';
					break;
					
					case "mediafile_single":
					
					// get attachment src
					
					$attach_url = wp_get_attachment_image_src( $config[$value['name']] );
					
					$out .= '<div class="form-group media_upload_block">  
						<label class="control-label" for="input01">'.$value['title'].'</label>  
						 
						  <input type="hidden" class="form-control input-xlarge mediafile_single item_id" name="'.$value['name'].'" id="'.$value['name'].'" value="'.$config[$value['name']].'"> 
						  
						  <input type="hidden" id="csv_link" />
							&nbsp;&nbsp;
						  <input type="button" class="btn btn-success upload_image_csv" data-single="1" value="'.$value['upload_text'].'" />
						  <div class="image_preview">'.( $attach_url[0] ? '<img src="'.$attach_url[0].'" />' : '' ).'</div>
						  
					  </div> ';	
					break;
				}
			}
		}
			echo $out;
			?>

				
				  <div class="form-actions">  
					<button type="button" class="btn btn-primary" id="generate_shortcode">Generate Shortcode & Copy to Clipboard</button>  
					
					
					
				  </div>  
				</fieldset>  

		</form>
		<div class="donate_block">
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="9CCGZJGGVMCTA" />
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal" />
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>

										</div>
		</div>
		<?php
	}
}	
 
 
	
	
add_Action('init',  function (){
	$config_big = 
	array(

		array(
			'type' => 'option',
		 
			'page_title' => __('Search CSV/Excel', $locale_taro),
			'menu_title' => __('Search CSV/Excel', $locale_taro),
			'capability' => 'edit_published_posts',
			'menu_slug' => 'wcs_settings',

			'parameters' => array(
 
				array(
					'type' => 'text',
					'title' => 'Search Bar Title',
					'name' => 'input_placeholder',
					'id' => 'input_placeholder',
				),
				/*
				array(
					'type' => 'text',
					'title' => 'Search Button Text',
					'name' => 'search_button_text',
					'id' => 'search_button_text',
				),
				*/
				array(
					'type' => 'mediafile_single',
					'title' => 'CSV or Excel File',
					'name' => 'csv_file',
					'upload_text' => 'Upload/Select',
				 
				), 
 
				array(
					'type' => 'text',
					'title' => 'Shortcode to Use',
					'name' => 'input_placeholder',
					'id' => 'shortcode_preview',
				),
			)
		)
	); 
	global $settings;

	$settings = new vooSettingsClassMainCSV( 'wcs' ); 
	$settings->create_menu(  $config_big   );
	
} );
	
 

?>