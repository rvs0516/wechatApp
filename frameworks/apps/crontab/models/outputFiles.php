<?php

/**
 * 导出文件
 * 
 */
class outputFiles 
{

    /**
     * 导出订单
     */
    public function outputOrder($data)
    {       
        $conditionData = json_decode($data['condition'], true);

        $gid = 1;
        if (!isset($conditionData['ostatus'])) {
            $status = 1;
        } else {
            $status = intval($conditionData['ostatus']);
        }

        $userName = trim($conditionData['userName']) ? trim($conditionData['userName']) : "";

        $roleId = trim($conditionData['roleId']) ? trim($conditionData['roleId']) : "";
        $openAd = trim($conditionData['openAd']) ? trim($conditionData['openAd']) : "";

        $game = trim($conditionData['game']) ? trim($conditionData['game']) : "";

        $channel = trim($conditionData['channel']) ? trim($conditionData['channel']) : "";//渠道
        $paymentId = trim($conditionData['paymentId']) ? trim($conditionData['paymentId']) : "";//支付方式
        if($conditionData['apkNum']){
            $apkNum = trim($conditionData['apkNum']);
        }elseif ($conditionData['yjApkNum']) {
            $apkNum = trim($conditionData['yjApkNum']);
        }

        //时间范围
        if (!empty($conditionData['start_date'])) {
            $start_time = strtotime($conditionData['start_date']);
        }
        if (!empty($conditionData['end_date'])) {
            $end_time = strtotime($conditionData['end_date'] . '23:59:59');
        }

        //游戏
        $game_model = getInstance('@oss.model.sdkGame.game');
        $game_list = $game_model->getList();
        $games = array();
        foreach ($game_list as $key => $value) {
            $games[$value['alias']] = $value['name'];
        }

        //考虑服务器性能损耗，一次导出最多导出100000条
        $page = 1;
        $row = 100000;
        $offset = 0;

        $statistics_model = getInstance('@oss.model.statistics');

        //获取上级游戏名
        $UpperList = $statistics_model->getUpperList();
        $upperName = trim($conditionData['upperName']) ? trim($conditionData['upperName']) : "";

        //获取专服游戏名
        $specialList = $statistics_model->getSpecialList($upperName);
    
        $specialName = trim($conditionData['specialName']) ? trim($conditionData['specialName']) : "";

        //取得一级游戏数据
        if ($upperName && empty($conditionData['game'])) {
            $game_model = getInstance('@oss.model.sdkGame.game');
            $summary = $game_model->getGameName($upperName, '', '');
            $sum = array();
            foreach ($summary as $key => $value) {
                $sum[] = "'" . $value['alias'] . "'";
            }
            $sumString = implode(',', $sum);
            //取得专服游戏数据
            if ($specialName) {
                $specialSummary = $game_model->getGameName($upperName, $specialName, '');
                $specialSum = array();
                foreach ($specialSummary as $key => $value) {
                    $specialSum[] = "'" . $value['alias'] . "'";
                }
                $specialString = implode(',', $specialSum);
            }
        }

        $serverId = trim($conditionData['serverId']) ? trim($conditionData['serverId']) : "";
        $orderId = trim($conditionData['orderId']) ? trim($conditionData['orderId']) : "";

        $order_list = $statistics_model->getOrderList($game, $channel, $start_time, $end_time, $status, $userName, $offset, $row, $apkNum, $paymentId, $sumString, $specialString, $roleId, $openAd, $serverId, $gid, '', $orderId);
        
        $num_omoney = 0;
        foreach ($order_list as $key => $val) {
            
            foreach ($game_list as $k => $v) {
                if ($val['gameAlias'] == $v['alias']) {
                    $order_list[$key]['upperName'] = $v['upperName'];
                }
            }
            $descArray = explode(',', $val['orderDescr']);
            if ($descArray[1] == 'USD') {
                $order_list[$key]['currency'] = '美元';
            }elseif ($descArray[1] == 'VND') {
                $order_list[$key]['currency'] = '越南盾';
            }else{
                $order_list[$key]['currency'] = '人民币';
            }
            if (!empty($descArray[2])) {
                $order_list[$key]['money'] = $descArray[2];
            }

        }
        
        $reports = array();
        foreach ($order_list as $keyr => $valuer) {
            if ($valuer['currency'] != '人民币') {
                $valuer['currency'] = "(".$valuer['currency'].")";
            }else{
                $valuer['currency'] = "";
            }
            $reports[$keyr]['ousername'] = $valuer['userName'];
            $reports[$keyr]['oid'] = $valuer['orderId']."\t";
            $reports[$keyr]['game'] = $valuer['gameName'];
            $reports[$keyr]['server'] = $valuer['server'];
            $reports[$keyr]['ocharname'] = $valuer['roleName'];
            $reports[$keyr]['roleId'] = $valuer['roleId'];
            $reports[$keyr]['otime'] = date('Y-m-d H:i', $valuer['time'])."\t";
            $reports[$keyr]['omoney'] = $valuer['money'].$valuer['currency'];
            $reports[$keyr]['agent_pay_gold'] = $valuer['gold'];
            $reports[$keyr]['channelname'] = ($valuer['channelName']) ? $valuer['channelName'] : ' ';
            $reports[$keyr]['apkNum'] = ($valuer['apkNum']) ? $valuer['apkNum'] : ' ';
            if ($valuer['paymentId'] == 9) {
                $reports[$keyr]['paymentId'] = '微信';
            }elseif ($valuer['paymentId'] == 7) {
                $reports[$keyr]['paymentId'] = '支付宝';
            }else{
                $reports[$keyr]['paymentId'] = '';
            }

            $reports[$keyr]['adid'] = $valuer['adid'];
        }
        $sdate = date('Ymd', $start_time);
        $edate = date('Ymd', $end_time);

        // 导出CSV文件
        $fileName = $data['fileName'];
        $head = array('账号', '订单号', '游戏', '服务器', '角色', '角色ID', '充值时间', '金额', '元宝', 'CPS渠道', '所属包体', '通道', '广告id');
        $this->exportCsv($fileName, $head, $reports);

        // 修改文件下载状态
        $fileManageModel = new model('ms_fileManage');
        $fileManageModel->set(
            array(
                'status' => '2',
            ), 
            "id='{$data['id']}'"
        );

    }

    /**
     * 
     * 导出CSV文件
     * 
     * 说明：针对excel或csv导出的数据变成科学计数法问题
     * 当Excel或CSV文件显示的内容为数字时，如果数字大于12位，它会自动转化为科学计数法；如果数字大于15位，它不仅用于科学技术法表示，还会只保留高15位，其他位都变0。
     * 处理方式：数字字段后面加上显示上看不见的字符即可，字符串结尾加上制表符"\t"，注意要双引号 “”。
     * 
     * @param  $fileName string 文件名称
     * @param  $head array 文件表头
     * @param  $data 文件内容
     * 
     */
    public function exportCsv($fileName, $head, $data)
    {   
        // 当天日期
        $day = date('Ymd', time());

        // 文件路径
        $catalogue = C('DEDE_DATA_PATH'). 'orderExcel/'. $day. '/';
        if ( !is_dir($catalogue) ) {
            // 0777 允许全局访问，允许所有者所有操作，包括读写
			mkdir($catalogue, 0777, true);
		}

        // 文件名称
        $csv_filename  = $catalogue. $fileName. '.csv';

        // 表头
        $fileContent = array();
        $fileContent[] = $head;

        // 文件内容
        $newData = array_values($data);
        foreach ($newData as $key => $value) {
            $fileContent[$key+1] = $value;
        }

        // 打开文件  w： 写入方式打开，清除文件内容，如果文件不存在则尝试创建之
        $outputFile = fopen($csv_filename, "w"); 
        // 写入内容
        foreach($fileContent as $row) {
            fputcsv($outputFile, $row);
        }
        // 关闭文件
        fclose($outputFile);
    }

}