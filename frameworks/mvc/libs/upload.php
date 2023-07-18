<?php
/**
 * 上传文件处理类
 * @package io
 */

class Upload {
    public $maxSize = - 1;			//上传文件的最大值

    public $allowExts = array();	//允许上传的文件后缀，留空不作后缀检查
    public $allowTypes = array();	//允许上传的文件类型,留空不做检查

    public $autoSub = FALSE;		//启用子目录保存文件
    public $dateFormat = 'Ymd'; 	//子目录方式为 date 的时候指定日期格式

    public $savePath;			//上传文件保存路径

    public $autoCheck = true;		 //是否自动检查附件

    public $uploadReplace = false;	 //存在同名是否覆盖

    // 上传文件命名规则
    // 例如可以是 time, uniqid, com_create_guid, 等
    // 必须是一个无需任何参数的函数名 可以使用自定义函数
    public $saveRule = 'ruleNow';

    private $error = '';// 错误信息

    private $uploadFileInfo;// 上传成功的文件信息

    /**
     * 构造函数
     * @access public
     */
    function __construct($maxSize = '', $allowExts = '', $allowTypes = '', $savePath = '', $saveRule = '') {
        if (! empty($maxSize) && is_numeric($maxSize)) {
            $this->maxSize = $maxSize;
        }

        if (! empty($allowExts)) {
            if (is_array($allowExts)) {
                $this->allowExts = array_map('strtolower', $allowExts);
            } else {
                $this->allowExts = explode(',', strtolower($allowExts));
            }
        }

        if (! empty($allowTypes)) {
            if (is_array($allowTypes)) {
                $this->allowTypes = array_map('strtolower', $allowTypes);
            } else {
                $this->allowTypes = explode(',', strtolower($allowTypes));
            }
        }

        if (! empty($saveRule)) {
            $this->saveRule = $saveRule;
        }

        $this->savePath = $savePath;
    }


    /**
     * 上传文件
     * @access public
     * @param string $savePath  上传文件保存路径
     * @return bool
     */
    function upload($savePath = '') {
        //如果不指定保存文件名，则由系统默认
        if (empty($savePath))
            $savePath = $this->savePath;

        // 检查上传目录
        if (! is_dir($savePath)) {
            // 尝试创建目录
            if (! mkdir($savePath)) {
                $this->error = '上传目录' . $savePath . '不存在';
                return false;
            }
        } else {
            if (! is_writeable($savePath)) {
                $this->error = '上传目录' . $savePath . '不可写';
                return false;
            }
        }
        $fileInfo = array();
        $isUpload = false;

        // 获取上传的文件信息
        // 对$_FILES数组信息处理
        $files = $this->dealFiles($_FILES);
        foreach ( $files as $key => $file ) {
            //过滤无效的上传
            if (! empty($file['name'])) {
                //登记上传文件的扩展信息
                $file['key'] = $key;
                $file['extension'] = $this->getExt($file['name']);
                $file['savepath'] = $savePath;
                $file['savename'] = $this->getSaveName($file);

                // 自动检查附件
                if ($this->autoCheck) {
                    if (! $this->check($file))
                        return false;
                }
                
                //保存上传文件
                if (! $this->save($file))
                    return false;

                //上传成功后保存文件信息，供其他地方调用
                unset($file['tmp_name'], $file['error']);
                $fileInfo[] = $file;
                $isUpload = true;
            }
        }
        if ($isUpload) {
            $this->uploadFileInfo = $fileInfo;
            return true;
        } else {
            $this->error = '没有选择上传文件';
            return false;
        }
    }



    /**
     * 取得上传文件的信息
     * @access public
     * @return array
     */
    function getUploadFileInfo() {
        return $this->uploadFileInfo;
    }

    /**
     * 取得最后一次错误信息
     * @access public
     * @return string
     */
    function getErrorMsg() {
        return $this->error;
    }

    /**
     * 上传一个文件
     * @access private
     * @param mixed $name 数据
     * @param string $value  数据表名
     * @return bool
     */
    private function save($file) {
        $filename = $file['savepath'] . $file['savename'];
        if (! $this->uploadReplace && is_file($filename)) {
            // 不覆盖同名文件
            $this->error = '文件已经存在！' . $filename;
            return false;
        }
        // 如果是图像文件 检测文件格式
        if (in_array(strtolower($file['extension']), array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf')) && false === getimagesize($file['tmp_name'])) {
            $this->error = '非法图像文件';
            return false;
        }
        if (! move_uploaded_file($file['tmp_name'], auto_charset($filename, 'utf-8', 'gbk'))) {
            $this->error = '文件上传保存错误！';
            return false;
        }
        return true;
    }

    /**
     * 转换上传文件数组变量为正确的方式
     * @access private
     * @param array $files  上传的文件变量
     * @return array
     */
    private function dealFiles($files) {
        $fileArray = array();
        foreach ( $files as $file ) {
            if (is_array($file['name'])) {
                $keys = array_keys($file);
                $count = count($file['name']);
                for($i = 0; $i < $count; $i ++) {
                    foreach ( $keys as $key )
                        $fileArray[$i][$key] = $file[$key][$i];
                }
            } else {
                $fileArray = $files;
            }
            break;
        }
        return $fileArray;
    }

    /**
     * 获取错误代码信息
     * @access protected
     * @param string $errorNo  错误号码
     * @return void
     */
    protected function error($errorNo) {
        switch ($errorNo) {
            case 1 :
                $this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
                break;
            case 2 :
                $this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
                break;
            case 3 :
                $this->error = '文件只有部分被上传';
                break;
            case 4 :
                $this->error = '没有文件被上传';
                break;
            case 6 :
                $this->error = '找不到临时文件夹';
                break;
            case 7 :
                $this->error = '文件写入失败';
                break;
            default :
                $this->error = '未知上传错误！';
        }
        return;
    }

    /**
     * 根据上传文件命名规则取得保存文件名
     * @access private
     * @param string $filename 数据
     * @return string
     */
    private function getSaveName($filename) {
        $rule = $this->saveRule;
        if (empty($rule)) { //没有定义命名规则，则保持文件名不变
            $saveName = $filename['name'];
        } else {
            if (function_exists($rule)) {
                //使用函数生成一个唯一文件标识号
                $saveName = $rule() . "." . $filename['extension'];
            } else {
                //使用给定的文件名作为标识号
                $saveName = $rule . "." . $filename['extension'];
            }
        }
        if ($this->autoSub) {
            // 使用子目录保存文件
            $saveName = $this->getSubName($filename) . '/' . $saveName;
        }
        return $saveName;
    }

    /**
     * 获取子目录的名称
     * @access private
     * @param array $file  上传的文件信息
     * @return string
     */
    private function getSubName($file) {
        $dir = date($this->dateFormat, time());

        if (! is_dir($file['savepath'] . $dir)) {
            mkdir($file['savepath'] . $dir);
        }
        return $dir;
    }

    /**
     * 检查上传的文件
     * @access private
     * @param array $file 文件信息
     * @return boolean
     */
    private function check($file) {
        if ($file['error'] !== 0) {
            //文件上传失败
            //捕获错误代码
            $this->error($file['error']);
            return false;
        }
        //文件上传成功，进行自定义规则检查
        //检查文件大小
        if (! $this->checkSize($file['size'])) {
            $this->error = '上传文件大小('.$file['size'].')不符！';
            return false;
        }

        //检查文件Mime类型
        if (! $this->checkType($file['type'])) {
            $this->error = '上传文件MIME类型('.$file['type'].')不允许！';
            return false;
        }
        //检查文件类型
        if (! $this->checkExt($file['extension'])) {
            $this->error = '上传文件类型('.$file['extension'].')不允许';
            return false;
        }

        //检查是否合法上传
        if (! $this->checkUpload($file['tmp_name'])) {
            $this->error = '非法上传文件('.$file['tmp_name'].')！';
            return false;
        }
        return true;
    }

    /**
     * 检查上传的文件类型是否合法
     * @access private
     * @param string $type 数据
     * @return boolean
     */
    private function checkType($type) {
        if (! empty($this->allowTypes))
            return in_array(strtolower($type), $this->allowTypes);
        return true;
    }

    /**
     * 检查上传的文件后缀是否合法
     * @access private
     * @param string $ext 后缀名
     * @return boolean
     */
    private function checkExt($ext) {
        if (! empty($this->allowExts))
            return in_array(strtolower($ext), $this->allowExts, true);
        return true;
    }

    /**
     * 检查文件大小是否合法
     * @access private
     * @param integer $size 数据
     * @return boolean
     */
    private function checkSize($size) {
        return ! ($size > $this->maxSize) || (- 1 == $this->maxSize);
    }

    /**
     * 检查文件是否非法提交
     * @access private
     * @param string $filename 文件名
     * @return boolean
     */
    private function checkUpload($filename) {
        return is_uploaded_file($filename);
    }

    /**
     * 取得上传文件的后缀
     * @access private
     * @param string $filename 文件名
     * @return boolean
     */
    private function getExt($filename) {
        $pathinfo = pathinfo($filename);
        return $pathinfo['extension'];
    }

} //类定义结束

function ruleNow() {
    return date('YmdHis') . rand(100, 999);
}

//<editor-fold defaultstate="collapsed" desc="auto_charset() 自动转换字符集 支持数组转换">
function auto_charset($fContents, $from, $to) {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && ! is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ( $fContents as $key => $val ) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    } else {
        return $fContents;
    }
}
//</editor-fold>
?>
