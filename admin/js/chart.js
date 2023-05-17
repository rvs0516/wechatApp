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

function chkForm() {
	var start_date 	= $("#start_date").val();
	var end_date 	= $("#end_date").val();
	if (start_date == '') {
		alert('开始时间不能为空');
		return false;
	}
	if (end_date == '') {
		alert('结束时间不能为空');
		return false;
	}
	if (start_date > end_date) {
		alert('开始时间不能大于结束时间！');
		return false;
	}
	return true;
}

function chkForm3() {
	var start_date 	= $("#start_date").val();
	var end_date 	= $("#end_date").val();
	if (start_date == '') {
		alert('开始时间不能为空');
		return false;
	}
	if (end_date == '') {
		alert('结束时间不能为空');
		return false;
	}
	if (start_date > end_date) {
		alert('开始时间不能大于结束时间！');
		return false;
	}
	$("#form1").submit();
}

$(document).ready(function(){
	var url = '';
	$("#form1 input:hidden").each(function(){
		url+=$(this).attr("name")+'='+$(this).val()+'&';
	});
	url = url.substring(0, url.length-1);
	doCharts('?'+url);
	return false;
});

function goNext(page) {
	var url = '?';
	$("#form1 input:hidden").each(function(){
		url+=$(this).attr("name")+'='+$(this).val()+'&';
	});
	doCharts(url+'start_date='+$("#start_date").val()+"&end_date="+$("#end_date").val()+"&p="+page);
}
function doCharts(url){
	var start_date 	= $("#start_date").val();
	var end_date 	= $("#end_date").val();
	var game 	= $("#game").val();
    var channel     = $("#channel").val();
    var apkNum     = $("#apkNum").val();
    var yjApkNum     = $("#yjApkNum").val();
    var province     = $("#province").val();
    var upperName     = $("#upperName").val();
    var specialName     = $("#specialName").val();
    $.ajax({
			url: url,
    		type: 'POST',
    		data:	{ // 发送到后台处理的数据
	 			start_date 	: 	start_date,
	 			end_date 	: 	end_date,
				game		:	game,
                channel     :   channel,
                apkNum      :   apkNum,
                yjApkNum    :   yjApkNum,
                province    :   province,
                upperName    :   upperName,
                specialName    :   specialName
    		},
    		dataType: "json",
    		timeout: 3000000,	// 超时值,毫秒
    		beforeSend:function(){		// 发起请求之前被调用，用来执行预请求操作
			// 载入动画
    		},
    		success: function(data){
				if (data.temp == 1) {
					$("#chart").css('display','');
					$("#charttip").html('');
					if (data.pageSign != 1) {
						eval(data.str);
					}
					$("#chartTable").show();
					$("#chartTable").html(data.table);
					$(".pager").html(data.pageStr);
				}
				// 多維度圖表
				else if (data.temp == 2) {
					$("#chart").css('display','');
					$("#charttip").html('');
					if (data.pageSign != 1) {
						eval(data.str);
					}
					$("#chartTable").show();
					$("#chartTable").html(data.table);
					$(".pager").html(data.pageStr);
				}
				else {
					$("#chart").css('display','none');
					$("#charttip").html('<div align="center"><font color="red">无查询结果！</font></div>');
					$("#chartTable").html("");
					$("#chartTable").hide();
					$(".pager").html("");
					return false;
				}
    		},
    		error: function(xhr, statusText, errorObject){	//错误处理
        		// alert(xhr+statusText+errorObject);
				// debug;
    		}
   	});//$.ajax() end
}

function month(value) {
	if (value < 10) {
		return '0'+value;
	}
	else {
		return value;
	}
}

function day(value) {
	if (value < 10) {
		return '0'+value;
	}
	else {
		return value;
	}
}

function searchTime(intval, type) {
	var now = new Date();
	var nowyear = now.getFullYear();    //获取完整的年份(4位,1970-????)
	var nowmonth = now.getMonth() + 1;       //获取当前月份(0-11,0代表1月)
	var nowdate = now.getDate();
	var begin;
	var end;

	switch(type) {
		case 'day':
			var nowtimeseconds = now.getTime();
			var beginday = new Date( nowtimeseconds - intval*1000*60*60*24 );
			var endday   = new Date( nowtimeseconds - ( intval - 1 ) * 1000 * 60 * 60 * 24 );
			begin = beginday.getFullYear() + "-" + month( beginday.getMonth() + 1 ) + "-" + day(beginday.getDate());
			end =   begin;
			break;

		case 'week':
			var week_today = now.getDay();
			thisweektimeseconds = new Date( now.getTime() + ( 6 - week_today ) * 1000 * 60 * 60 * 24 ).getTime();
			endweek = new Date(thisweektimeseconds - intval * 7 * 1000 * 60 * 60 * 24 );
			beginweek = new Date( thisweektimeseconds - ( intval + 1 ) * 7 * 1000 * 60 * 60 * 24 );
	    	begin = beginweek.getFullYear() + "-" + month(( beginweek.getMonth() + 1 )) + "-" + day(( beginweek.getDate() + 2 ));
	    	end =  endweek.getFullYear() + "-" + month(( endweek.getMonth() + 1 )) + "-" + day(( endweek.getDate() + 1 ));
			break;

		case 'month':
			begin = nowyear + "-" + month( nowmonth - intval ) + "-" + "01";
			var monthbegin = new Date( nowyear,( nowmonth - intval ),1 );
			var monthendday = new Date( monthbegin.getTime() - 1000 * 60 * 60 * 24 ).getDate();
			end = nowyear + "-" + month( nowmonth - intval ) + "-" + day(monthendday);
			break;
		case 'season_month':
			var changemonth= nowmonth % 3;
			var tarmonth =null;
			tarmonth = nowmonth+(intval-changemonth);
			begin = nowyear + "-" + month(tarmonth) + "-" + "01";
			var monthbegin = new Date( nowyear,tarmonth,1 );
			var monthendday = new Date( monthbegin.getTime() - 1000 * 60 * 60 * 24 ).getDate();
			end = nowyear + "-" + month(tarmonth) + "-" + day(monthendday);
			break;
		case 'season':
			var season= parseInt(nowmonth/3);
			begin = nowyear + "-" + month(season*3+1) + "-" + "01";
			var monthendday = new Date( new Date(nowyear,(season*3+3),1).getTime() - 1000 * 60 * 60 * 24 ).getDate();
			end =  nowyear + "-" + month(season*3+3) + "-" + day(monthendday);
			break;
	 	default:break;
	}

	$("#start_date").attr("value", begin);
	$("#end_date").attr("value", end);

	var url = '';
	$("#form1 input:hidden").each(function(){
		url+=$(this).attr("name")+'='+$(this).val()+'&';
	});
	doCharts('?'+url);
}

$(document).ready(function(){
	$("#sub").click(function(){
		if(chkForm()) {
			var url = '';
			$("#form1 input:hidden").each(function(){
				url+= $(this).attr("name") + '=' + $(this).val() + '&';
			});
			doCharts('?' + url);
		}
		return false;
	});
});