<?php
/**
 * 分页類，默認表單提交方式 
 * 默認： C('VAR_PAGE', 'page');
 * 		 C('AJAX_FUNCTION', '');
 * 如需支持ajax無刷新分页 
 * 需配置如：  C('VAR_PAGE', 'page'); 
 * 			 C('AJAX_FUNCTION', 'goNext');
 * 
 * load('model.page');
 * $page = new page($count, $listRows); //$count 記錄總數   $listRows 每页顯示行數
 * (example: SELECT * FROM `table_name` LIMIT $page->firstRow, $page->listRows )
 * $show = $page->show();				// 分页字符串

 * // 如有搜索查詢參數：
 * // example:
 * $map = array (
 * 		'start_date' => $start,
 * 		'end_date'   => $end,
 * );
 * foreach ($map as $key=> $value) {
 * 		if ($value) {
 * 			$page->parameter .= "&$key=".urlencode($value);
 * 		}
 * }
 */

class page {
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 默认列表每页显示行数
    public $listRows = 10;
    // 起始行数
    public $firstRow	;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config  =	array('header'=>'条记录','prev'=>'上一页','first'=>'第一页','next'=>'下一页','last'=>'尾页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage%  %first%  %prePage%  %linkPage%  %downPage% %nextPage%  %end%');

	 /**
     +----------------------------------------------------------
     * ajax分页函数
     +----------------------------------------------------------
     * @var integer
     * @access protected
     +----------------------------------------------------------
     */
    protected $ajaxFunc =""  ;
    
    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows='',$parameter='') {
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        if(!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = !empty($_GET[C('VAR_PAGE')])?intval($_GET[C('VAR_PAGE')]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
        return $this->ajaxFunc=C('AJAX_FUNCTION');
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }
    
    public function __set($name,$value){
        $this->$name=$value;
    }
	
    public function __get($name){
        return  $this->$name;
    }
    
	public function getHref($url,$page)
	{
		if(empty($this->ajaxFunc)){
			$href=$url."&".C('VAR_PAGE')."=".$page;
			
		}else{
			$href="javascript:".$this->ajaxFunc."(".$page.");";
		}
		return $href;
	}    

    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p = C('VAR_PAGE');
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href='".$this->getHref($url,$upRow)."'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a href='".$this->getHref($url,$downRow)."'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<a href='".$this->getHref($url,$preRow)."' >上".$this->rollPage."页</a>";
            $theFirst = "<a href='".$this->getHref($url,1)."' >".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='".$this->getHref($url,$nextRow)."' >下".$this->rollPage."页</a>";
            $theEnd = "<a href='".$this->getHref($url,$theEndRow)."' >".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "&nbsp;<a href='".$this->getHref($url,$page)."'>&nbsp;".$page."&nbsp;</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "&nbsp;<span class='current'>".$page."</span>";
                }
            }
        }
        $pageStr	 =	 str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%first%','%prePage%','%linkPage%','%nextPage%','%downPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$theFirst,$prePage,$linkPage,$nextPage,$downPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }
}