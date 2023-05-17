$(function() {
    // 根据项目名称获取游戏专服名称
    get_specialName();
    $("#upperName").change(function(){
        // console.log('根据项目名称获取游戏专服名称')
        $("#specialName option[text!='']").remove();
        $("#specialName").append('<option value="">请选择</option>').change();
        get_specialName();
        return false;
    });
    get_games();
    get_game_servers();
    $("#specialName").change(function(){
        get_game_servers();
        get_games();
        return false;
    });
    get_servers();
    $("#game").change(function(){
        get_servers();
        return false;
    });
    get_apkNum();
    $("#channel").change(function(){
        get_apkNum();
        return false;
    });

    function get_specialName() {
        // 项目名称
        var upperName = $('#upperName').val();
        // console.log('项目名称:'+upperName)
        // 专服名称
        var specialName = $('#specialName').val();
        // console.log('专服名称:'+specialName)

        var gameStr = $('#gameStr').val();
        var gid = $('#gid').val();

        if(upperName == ''){
            $("#specialName option[text!='']").remove();
            $("#specialName").append('<option value="">请选择</option>').change();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/?m=sdkGame&a=getSpecialName",
            data: "upperName="+upperName+"&specialName="+specialName+"&gameStr="+gameStr+"&gid="+gid,
            dataType: 'text',

            success: function(result){
                $("#specialName option[text!='0']").remove();
                $("#specialName").append(result);
            }
        });
        return false;
    }

    function get_games() {
            var upperName = $('#upperName').val();
            var specialName = $('#specialName').val();
            var game = $('#game').val();
            var gameStr = $('#gameStr').val();
            var gid = $('#gid').val();
            if(specialName == ''){
                $("#game option[text!='']").remove();
                $("#game").append('<option value="">请选择</option>').change();
                return false;
            }

            $.ajax({
                type: "POST",
                url: "/?m=sdkGame&a=getGameName",
                data: "upperName="+upperName+"&specialName="+specialName+"&game="+game+"&gameStr="+gameStr+"&gid="+gid,
                dataType: 'text',

                success: function(result){
                    $("#game option[text!='0']").remove();
                    $("#game").append(result);
                }
            });
            return false;
        }

    function get_servers() {
        var game = $('#game').val();
        var channel = $('#channel').val();
        var gid = $('#gid').val();
        if(game == ''){
            // if (gid !=1 && gid !=12) {
            //     $("#channel option[text!='']").remove();
            //     $("#channel").append('<option value="">请选择</option>').change();
            // }else{
                $.ajax({
                    type: "POST",
                    url: "/?m=sdkChannel&a=getGameChannels",
                    data: "game="+game+"&channelId="+channel+"&pvc=1",
                    dataType: 'text',

                    success: function(result){
                        $("#channel option[text!='0']").remove();
                        $("#channel").append(result);
                    }
                });
            // }
            return false; 
        }else{
            $.ajax({
                type: "POST",
                url: "/?m=sdkChannel&a=getGameChannels",
                data: "game="+game+"&channelId="+channel,
                dataType: 'text',

                success: function(result){
                    $("#channel option[text!='0']").remove();
                    $("#channel").append(result);
                }
            });
            return false; 
        }

    }

    function get_apkNum() {
        var game = $('#game').val();
        var channel = $('#channel').val();
        var apkNum = $('#apkNum').val();
        var gid = $('#gid').val();
        if(channel == ''){
            if (gid !=1 && gid !=12) {
                $("#apkNum option[text!='']").remove();
                $("#apkNum").append('<option value="">请选择</option>').change();
            }else{
                $.ajax({
                    type: "POST",
                    url: "/?m=statistics&a=getApkNum",
                    data: "game="+game+"&channelId="+channel+"&apkNum="+apkNum+"&pvc=1",
                    dataType: 'text',

                    success: function(result){
                        $("#apkNum option[text!='0']").remove();
                        $("#apkNum").append(result);
                    }
                });
            }
            return false;
        }else{
            $.ajax({
                type: "POST",
                url: "/?m=statistics&a=getApkNum",
                data: "game="+game+"&channelId="+channel+"&apkNum="+apkNum,
                dataType: 'text',

                success: function(result){
                    $("#apkNum option[text!='0']").remove();
                    $("#apkNum").append(result);
                }
            });
        }
        return false;
    }

    function get_game_servers() {
        var upperName = $('#upperName').val();
        var specialName = $('#specialName').val();
        var server = $('#server').val();
        if(specialName == ''){
            $("#server option[text!='']").remove();
            $("#server").append('<option value="">请选择</option>').change();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/?m=sdkGame&a=getGameServer",
            data: "upperName="+upperName+"&specialName="+specialName+"&server="+server,
            dataType: 'text',

            success: function(result){
                $("#server option[text!='0']").remove();
                $("#server").append(result);
            }
        });
        return false;
    }
});