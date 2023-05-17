#!/bin/bash

dirname=$(cd "$(dirname "$0")"; pwd);
/usr/bin/php ${dirname}/mgamesdkapi.php "m=log&a=check&game=${1}"

#输出一个空行做结束
echo "";
exit 1;
