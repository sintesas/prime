$(document).ready(function(){
	
	$('.ir-arriba').click(function(){
		$('body,html').animate({
			scrollTop:'0px'
		});
		$('.ir-arriba').slideDown(100);
	});
	
	$(window).scroll(function(){
		if( $(this).scrollTop() >50 ){
			$('.ir-arriba').slideDown(100);
		}else{
			$('.ir-arriba').slideUp(100);
			
		};
		
	});
});