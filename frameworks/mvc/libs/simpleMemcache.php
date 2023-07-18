<?php
/**
 * memcache缓存类
 * 
 * 暂不支持多服务器缓存，使用memcache而不是memcached
 * 		
 */
class simpleMemcache {
	
	/**
	 * 持久化memcache对象
	 * @var object
	 */
	private $_memcache;
	
	/**
	 * 默认配置
	 * @var string
	 */
	protected $_memcache_conf = array(
		'host' => '127.0.0.1',
		'port' => 11211
	);

	/**
	 * 构造器
	 *
	 * @param array $config array( host => 主机IP, port => 端口 )
	 */
	public function __construct($config = array()) {
		if( !empty($config) ) {
			$this->_memcache_conf = $config;
		}
		//初始化
		$this->_initialize();
	}

	/**
	 * 初始化memcache
	 * 
	 * @throws ErrorException
	 */
	private function _initialize() {
		if (!extension_loaded('memcache')) {
			throw new ErrorException('memcache扩展不存在');
		}
		
		$this->_memcache = new Memcache();
		$result = $this->_memcache->connect(
			$this->_memcache_conf['host'], $this->_memcache_conf['port']
		);
		if(!$result) {
			throw new ErrorException('memcache服务器连接失败，请确认已经成功安装并启动了memcache service');
		}
	}


	/**
	 * 获取数据
	 *
	 * @param string 键名
	 * @return mixed
	 */
	public function get($id) {
		$data = $this->_memcache->get($id);

		return (is_array($data)) ? $data[0] : false;
	}


	/**
	 * 保存数据
	 *
	 * @param string 键名
	 * @param mixed	 数据
	 * @param int 有效期
	 * @return mixed
	 */
	public function save($id, $data, $ttl = 60) {
		return $this->_memcache->set($id, array($data, time(), $ttl), 0, $ttl);
	}

	/**
	 * 删除数据
	 *
	 * @param string 键名
	 * @return boolean
	 */
	public function delete($id) {
		return $this->_memcache->delete($id);
	}


	/**
	 * 清空所有数据
	 *
	 * @return boolean
	 */
	public function clean() {
		return $this->_memcache->flush();
	}

	/**
	 * 获得memcache信息
	 *
	 * @return array
	 */
	public function cacheInfo() {
		return $this->_memcache->getStats();
	}

	/**
	 * 获取完整的数据记录（前缀、创建时间、数据内容）
	 *
	 * @param string 键名
	 * @return mixed
	 */
	public function getMetadata($id) {
		$stored = $this->_memcache->get($id);

		if (count($stored) !== 3) {
			return false;
		}

		list($data, $time, $ttl) = $stored;

		return array(
			'expire' => $time + $ttl,
			'mtime' => $time,
			'data' => $data
		);
	}

}
