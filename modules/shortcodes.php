<?php  
add_shortcode( 'csv_filter', 'wcs_csv_filter' );
function wcs_csv_filter( $atts, $content = null ){
		
		
	$rand = rand(1, 999999);
	$csv = $atts['csv'];
	$label = $atts['label'];
	$search = $atts['search'];	
		
	 
	$current_transient = get_transient( 'wcs_'.md5( $csv ) );	 
 
	if( $current_transient && $current_transient != '' ){
		$out_array = get_transient( 'wcs_'.md5( $csv ) );
	}else{	
		$args = array(
			'timeout'     => 15,
			'redirection' => 5,
			'httpversion' => '1.0',
			'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
			
			); 
			
			
		$res = wp_remote_get( $csv, $args );
		 
		if( !is_wp_error( $res ) ){
			$file_content = wp_remote_retrieve_body( $res );

					$out_array = array();
					
			 
					if( substr_count( $csv, '.csv' ) > 0 ) {
						$all_lines = explode( "\n", $file_content );
						foreach( $all_lines as $single_line ){
						
							
							if( $single_line != '' ){
								$out_array[] = explode(';', trim( $single_line ) );
							}
						}
					}
				 
				 
					if( substr_count( $csv, '.xls' ) > 0 ) {
						require_once dirname(__FILE__).'/inc/phpExcel/PHPExcel/IOFactory.php';
						
						$attached_file = get_attached_file( pippin_get_image_id( $csv ) );
						
						if( $attached_file && file_exists( $attached_file ) ){
						
							$objPHPExcel = PHPExcel_IOFactory::load( get_attached_file( pippin_get_image_id( $csv ) ) );
							foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
								$worksheetTitle     = $worksheet->getTitle();
								$highestRow         = $worksheet->getHighestRow(); // e.g. 10
								$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
								$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
								$nrColumns = ord($highestColumn) - 64;
								
								
								for ($row = 1; $row <= $highestRow; ++ $row) {
									
									$lastColumn = $worksheet->getHighestColumn();
									$array = $worksheet->rangeToArray('A'.$row.':'.$lastColumn.$row);
									$out_array[] =  $array[0];
									/*
										for ($col = 0; $col < $highestColumnIndex; ++ $col) {
										$cell = $worksheet->getCellByColumnAndRow($col, $row);
										$val = $cell->getValue();
										$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
										
										}
									*/
									
								}
								
							}
						}
					}
				 
					set_transient('wcs_'. md5( $csv ), $out_array, 60*60 );
				 
		}else{
			$out .= 'Error!';
		}
	}
			
 
 
		
		$out .=  '<script>';				
		$out .=  'var output_json'.$rand.' = '. json_encode( $out_array );
		$out .=  '</script>';
		$out .= '
			<div class="tw-bs4 container_big" data-id="'.$rand.'">
			<label>'.$label.'</label>
			<div class="input-group mb-3">
			
			<input type="text" class="form-control input_field"   aria-describedby="basic-addon2">
			<div class="input-group-append">
			<!-- <button class="btn btn-outline-secondary make_search"   type="button">'.$search.'</button> -->
			</div>
			</div>
			
		 
			
			<div class="search_results">
				
			</div>
			
			</div>
			';
			
		return $out;
		
}
	
function pippin_get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return $attachment[0]; 
}
	
?>