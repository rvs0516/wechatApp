$(function() {
    get_second();
    $("#first_name").change(function(){
		$("#second_name option[text!='']").remove();
        $("#second_name").append('<option value="">请选择</option>').change();
        get_second();
        return false;
    });
    get_games();
    $("#second_name").change(function(){
        get_games();
        return false;
    });
	get_servers();
	$("#game").change(function(){
		get_servers();
		return false;
	});

    function get_second() {
        var first_name = $('#first_name').val();
        var second_name = $('#second_name').val();
        if(first_name == ''){
            $("#second_name option[text!='']").remove();
            $("#second_name").append('<option value="">请选择</option>').change();
            return false;
        }

        $.ajax({
            type: "POST",
            url: "/?m=sdkGame&a=getSecondName",
            data: "first_name="+first_name+"&second_name="+second_name,
            dataType: 'text',

            success: function(result){
                $("#second_name option[text!='0']").remove();
                $("#second_name").append(result);
            }
        });
        return false;
    }

    function get_games() {
    		var first_name = $('#first_name').val();
            var second_name = $('#second_name').val();
            var game = $('#game').val();
            if(second_name == ''){
                $("#game option[text!='']").remove();
                $("#game").append('<option value="">请选择</option>').change();
                return false;
            }

            $.ajax({
                type: "POST",
                url: "/?m=sdkGame&a=getGameName",
                data: "first_name="+first_name+"&second_name="+second_name+"&game="+game,
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
        var thisc = "{$smarty.session.qchannel}";
        if(game == ''){
                $("#channel option[text!='0']").remove();
                $("#channel").append('<option value="0">请选择</option>').change();
                return false;
        }

        $.ajax({
                type: "POST",
                url: "/?m=sdkChannel&a=getGameChannels",
                data: "game="+game+"&thisc="+thisc,
                dataType: 'text',

                success: function(result){
                        $("#channel option[text!='0']").remove();
                        $("#channel").append(result);
                }
        });
        return false;
    }
});