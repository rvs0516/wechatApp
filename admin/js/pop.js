$(function(){
	$(".showbox").click(function(){
		$("#popbg").css({
			display:"block",height:$(document).height()
		});
		$(".pop").css({
			left:($("body").width()-$(".pop").width())/2-20+"px",
			top:($(window).height()-$(".pop").height())/2+$(window).scrollTop()+"px",
			display:"block"
		});
	});
	
	$(".popclose").click(function(){
		$("#popbg").css("display","none");
		$(".pop ").css("display","none");
	});
});
