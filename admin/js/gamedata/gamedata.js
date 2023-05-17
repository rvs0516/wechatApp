/**
 * 
 */
$(document).ready(function(){
	$("#index").get(0).options[0].selected = true;
	$("#query").click(function(){
		//alert('kkk');
		gameAjax('week');
	});
	$("#index").change(function(){
		var index = $("#index").val();
		if (index == "getChannel") {
			$("#all").remove();
			$("#game").get(0).options[0].selected = true;
			$("#game").hide();
		}
		else if (index == "getPayUser") {
			$("#all").remove();
			$("#game").show();
		}
		else {
			$("#all").remove();
			$('<option value="" id="all">全部遊戲</option>').prependTo($("#game"));
			$("#game").get(0).options[0].selected = true;
			$("#game").show();
		}
	});
	$("#week").click(function(){
		gameAjax('week');
	});
	$("#month").click(function(){
		gameAjax('month');
	});
});
function gameAjax(condition) {
	$.ajax({
		url 	: "?m=gamedata&a=index&ajax="+$("#index").val()+"&condition="+condition,
		type	: "post",
		dataType: "json",
		data 	: {
			date : $("#date").val(),
			game : $("#game").val()
		},
		cache	: false,
		beforeSend : function() {
			if ($("#date").val() == '') {
				alert("時間不能為空！");
				$("#date").focus();
				return false;
			}
			return true;
		},
		success : function(data) {
			if (data.temp == '1') {
				$("#list").html(data.data);
				$("#list").show();					
			}
			else {
				$("#list").html("<tr><td><font color='red'>無結果！</font></td></tr>");
				$("#list").show();					
			}
		},
		error   : function(msg) {
			
		}
	});
}