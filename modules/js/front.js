jQuery(document).ready( function($){
	
	$('.input_field').focusout(function(){
		var parent = $(this).parents('.container_big');
		$('.search_results', parent).html( '' );
	})
	$('.input_field').keyup(function(){
 
		var parent = $(this).parents('.container_big');
		var id = parent.attr('data-id');
	
		var outstr = '';
		if( $(this).val().length  >= 2 ){
			var val2search = $('.input_field', parent).val();

			$.each(  window['output_json'+id], function( index, value ){
				if( index == 0 ){
					outstr += '<tr class="bold"><td>'+value.join('</td><td>')+'</td></tr>';
				}
			
			
				var cur_string = value.join('|').toUpperCase();	
				if( cur_string.indexOf( val2search.toUpperCase() )  === 0 ){
					outstr += '<tr><td>'+value.join('</td><td>')+'</td></tr>';
				}
			})
			outstr = '<table class="table1"><tbody>'+outstr+'</tbody></table>';
			$('.search_results', parent).html( outstr );
		}else{
			$('.search_results', parent).html( '' );
		}
		
	})
	
	$('.make_search').keyup(function(){
 
		var parent = $(this).parents('.container_big');
		var id = parent.attr('data-id');
	
		var outstr = '';
		 
			var val2search = $('.input_field', parent).val();

			$.each(  window['output_json'+id], function( index, value ){
				var cur_string = value.join('|').toUpperCase();	
				if( cur_string.indexOf( val2search.toUpperCase() )  === 0 ){
					outstr += '<tr><td>'+value.join('</td><td>')+'</td></tr>';
				}
			})
			outstr = '<table class="table1"><tbody>'+outstr+'</tbody></table>';
			$('.search_results', parent).html( outstr );
	 
		
	})
	
	
	
	
}) // global end
