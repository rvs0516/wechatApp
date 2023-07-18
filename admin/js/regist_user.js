
$(document).ready(function(){       
	$("#sub").click(function() {
		var begin  = $("#start_date").attr("value");
		var end  = $("#end_date").attr("value"); 	
		var game  = $("#game").attr("value"); 
		
		doajax(begin, end, game);
	});
});

function doajax(start_date, end_date, game){
	$.ajax({type: "GET", url: "/index.php?m=game&a=mgameUsersRole&isajax=1&start_date="+start_date+"&end_date="+end_date+"&game="+game,
	async:   false,
	global:  true,
	dataType: 'html',
	success: function(data){            
		$("#mmlist").html(data);
	}});
	return false;
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

	var game  = $("#game").attr("value"); 		
	$("#start_date").attr("value", begin);
	$("#end_date").attr("value", end); 
	
	doajax(begin, end, game);
}