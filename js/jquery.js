$(document).ready(function(){
	
	$('[class~=panel-body], [class~=task-solver], [class~=solve]')
	.css('display','none');

	
	$('#task-info-btn').click(function(){
		$('#task-info').fadeToggle();
	});
	
	$('#solver-info-btn').click(function(){
		$('#solver-info').fadeToggle();
	});
	
	$('#real-task-info-btn').click(function(){
		$('#real-task-info').fadeToggle();
	});
	
	$('#task-file').click(function(){
		$('[class~=task-solver], .task-file-solver').show();		
		$('.task-random-solver').hide();
		$(this).toggleClass("active");
		$('[name=customtask]').val("ct");
		$('[name=Terminal], [name=amountV]').val("");
	});
	
	$('#task-random').click(function(){
		$('[class~=task-solver], .task-random-solver').show();
		$('.task-file-solver').hide();
		$(this).toggleClass( "active");
		$('[name=customtask]').val("ft");
	});
	
	$('#real-task-2-btn').click(function(){
		$('div[id|=real] div[id|=real-task-2], [id|=real-common]').show();
		$('div[id|=real] div[id|=real-task-3]').hide();
		$(this).toggleClass( "active");
	});
	
	$('#real-task-3-btn').click(function(){
		$('div[id|=real] div[id|=real-task-3], [id|=real-common]').show();
		$('div[id|=real] div[id|=real-task-2]').hide();
		$(this).toggleClass( "active");
	});
	
	$('[type=submit]').click(function(){
		$('[class~=panel-body], [class~=task-solver], [class~=task-random-solver]')
		.css('display','none');
		$('[class~=solve]').css('display','block');
	});
	

	
});
