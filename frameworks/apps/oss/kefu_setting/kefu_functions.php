<?php

function fetchEnumsJs(&$array,$name,$id,$value) {
    $string = 'var '.$name.'= new Array();'.chr(13).chr(10);
    foreach($array as $k => $v) {
        $string .= $name.'['.$v[$id].']'.'= '.json_encode($v).';'.chr(13).chr(10);
    }
    return $string;
}
if ( ! function_exists('GetIP')) {
    function GetIP() {
        if(isset($_SERVER)) {
            if($_SERVER['HTTP_CLIENT_IP'] && strcasecmp($_SERVER['HTTP_CLIENT_IP'],'unkown'))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            elseif($_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'],'unknown'))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ;
                elseif($_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],'unknown'))
                        $ip = $_SERVER['REMOTE_ADDR'];
                    else
                        $ip = 'unkown';
        }else {
            if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
                $ip = getenv("HTTP_CLIENT_IP");
            elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
                    $ip = getenv("HTTP_X_FORWARDED_FOR");
                elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
                        $ip = getenv("REMOTE_ADDR");
                    elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
                            $ip = $_SERVER['REMOTE_ADDR'];
                        else
                            $ip = "unknown";
        }
        return $ip;
    }
}
function setCurrentArray(&$array,$colum,$val) {
    $found = array();
    foreach($array as $key => $value) {
        if($value[$colum] == $val) {
            $value['current'] = 1;
            $found = $value;
            $array[$key] = $value;
        }
    }
    return $found;
}

function referer($default = '?') {
    $DOMAIN = preg_replace("~^www\.~",'',strtolower(getenv('HTTP_HOST') ? getenv('HTTP_HOST') : $_SERVER['HTTP_HOST']));
    $referer=$_POST['referer']?$_POST['referer']:$_GET['referer'];
    if($referer=='')$referer=$_SERVER['HTTP_REFERER'];
    return $referer;
}


function showMessge($msg, $gourl, $onlymsg = 0, $limittime = 0,$obj) {
    header('Content-Type:text/html;charset=utf8');
    if (empty ( $GLOBALS ['cfg_phpurl'] ))
        $GLOBALS ['cfg_phpurl'] = '..';

    $htmlhead = "<html>\r\n<head>\r\n<title>".$obj->_L['tip_msg']."</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n";
    $htmlhead .= "<base target='_self'/>\r\n<style>div{line-height:160%;}</style></head>\r\n<body leftmargin='0' topmargin='0'>" . (isset ( $GLOBALS ['ucsynlogin'] ) ? $GLOBALS ['ucsynlogin'] : '') . "\r\n<center>\r\n<script>\r\n";
    $htmlfoot = "</script>\r\n</center>\r\n</body>\r\n</html>\r\n";

    $litime = ($limittime == 0 ? 1000 : $limittime);
    $func = '';

    if ($gourl == '-1') {
        if ($limittime == 0)
            $litime = 500;
        $gourl = $_SERVER['HTTP_REFERER'];
    }

    if ($gourl == '' || $onlymsg == 1) {
        $msg = "<script>alert(\"" . str_replace ( "\"", "“", $msg ) . "\");</script>";
    } else {
    //当网址为:close::objname 时, 关闭父框架的id=objname元素
        if (preg_match ( '/close::/', $gourl )) {
            $tgobj = trim ( eregi_replace ( 'close::', '', $gourl ) );
            $gourl = 'javascript:;';
            $func .= "window.parent.document.getElementById('{$tgobj}').style.display='none';\r\n";
        }

        $func .= "      var pgo=0;
      function JumpUrl(){
        if(pgo==0){ location='$gourl'; pgo=1; }
      }\r\n";
        $rmsg = $func;
        $rmsg .= "document.write(\"<br /><div style='width:450px;padding:0px;border:1px solid #D8E4EE;'>";
        $rmsg .= "<div style='padding:6px;font-size:12px;border-bottom:1px solid #D8E4EE;background:#D8E4EE url({$GLOBALS['cfg_phpurl']}/img/wbg.gif)';'><b>".$obj->_L['tip_msg']."！</b></div>\");\r\n";
        $rmsg .= "document.write(\"<div style='height:130px;font-size:14px;background:#ffffff'><br />\");\r\n";
        $rmsg .= "document.write(\"" . str_replace ( "\"", "“", $msg ) . "\");\r\n";
        $rmsg .= "document.write(\"";

        if ($onlymsg == 0) {
            if ($gourl != 'javascript:;' && $gourl != '') {
                $rmsg .= "<br /><a href='{$gourl}'>".$obj->_L['redirect_msg']."</a>";
                $rmsg .= "<br/></div>\");\r\n";
                $rmsg .= "setTimeout('JumpUrl()',$litime);";
            } else {
                $rmsg .= "<br/></div>\");\r\n";
            }
        } else {
            $rmsg .= "<br/><br/></div>\");\r\n";
        }
        $msg = $htmlhead . $rmsg . $htmlfoot;
    }
    echo $msg;
}

function strexists($haystack, $needle) {
    return !(strpos($haystack, $needle) === FALSE);
}
function upload_rule() {
    return str_replace('','.',microtime(true));
}
function str_exists($haystack,$needle) {
    $arg_list = func_get_args();
    while(($needle=$arg_list[++$i])!==null) {
        if(strpos($haystack,$needle)!==false)return true;
    }
    return false;
}

function buildQqueryString(&$array,$sp1=',',$sp2='|') {
    $ret = '';
    $sp = '';
    foreach($array as $key=>$value) {
        $ret .= $sp.$key.$sp1.$value;
        $sp = $sp2;
    }
    return $ret;
}

function parseQueryString(&$string) {
    $ret = array();
    if(!empty($string)) {
        $array1 = explode('|',$string);
        if(is_array($array1)) {
            foreach($array1 as $value) {
                list($k,$v) = explode(',',$value);
                $ret[$k] = $v;
            }
        }
    }

    return $ret;
}
function ajaxError($msg,$isexit=true) {
    global $result;
    $result ['status'] =0;
    $result ['msg'] = $msg;
    $isexit && exit(json_encode($result));
}

function ajaxSuccess($msg='',$isexit=true) {
    global $result;
    $result ['status'] =1;
    $result ['msg'] = $msg;
    $isexit && exit(json_encode($result));
}

function addslashes_deep($string, $force = 0, $strip = FALSE) {
    if(!MAGIC_QUOTES_GPC || $force) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = addslashes_deep($val, $force, $strip);
            }
        } else {
            $string = (is_numeric($string) ? $string : addslashes($strip ? stripslashes($string) : $string));
        }
    }
    return $string;
}

function showJsMsg($msg,$gourl=-1,$return=false) {

    $ret = '<script>alert(\''.$msg.'\');';
    if($gourl == -1) {
        $ret .= "history.back(-1)";

    } else {
        $ret .= 'location.href=\''.$gourl.'\';';
    }
    $ret.='</script>';

    if($return) {
        return $ret;
    } else {
        echo $ret;
    }
}

function getJsMsg($msg,$gourl=-1) {
    $ret = 'alert(\''.$msg.'\');';
    if($gourl == -1) {
        $ret .= "history.back(-1)";
    } else {
        $ret .= 'location.href=\''.$gourl.'\';';
    }

    return $ret;

}

function httpGet($url) {
    $urlarr = parse_url($url);
    $fp = fsockopen($urlarr['host'],80,$errorno,$errorstr,30);
    $cookie = buildQqueryString($_COOKIE,'=',';');
    fputs($fp,"GET {$urlarr['path']}?{$urlarr['query']} HTTP/1.1\r\n");
    fputs($fp,"Host:{$urlarr['host']}\r\n");
    fputs($fp,"Cookie:$cookie\r\n");
    fputs($fp,"Connection:Close\r\n\r\n");
    $ret = '';
    while(!feof($fp)) {
        $ret .= fread($fp,1024);
    }
    //   var_dump($ret);
    preg_match("/\r\n\r\n(.+)/is", $ret, $out);
    $ret = $out[1];
    $spos = strpos($ret,chr(13).chr(10));
    $ret = substr($ret,$spos);
    $epos = strrpos($ret,chr(10));
    $ret = substr($ret,0,strlen($ret)-7);
    return $ret;
}

function httpPost($url,$postdata) {
    $urlarr = parse_url($url);
    // print_r($urlarr);
    $fp = fsockopen($urlarr['host'],80,$errorno,$errorstr,30);
    $cookie = buildQqueryString($_COOKIE,'=',';');
    $postvar = $postdata.'&'.$urlarr['query'];
    fputs($fp,"POST {$urlarr['path']} HTTP/1.1\r\n");
    fputs($fp,"Content-Type:application/x-www-form-urlencoded\r\n");
    fputs($fp,"Content-Length:".strlen($postvar)."\r\n");
    fputs($fp,"Host:{$urlarr['host']}\r\n");
    fputs($fp,"Cookie:$cookie\r\n");
    fputs($fp,"Connection:Close\r\n\r\n");
    fputs($fp,$postvar."\r\n\r\n");
    $ret = '';
    while(!feof($fp)) {
        $ret .= fread($fp,1024);
    }
    preg_match("/\r\n\r\n(.+)/is", $ret, $out);
    $ret = $out[1];
    $spos = strpos($ret,chr(13).chr(10));
    $ret = substr($ret,$spos);
    $epos = strrpos($ret,chr(10));
    $ret = substr($ret,0,strlen($ret)-7);
    return $ret;
}


function checkMail($mail) {
    $ret = preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/',$mail);

    return $ret;
}
require_once LIB_PATH . '/simplelog.php';
class MyLog Extends SimpleLog {
    var $_db = null;
    var $_uploader = null;
    function sqlError() {
        $dbgsql = $this ->_db->debugSql();
        $sqlerror = $this ->_db->getError();
        self::error('Sql Error:'.$dbgsql.chr(13).chr(10).$sqlerror);
    }

    function uploadError() {
        self::error('Upload Error:'.$this -> _uploader->getErrorMsg());
    }

    function setDB(&$db) {
        $this -> _db = $db;

    }
    function setUploader(&$uploader) {
        $this -> _uploader = $uploader;

    }
}
?>
