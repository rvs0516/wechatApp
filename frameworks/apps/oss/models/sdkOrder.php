<?php
/**
 * sdk订单处理
 */

class sdkOrder {
	
	static public function getList() {
		$order_fail_model = new Model('ms_sdk_orders_fail');
		$order_list = array();
		$sql = "SELECT s.oid, s.game, s.server, s.money, s.gold, FROM_UNIXTIME(s.time, '%Y-%m-%d %H:%i') as time, s.username, f.error_message as error 
				FROM ms_sdk_orders_fail f LEFT JOIN ms_all_orders s ON f.oid=s.oid 
				WHERE s.oid IS NOT NULL
				ORDER BY s.time DESC";
		return $order_fail_model->getBySql($sql);
	}
	
	/**
	 * 设置订单为成功
	 * 
	 * @param string $oid
	 */
	static public function setOrderSuccess($oid) {
		$oid = mysql_real_escape_string($oid);
		$sql = "oid='{$oid}'";
		$order_model = new Model('ms_all_orders');
		$order_model->set( array('status' => 1), $sql) ;
		
		$order_model = new Model('blk_shops_orders');
		$order_model->set( array('state' => 1, 'game_state' => 1), $sql);
		
		$order_model = new Model('ms_agent_orders');
		$order_model->set( array('ostatus' => 1, 'send_status' => 1), $sql);
		
		$order_model = new Model('ms_sdk_orders_fail');
		$order_model->delete($sql);
	}
}

?>
