$(document).ready(function(){
	$('[class~=panel-heading]').css('text-align', 'center').css('font-weight','bold');
	
	$('[class~=panel-body], [class~=task-solver], [class~=task-random-solver], [class~=solve]')
	.css('display','none');

	
	$('#task-info-btn').click(function(){
		$('#task-info').fadeToggle();
	});
	
	$('#solver-info-btn').click(function(){
		$('#solver-info').fadeToggle();
	});
	
	$('#task-file').click(function(){
		$('[class~=task-solver], .task-file-solver').fadeIn();		
		$('.task-random-solver').fadeOut();
		$(this).toggleClass("active");
		$('[name=customtask]').val("ct");
		$('[name=Terminal], [name=amountV]').val("");
	});
	
	$('#task-random').click(function(){
		$('[class~=task-solver], .task-random-solver').fadeIn();
		$('.task-file-solver').fadeOut();
		$(this).toggleClass( "active");
		$('[name=customtask]').val("ft");
	});
	
	
	$('[type=submit]').click(function(){
		$('[class~=panel-body], [class~=task-solver], [class~=task-random-solver]')
		.css('display','none');
		$('[class~=solve]').css('display','block');
	});
	

	
});
