<?php
class game{
	/**
	 * 根据游戏名获取游戏伺服器信息
	 * @param string $alias
	 */
	public function getServerList($alias){
		$sql='SELECT s.* FROM `blk_games_inter` p LEFT JOIN `blk_games_inter` s ON p.`id` = s.`parent_id` WHERE p.`alias`=\'' . $alias . '\' ';
		$sql .= ' order by s.id';
		return model::getBySql($sql);
	}

}
