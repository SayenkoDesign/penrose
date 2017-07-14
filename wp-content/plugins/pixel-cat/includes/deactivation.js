/* jshint asi: true */
var $deactivateButton

jQuery(document).ready(function($){
	
	$deactivateButton = $('#the-list tr.active').filter( function() { return $(this).data('plugin') === 'pixel-cat/pixel-cat.php' } ).find('.deactivate a')
		
	$deactivateButton.click(function(e){
		e.preventDefault()
		$deactivateButton.unbind('click')
		$('body').append(fca_pc.html)
		fca_pc_uninstall_button_handlers()
		
	})
}) 

function fca_pc_uninstall_button_handlers() {
	var $ = jQuery
	$('#fca-deactivate-skip').click(function(){
		$(this).prop( 'disabled', true )
		window.location.href = $deactivateButton.attr('href')
	})
	$('#fca-deactivate-send').click(function(){
		$(this).prop( 'disabled', true )
		$(this).html('...')
		$('#fca-deactivate-skip').hide()
		$.ajax({
			url: fca_pc.ajaxurl,
			type: 'POST',
			data: {
				"action": "fca_pc_uninstall",
				"nonce": fca_pc.nonce,
				"msg": $('#fca-deactivate-textarea').val()
			}
		}).done( function( response ) {
			console.log ( response )
			window.location.href = $deactivateButton.attr('href')			
		})	
	})
	
}