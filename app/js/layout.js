$(document).ready(function(){
	$('#btn-space-toggle').click(function(){
		$('#filter').fadeIn(100);
		$('#space-panel').addClass('-toggle');
	});

	$('#btn-zone-toggle').click(function(){
		$('#filter').fadeIn(100);
		$('#zone-panel').addClass('-toggle');
	});

	$('#filter').click(function(){
		$('#filter').fadeOut(300);
		$('#space-panel').removeClass('-toggle');
		$('#zone-panel').removeClass('-toggle');
	})
});