$(function() {
    //绑定菜单li跳转事件，增加点击范围
    $('.treeview-black ul li').hover(function() {
        var $self = $(this);
        if(!$self.hasClass('active')) {
            $('.treeview-black ul li').removeClass('hover');
            $self.addClass('hover');
        }
    }, function() {
        $(this).removeClass('hover');
    })
    .click(function() {
        window.location = $(this).find('a').attr('href');
    }).hover();
    
    //设置菜单高亮
    $('#menu li').hover(function() {
        var $self = $(this);
        if(!$self.hasClass('active')) {
            $('#menu li').removeClass('hover');
            $self.addClass('hover');
        }
    }, function() {
        $(this).removeClass('hover');
    });
    
    //设置当前菜单选项高亮
    $('.treeview-black ul a').each(function() {
        var selfvars = getUrlVars( $(this).attr('href') );
        var vars = getUrlVars(window.location.search);
        var match = true;
        for(var key in vars) {
            if(selfvars[key] != vars[key]) {
                match  = false;
            }
        }
        for(var key in selfvars) {
            if(selfvars[key] != vars[key]) {
                match  = false;
            }
        }
        if(match) {
            $(this).parentsUntil('li').parent().addClass('active');
        }
    });
    
    //解释Url参数
    function getUrlVars(uri) {
        var vars = [], hash;
        var hashes = uri.slice(uri.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }
    
    //设置表格停留高亮
    $('body').on('mouseover', 'table.list tr', function() {
        $('table.list tr').removeClass('hover');
        var $self = $(this);
        if(!$self.hasClass('selected')) {
            $self.addClass('hover');
        }
    });
    $('body').on('mouseleave', 'table.list tr', function() {
        $(this).removeClass('hover');
    });
    
    //设置表格选择
    $('body').on('click', 'table.list tr', function() {
        var $self = $(this);
        $self.removeClass('hover');
        $self[ $self.hasClass('selected') ? 'removeClass' : 'addClass' ]('selected');
    });
    
    //头部标签高亮
    $('body').on('mouseover', '.label-list li', function() {
        var $self = $(this);
        if(!$self.hasClass('active')) {
            $('.label-list li').removeClass('hover');
            $self.addClass('hover');
        }
    });
    $('body').on('mouseleave', '.label-list li', function() {
        $(this).removeClass('hover');
    });
    
    //为长表格设置滚动箭头
    if( $('.auto-layout-table').get(0) ) {
        $('.auto-layout-table').each(function() {
            var $tb = $(this);
            var $l = $('<a href="javascript:void(0);"><img src="/img/table_arrows_l.png" width="20" height="50" /></a>').appendTo($tb);
            var $r = $('<a href="javascript:void(0);"><img src="/img/table_arrows_r.png" width="20" height="50" /></a>').appendTo($tb);
            
            $l.on({
                'mousedown' : function() {
                    var tb = $tb.get(0);
                    var move = function() {
                        tb.scrollLeft = tb.scrollLeft - 50;
                    };
                    move();
                    $tb.attr('data-scroll-lid', setInterval(move, 200));
                },
                'mouseup' : function() {
                     clearInterval( $tb.attr('data-scroll-lid') );
                },
                'mouseleave': function() {
                     clearInterval( $tb.attr('data-scroll-lid') );
                }
            });
            $r.on({
                'mousedown' : function() {
                    var tb = $tb.get(0);
                    var move = function() {
                        tb.scrollLeft = tb.scrollLeft + 50;
                    };
                    move();
                    $tb.attr('data-scroll-rid', setInterval(move, 200));
                },
                'mouseup' : function() {
                     clearInterval( $tb.attr('data-scroll-rid') );
                },
                'mouseleave': function() {
                     clearInterval( $tb.attr('data-scroll-rid') );
                }
            });
            
            var setPos = function() {
                var offset = $tb.offset();
                var curTop = offset.top + $tb.height() - $(window).scrollTop() - 40;
                var top = curTop < offset.top ? curTop : offset.top;
                $l.css({
                    'position': 'fixed',
                    'top': top + 'px',
                    'left': offset.left - 30 + 'px',
                    'filter': 'alpha(opacity=60)',
                    'opacity' : 0.6
                });
                $r.css({
                    'position': 'fixed',
                    'top': top + 'px',
                    'left': offset.left + 10 + $tb.width() + 'px',
                    'filter': 'alpha(opacity=60)',
                    'opacity' : 0.6
                });
            };
            setPos();
            $(window).scroll(setPos).resize(setPos);
        });
    }
});

/**
 * 显示一个窗口
 * 
 * @param string title 标题
 * @param string mainid 容器ID
 * @param function callback 回调 第一个参数表示用户点确定还是关闭
 * @param int width 宽度
 */
function showWindow(title, mainid, callback, width) {
    if(!$('#popbg').get(0)) {
        $('<div id="popbg" style="display: none;"></div>').appendTo('body');
    }
    if(!$('#popmain').get(0)) {
        var mainHtml = '<div id="pop" class="pop searchbox" style="display: none;">' +
            '<h3><span><a href="#" class="popclose">&nbsp;&nbsp;X</a></span><b class="poptitle">添加模塊</b></h3>' +
            '<div class="popmain"></div>' +
            '<p class="inline"><button type="submit" class="su popsubmit" style="margin-top:2px;">確定</button></p>' +
        '</div>';
        $(mainHtml).appendTo('body');
    }
    var $popbg = $('#popbg');
    var $pop = $('#pop');
    var $main = $pop.find('.popmain');
    //添加标题
    $pop.find('.poptitle').html(title);
    //移除旧节点
    $main.children().hide().appendTo('body');
    //添加新节点
    $('#' + mainid).appendTo( $main );
    //绑定关闭事件
    $pop.find('.popclose').unbind().click(function() {
        $pop.hide();
        $popbg.hide();
        callback && callback(false);
    });
    //绑定提交事件
    $pop.find('.popsubmit').unbind().click(function() {
        $popbg.hide();
        $pop.hide();
        callback && callback(true);
    });
    //显示内容
    $popbg.css({
        height:$(document).height()
    }).fadeIn();
    $pop.css( 'width', (width || 150) + 'px' ).css({
        left:($("body").width() - $pop.width()) / 2 - 20 + "px",
        top:($(window).height() - $pop.height())/ 3 + $(window).scrollTop() + "px"
    }).fadeIn();
    $('#' + mainid).show();
}

/**
 * mis普通弹框
 * 
 * @param string content
 */
function misAlert(content, width) {
    if(!$('#misalert').get(0)) {
        $('<p id="misalert"></p>').appendTo('body');
    }
    $('#misalert').html( content.replace(/\n/g, '<br />') );
    showWindow('消息', 'misalert', false, width || 200);
}

/**
 * mis内容确认框
 * 
 * @param string content
 * @param function callback 回调 第一个参数表示用户点确定还是关闭
 */
function misConfirm(content, callback, width) {
    if(!$('#misconfirm').get(0)) {
        $('<p id="misconfirm"></p>').appendTo('body');
    }
    $('#misconfirm').html( content.replace(/\n/g, '<br />') );
    showWindow('提示', 'misconfirm', callback, width || 200);
}

/**
 * 计算日期
 * 
 * @param string type
 * @return object { start, end }
 */
function getDate(type) {
    var cur_date = new Date();
    var start, end;
    var daytime = 86400000;
    switch (type) {
        //上周
        case 'last_week':
            //上周日
            //算法：先减去7天得到上周的某一天，再减去上周已过去的天数
            var date = new Date(cur_date.getTime() - (7 * daytime) - (cur_date.getDay() * daytime));
            start = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            end = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 6);
            break;

        //今周
        case 'current_week':
            //和上周一样，但不需要减去7天
            var date = new Date(cur_date.getTime() - (cur_date.getDay() * daytime));
            start = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            end = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 6);
            break;

        //上月
        case 'last_month':
            start = new Date(cur_date.getFullYear(), cur_date.getMonth() - 1, 1);
            end = new Date(cur_date.getFullYear(), cur_date.getMonth(), 0);
            break;

        //本月
        case 'current_month':
            start = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
            end = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
            break;

        //今日
        case 'today':
        default:
            start = end = cur_date;
            break;
    }
    var formatDate = function(date) {
        return [ date.getFullYear(), date.getMonth() + 1, date.getDate() ].join('-')
                .replace(/-(\d)(?!\d)/, '-0$1').replace(/-(\d)(?!\d)/, '-0$1');
    }
    return {
        start: formatDate(start),
        end: formatDate(end)
    };
}