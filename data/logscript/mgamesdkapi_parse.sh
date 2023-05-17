#!/bin/bash
#该脚本用于解析SDK系统日志
#实际上它并没有执行任何解析日志的操作
#只是通过拼装参数，然后通知PHP使用指定脚本去解释并输出

#日志文件，只能是/data/logs目录下的日志
log_file=$1;

#执行动作(命令)
action=$2;


#param用于拼装参数
param="";

#是否将解析结果保存到指定文件
is_save=false;
save_key=$(($#-1));
if [ "${!save_key}" == "-save" ]; then
	is_save=true;
	file_key=$#;
	save_file=${!file_key};
fi

#指定文件名可为空
save_key=$#;
if [ "${!save_key}" == "-save" ]; then
	is_save=true;
	save_file="";
fi


#前两位的参数刚已经定义，其意义也很明确
#而之后的都是相关action的所需参数
#为了方便，从参数列表中移除前面两位参数
shift;
shift;

type="";
case $action in
	#解析并输出指定ID的日志
	"-id")
		type="id";
		i=0;
		#可以有多个ID
		for id in $@
		do
			#简单判断一下是否带有保存的参数，虽然很山寨。。
			if [ $is_save == true ] && [ $id == "-save" ]; then
				break;
			fi	
			param="${param}&id[${i}]=${id}";
			i=$(($i+1));
		done
	;;

	#除了ID，也可以直接给出日志密文(DATA部分)，让程序解析并输出
	"-data")
		type="data";
		param="data=${1}";
	;;

	*)
		type="all";
	;;
	
esac

#组装请求参数
request_param="m=log";
if [ $is_save == true ]; then
	request_param="${request_param}&a=save&save=${save_file}";	
else
	request_param="${request_param}&a=printf";	
fi
request_param="file=${log_file}&${request_param}&type=${type}&${param}";

dirname=$(cd "$(dirname "$0")"; pwd);
/usr/bin/php ${dirname}/mgamesdkapi.php $request_param;

#输出一个空行做结束
echo "";
exit 1;
