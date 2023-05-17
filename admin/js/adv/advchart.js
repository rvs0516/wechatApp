function doChart(url) {
	var start_date = $("#start_date").val();
	var end_date   = $("#end_date").val();
	var game	   = $("#game").val();
	var media      = $("#media").val();
	var material   = $("#material").val();
	var index	   = $("#index").val();
	$.ajax({
		url  	 : url,
		type 	 : "post",
		data 	 : {
			start_date : start_date,
			end_date   : end_date,
			game	   : game,
			media 	   : media,
			material   : material,
			index	   : index
		},
		dataType : "json",
		cache 	 : false,
		success  : function (data) {
			if (data.temp == 1) {
				eval(data.str);
				//$("#advchart").css("display", "");
				$("#advtip").html("");
			}
			else if (data.temp == 0) {
				$("#advchart").html("");
				$("#advtip").html("<font color='red'>無查詢結果！</font>");
			}
			if (data.temp == 't1') {
				$("#adv").css("display", "");
				$("#adv").html("");
				$("#adv").html(data.table);
				$(".pager").html(data.pageStr);
			}
			else if (data.temp == 't0') {
				$("#adv").css("display", "none");
				$("#adv").html("");
				$("#advtip").html("<font color='red'>無查詢結果！</font>");
				$(".pager").html("");
			}
		}
	});
}
function goNext(page) {
	doChart("?m=adv&a=advChart&ajax=getAdvDetail&p="+page);
}
$(document).ready(function(){
	if ($("#start_date").val() == $("#end_date").val()) {
		$("option","#index").remove();
		var str = '<option value="2">點擊量&amp;註冊量</option><option value="3">轉化率</option>';
		$("#index").html(str);			
	}
	else {
		$("option","#index").remove();
		var str = '<option value="1">曝光量</option><option value="2">點擊量&amp;註冊量</option><option value="3">轉化率</option><option value="4">推廣成本</option><option value="5">點擊均價</option><option value="6">註冊均價</option>';
		$("#index").html(str);			
	}	
	doChart("?m=adv&a=advChart&ajax=getAdvData");
	doChart("?m=adv&a=advChart&ajax=getAdvDetail");
	$("#sub").click(function(){
		if ($("#start_date").val() == $("#end_date").val()) {
			var key = $("#index").val() - 2;
			$("option","#index").remove();
			var str = '<option value="2">點擊量&amp;註冊量</option><option value="3">轉化率</option>';			
			$("#index").html(str);
			if (key <= 1) {
				$("#index").get(0).options[key].selected = true;
			}
			else {
				$("#index").get(0).options[0].selected = true;
			}
		}
		else {
			var key = $("#index").val() - 1;
			$("option","#index").remove();
			var str = '<option value="1">曝光量</option><option value="2">點擊量&amp;註冊量</option><option value="3">轉化率</option><option value="4">推廣成本</option><option value="5">點擊均價</option><option value="6">註冊均價</option>';
			$("#index").html(str);
			$("#index").get(0).options[key].selected = true;
		}
		doChart("?m=adv&a=advChart&ajax=getAdvData");
		doChart("?m=adv&a=advChart&ajax=getAdvDetail");
		
	});
	$("#index").change(function(){
		doChart("?m=adv&a=advChart&ajax=getAdvData");
		doChart("?m=adv&a=advChart&ajax=getAdvDetail");		
	});
	$("#export").click(function(){
		var start_date = $("#start_date").val();
		var end_date   = $("#end_date").val();
		var game	   = $("#game").val();
		var media      = $("#media").val();
		var material   = $("#material").val();
		var index	   = $("#index").val();
		var url = "?m=adv&a=exportAdvDetail"+'&start_date='+start_date+'&end_date='+end_date+'&game='+game+'&media='+media+'&material='+material;
		location.href = url;
		return false;
	});
})