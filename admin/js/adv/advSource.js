//保留2位小数
function twoDecimal(x) {
    var f_x = parseFloat(x);
    if (isNaN(f_x)) {
        // alert('错误的参数');
        return false;
    }
    var f_x = Math.round(x * 100) / 100;
    var s_x = f_x.toString();
    var pos_decimal = s_x.indexOf('.');
    if (pos_decimal < 0) {
        pos_decimal = s_x.length;
        s_x += '.';
    }
    while (s_x.length <= pos_decimal + 2) {
        s_x += '0';
    }
    return s_x;
}

function doChart(url) {
	var start_date = $("#start_date").val();
	var end_date   = $("#end_date").val();
	var game	   = $("#game").val();
	var media      = $("#media").val();
	var index	   = $("#index").val();
	$.ajax({
		url  	 : url,
		type 	 : "post",
		data 	 : {
			start_date : start_date,
			end_date   : end_date,
			game	   : game,
			media 	   : media,
			index	   : index
		},
		dataType : "json",
		//cache 	 : false,
		success  : function (data) {
			if (data.temp == 1) {
				$("#"+data.type).css("display", "");
				eval(data.str);
				$("#"+data.type+"tip").html("");
			}
			else if (data.temp == 0) {
				$("#"+data.type).css("display", "none");
				$("#"+data.type+"tip").html("<font color='red'>無查詢結果！</font>");
			}
		}
	});	
}

function getDetail(url) {
	var start_date = $("#start_date").val();
	var end_date   = $("#end_date").val();
	var index	   = $("#index").val();
	var type	   = $("#type").val();
	$.ajax({
		url  	 : url,
		type 	 : "post",
		data 	 : {
			start_date : start_date,
			end_date   : end_date,
			index	   : index,
			type	   : type
		},
		dataType : "json",
		//cache 	 : false,
		success  : function (data) {
			if (data.temp == 1) {
				$("#adv").css("display", "");
				$("#adv").html("");
				$("#adv").html(data.table);
				$("#advtip").html("");
			}
			else if (data.temp == 0) {
				$("#adv").css("display", "none");
				$("#adv").html("");
				$("#advtip").html("<font color='red'>無查詢結果！</font>");
			}
		}
	});	
}
$(document).ready(function(){
	doChart("?m=adv&a=advSource&ajax=getGameSource");
	doChart("?m=adv&a=advSource&ajax=getChlSource");
	getDetail("?m=adv&a=advSource&ajax=getSourceDetail");
	$("#game").change(function(){
		doChart("?m=adv&a=advSource&ajax=getChlSource");		
	});
	$("#media").change(function(){
		doChart("?m=adv&a=advSource&ajax=getGameSource");		
	});
	$("#index").change(function(){
		doChart("?m=adv&a=advSource&ajax=getGameSource");
		doChart("?m=adv&a=advSource&ajax=getChlSource");
	});
	$("#sub").click(function(){
		doChart("?m=adv&a=advSource&ajax=getGameSource");
		doChart("?m=adv&a=advSource&ajax=getChlSource");
		getDetail("?m=adv&a=advSource&ajax=getSourceDetail");
	})
	$("#type").change(function(){
		getDetail("?m=adv&a=advSource&ajax=getSourceDetail");		
	});	
});