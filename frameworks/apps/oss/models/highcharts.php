<?php
/**
 * 
 * @author Administrator
 *
 */
class highcharts {
	/**
	 * 本套工具所支持的自定样式名
	 * 
	 * 线性图line, 弧线图spline, 区域图area, 区域弧线图areaspline, 柱形图column, bar, 饼形图pie , 散点图scatter
	 * 
	 * load('model.highcharts');
	 * $chart = highcharts::getInstance();
	 * $chart->setChart( array('renderTo'=>"'container'", 'defaultSeriesType'=>"'line'") ); 				设置显示div标签id和报表类型   
	 * $chart->setTitle('text', "'平台每天的注册人数走势'"); 							             			设置报表标题
	 * $chart->setXaxis( array('type'=>'datetime', 'labels'=>'%y/%m/%d','tickInterval'=>24*3600*1000*1) );	设置横坐标格式
	 * $chart->setYaxis( array('min'=>0, 'title'=>array('text'=>"'人数'")) );				   				设置纵坐标名称
	 * $chart->setTooltip( array('formatter'=>array('%Y-%m-%d','人')) ); 					   				设置鼠标浮动提示数据格式
	 * $chart->setPlotoptions('line', '0');											             	 		设置报表数据是否显示
	 * $chart->setAjax('ajax', 1);																			是否ajax方式
	 * 
	 */
	private $pieLegend = 0;
	private static $chartAttr = array(
		'backgroundColor'		=> "'#FFFFFF'",
		'borderWidth'			=> 0,		
		'borderRadius'			=> 5,
		'renderTo'				=> null,
		'defaultSeriesType'		=> "'line'",
		'width'					=> null,
		'height'				=> null,
		'margin'				=> "[null]",
		'plotBackgroundColor'	=> null,
		'plotBorderColor'		=> null,
		'plotBorderWidth'		=> null, 	
		'shadow'				=> 0, 
		'reflow'				=> 1,
		'zoomType'				=> "''", 	
		'events'				=> "''",	
	);
	
	private static $titleAttr = array(
		'text'					=> "''",
		'align'					=> "'center'"	,
		'verticalAlign'			=> "''",
		'margin'				=> 50,
		'floating'				=> 0,
		'style'					=> "{color: '#3E576F',fontSize: '16px'}"
	);

	private static $subtitleAttr = array(
		'text'					=> "''",
		'align'					=> "'center'"
	);
	
	private static $xaxisAttr = array(
		'categories',
		'title',	
		'labels',
		'max',
		'min',
		'gridLineColor',
		'gridLineWidth',
		'lineColor',
		'lineWidth',
		'type',
		'maxZoom',
		'tickInterval',
		'tickPixelInterval',
		'tickWidth'
	);

	private static $yaxisAttr = array(
		'title',
		'max',
		'min',
		'text',
		'allowDecimals',
		'gridLineWidth'
	);
	
	private static $seriesAttr = array(
		'data',
		'name',
		'type',
		'series',
	);
	
	private static $plotoptionsAttr = array(
		'enabled',
		'allowPointSelect',
		'formatter'
	);

	private static $tooltipAttr = array(
		'enabled',
		'backgroundColor',
		'borderColor',
		'borderRadius',
		'shadow',
		'style',
		'formatter',
		'crosshairs',
		'shared',
	);

	private static $legendAttr = array(
		'layout'			=> "'horizontal'",
		'align'				=> "'center'",			
		'backgroundColor'   => "null",
		'borderColor'		=> "'#909090'",
		'borderRadius'		=> 5,
		'enabled'			=> 1,
		'floating'			=> 0,
		'shadow'			=> 0,
		'style'				=> "''",
		'borderWidth'		=> 1,
	);

	private static $creditsAttr = array(
		'enabled'
	);   
	
	private static $ajaxAttr = array(
		'ajax'
	);
	/**
	 * 自定义的样式
	 */
	private $chartCss = array();
	
	private $titleCss = array();
	
	private $subtitleCss = array();
	
	private $xaxisCss = array();
	
	private $yaxisCss = array();
	
	private $seriesCss = array();
	
	private $plotoptionsCss = array();
		
	private $tooltipCss = array();
	
	private $legendCss = array();

	private $creditsCss = array('enabled' => 0);
	
	
	
	private $chart = null;
	
	private $css = null;
	
	private $title = null;
	
	private $subtitle = null;
	
	private $xaxis = null;
	
	private $yaxis = null;
	
	private $tooltip = null;
	
	private $legend = null;
	
	private $plotOptions = null;
	
	private $series = null;
	
	private $credits = null;
	
	private static $instance;
	
	private $ajax = null;
	
	
 	public static function getInstance(){  
        if(empty(self::$instance)){
			self::$instance = new highcharts();
			return self::$instance;
        }  
        return self::$instance;  
    }
    
	/**
	 * 配置Css函数
	 * 
	 * @param $key
	 * @param $value
	 * @param $css
	 * @param $cssAttr
	 */
	public function setCss($key, $value, $css, $cssAttr) {
		if (is_array($key)){
			foreach ($key as $k=> $v) {
				if (is_array($v)) {
					$this->{$css}[$k] = $v;
				}
				if (array_key_exists($k, self::${$cssAttr})) {
					$this->{$css}[$k] = $v;
				}			
			}				
		}
		else {
			if (array_key_exists($key, self::${$cssAttr})) {
				$this->{$css}[$key] = $value;
			}			
		}	
		return $this;	
	}     

	public function setDefaultCss ($key, $value, $css, $cssAttr) {
		if (is_array($key)){
			foreach ($key as $k=> $v) {
				if (is_array($v)) {
					$this->{$css}[$k] = $v;
					//$this->setMutiArr($v);
				}
				if (in_array($k, self::${$cssAttr})) {
					$this->{$css}[$k] = $v;
				}			
			}				
		}
		else {
			if (in_array($key, self::${$cssAttr})) {
				$this->{$css}[$key] = $value;
			}			
		}	
		return $this;	
	}	
	/**
	 * 
	 * 设置图表chart
	 * 
	 * 此方法支持多值同时赋值, 赋值方式如下: 
	 * 1. 一组赋值 $highcharts->setChart('name', 'hx');
	 * 2. 多组赋值 $highcharts->setChart( array('a'=>1, 'b'=>2) );
	 * 
	 * $chart->setChart( array('renderTo'=>"'lineDiv'", 'defaultSeriesType'=>"'line'", 'zoomType'=>"'x'") );
	 * 表示設置線形圖line, 可拉伸變化參數x方向，和圖表顯示DIV標籤id  lineDiv
	 * @param $key
	 * @param $value
	 */
	public function setChart($key, $value = null) {
		return $this->setCss($key, $value, 'chartCss', 'chartAttr');	
	}
	
	/**
	 * 设置标题title
	 * 
	 * 此方法支持多值同时赋值, 赋值方式如下: 
	 * 1. 一组赋值 $highcharts->setTitle('name', 'hx');
	 * 2. 多组赋值 $highcharts->setTitle( array('a'=>1, 'b'=>2) );
	 * 
	 * @param $key
	 * @param $value
	 */
	public function setTitle($key, $value) {
		return $this->setCss($key, $value, 'titleCss', 'titleAttr');	
	}
	
	/**
	 * 设置副标题subtitle
	 * 
	 * 此方法支持多值同时赋值, 赋值方式如下: 
	 * 1. 一组赋值 $highcharts->setSubTitle('name', 'hx');
	 * 2. 多组赋值 $highcharts->setSubTitle( array('a'=>1, 'b'=>2) );
	 * 
	 * @param $key
	 * @param $value
	 */
	public function setSubTitle($key, $value) {
		return $this->setCss($key, $value, 'subtitleCss', 'subtitleAttr');		
	}

	/**
	 * 设置X轴xAxis
	 * 
	 * @param $key
	 * @param $value
	 */
	public function setXaxis($key, $value = null) {
		if (is_array($key)){
			foreach ($key as $k=> $v) {
				if($k == 'labels'){
					if ($v == '%') {
						$this->xaxisCss[$k] = " formatter :　function() { return this.value+'%';}";		
					}
					else {
						$this->xaxisCss[$k] = " formatter :　function() { return Highcharts.dateFormat('$v', this.value);}";	
					}
					
				}	
				else if (in_array($k, self::$xaxisAttr)) {
					$this->xaxisCss[$k] = $v;
				}			
			}				
		}
		else {
			if (in_array($key, self::$xaxisAttr)) {
				$this->xaxisCss[$key] = $value;
			}			
		}
		return $this;			
	}

	
	/**
	 * 设置Y轴yAxis
	 * 
	 * 此方法支持多值同时赋值, 赋值方式如下: 
	 * 1. 一组赋值 $highcharts->setYaxis('name', 'hx');
	 * 2. 多组赋值 $highcharts->setYaxis( array('a'=>1, 'b'=>2) );
	 * 
	 * 注：當$key, $value都為數組時（結合setTooltip()），如
	 * $key   = array('labels'=> '%', 'title'=>'', 'opposite'=>1);	 設置Y軸右邊 
	 * $value = array('labels'=>'台幣', 'title'=>''); 				設置Y軸左邊
	 * $chart->setYaxis( $key, $value );							兩個緯度
	 * 
	 * @param $key
	 * @param $value
	 */
	public function setYaxis($key, $value = null) {
		if (is_array($key) && is_array($value)) {
			$this->yaxis = <<<EOT
						yAxis : [{ //设置Y轴-第一个（如增幅） 
				            labels: { 
				                formatter: function() { //格式化标签名称 
				                    return this.value + '{$key[labels]}'; 
				                }, 
				                style: { 
				                    color: '#89A54E' //设置标签颜色 
				                } 
				            },
				            min : 0,
				            title: {text: '{$key[title]}'}, //Y轴标题设为空 
				            opposite: {$key[opposite]}  //显示在Y轴右侧，通常为false时，左边显示Y轴，下边显示X轴 
				 
				        }, 
				        { //设置Y轴-第二个（如金额） 
				            gridLineWidth: 1,  //设置网格宽度为0，因为第一个Y轴默认了网格宽度为1 
				            title: {text: '{$value[title]}'},//Y轴标题设为空 
				            min : 0,
				            labels: { 
				                formatter: function() {//格式化标签名称 
				                    return this.value + ' {$value[labels]}'; 
				                }, 
				                style: { 
				                    color: '#4572A7' //设置标签颜色 
				                } 
				            } 
				 
				        }], 			
EOT;
		}
		else {
			return $this->setDefaultCss($key, $value, 'yaxisCss', 'yaxisAttr');
		}
	}

	
	/**
	 * 设置数据列选项series
	 * 默認為 series: [] 不用設置
	 * 
	 * @param $key
	 * @param $value
	 */
	public function setSeries($key, $value) {
		if (is_array($key)){
			foreach ($key as $k=> $v) {
				if (is_array($v)) {
					$this->seriesCss[$k] = $v;
				}
				if ($k == 'series') {
					$this->series = 'series : ' . $v .' ,';	
				}
				else if (in_array($k, self::$seriesAttr)) {
					$this->seriesCss[$k] = $v;
				}			
			}				
		}
		else {
			if ($key == 'series' && $this->chartCss['defaultSeriesType'] == "'pie'") {
				$this->series = 'series : [' . $value .'] ,';	
			}
			else if ($key == 'series') {
				$this->series = 'series : ' . $value .' ,';	
			}
			else if (in_array($key, self::$seriesAttr)) {
				$this->seriesCss[$key] = $value;
			}			
		}
		return $this;			
	}

	/*
	 * 判斷是否ajax方法顯示圖表
	 * $chart->setAjax('ajax', $value); $value為1表示ajax方法顯示
	 */
	public function setAjax($key, $value) {
		if (in_array($key, self::$ajaxAttr)) {
			$this->$key = $value;
		}
		return $this;
	}
	/**
	 * 
	 * 设置图例选项legend
	 * 
	 * 默認不用設置，已經初始化默認樣式
	 * 
	 * 此方法支持多值同时赋值, 赋值方式如下: 
	 * 1. 一组赋值 $highcharts->setLegend('name', 'hx');
	 * 2. 多组赋值 $highcharts->setLegend( array('a'=>1, 'b'=>2) );
	 * 
	 * @param $key
	 * @param $value
	 */
	public function setLegend($key, $value) {
		return $this->setCss($key, $value, 'legendCss', 'legendAttr');		
	}

	/**
	 * 设置logo credits
	 * 
	 * 默認不用設置，已去除logo
	 * 
	 * 此方法支持多值同时赋值, 赋值方式如下: 
	 * 1. 一组赋值 $highcharts->setCredits('name', 'hx');
	 * 2. 多组赋值 $highcharts->setCredits( array('a'=>1, 'b'=>2) );
	 * 
	 */
	public function setCredits($key, $value = null) {
		return $this->setDefaultCss($key, $value, 'creditsCss', 'creditsAttr');		
	}

	
	/**
	 * 设置数据点选项plotOptions
	 * 
	 * 1. 饼形图数据以百分号显示  $highcharts->setPlotoptions('pie','%');
	 * 2. 线形图数据显示                     $highcharts->setPlotoptions('line','1');						
	 * 
	 */
	public function setPlotoptions($key, $value) {
		if ($key) {			
			switch ($key) {
				case 'pie' : 
					
					if ($value == '%') {
						$this->plotoptions = <<<EOT
			                pie: {
			                    allowPointSelect: true,
			                    selected : true,
			                    visible: 0,
			                    cursor: 'pointer',
			                    showInLegend: {$this->pieLegend},
			                    dataLabels: {
			                        enabled: true,
			                        color: '#000000',
			                        connectorColor: '#000000',
			                        formatter: function() {
			                            return '<b>'+ this.point.name +'</b>: '+ twoDecimal(this.y) +' {$value}';
			                        }
			                    }
			                }		
EOT;
					}
					else {
						$this->plotoptions = <<<EOT
			                pie: {
			                    allowPointSelect: true,
			                    cursor: 'pointer',
			                    dataLabels: {
			                        enabled: true,
			                        color: '#000000',
			                        connectorColor: '#000000',
			                        formatter: function() {
			                            return '<b>'+ this.point.name +'</b>: '+ this.y +' {$value}';
			                        }
			                    }
			                }		
EOT;
					}
					break;
				case 'line' :
					$this->plotoptions = <<<EOT
						line: {
 	    					lineWidth: 2,
 	    					states: {
 	    						hover: {
 	    							lineWidth: 4
 	    						}
 	    					},
 	    					dataLabels : {
 	    						enabled : {$value}
							},
 	    					marker: {
 	    						enabled: false,
 	    						states: {
 	    							hover: {
 	    								enabled: true,
 	    								symbol: 'circle',
 	    								radius: 5,
 	    								lineWidth: 3
 	    							}
 	    						}
 	    					}
 	    				}					
EOT;
					break;
				case 'spline' :
					$this->plotoptions = <<<EOT
						spline: {
 	    					lineWidth: 2,
 	    					states: {
 	    						hover: {
 	    							lineWidth: 4
 	    						}
 	    					},
 	    					dataLabels : {
 	    						enabled : {$value},
 	    						align:"right"
							},
 	    					marker: {
 	    						enabled: false,
 	    						states: {
 	    							hover: {
 	    								enabled: true,
 	    								symbol: 'circle',
 	    								radius: 5,
 	    								lineWidth: 3
 	    							}
 	    						}
 	    					}
 	    				}					
EOT;
					break;					
				case 'column' :
					$this->plotoptions = <<<EOT
						column: {
 	    					dataLabels : {
 	    						enabled : {$value}
							}
 	    				}					
EOT;
					break;
				case 'bar' :
					$this->plotoptions = <<<EOT
				        bar: {
		                    dataLabels: {
		                        enabled: {$value}
		                    }
		                }					
EOT;
					break;				
			}
		}
		return $this;		
	}
	
	/**
	 * 
	 * 设置数据点提示框tooltip
	 * 
	 * 注：當$key, $value都為數組形式時（結合setYaxis()），如:
	 * $key   = array('金額','台幣'); 		表示X軸圖例顯示金額，Y軸單位為台幣
	 * $value = array('增幅','%');    		表示X軸圖例顯示增幅，Y軸單位為百分比%
	 * $chart->setTooltip( $key, $value); 	表示有兩個維度顯示，如每個月的充值金額柱形圖和增長率走勢曲線
	 * 
	 * @param $key
	 * @param $value
	 */
	public function setTooltip($key, $value) {
		if (is_array($key) && is_array($value)) {
			$this->tooltip = <<<EOT
					tooltip:{ 
					            formatter: function() { 
					                var unit = { 
					                    '{$key[0]}'  : '{$key[1]}', 
					                    '{$value[0]}': '{$value[1]}' 
					                }[this.series.name]; 
					                return '' + this.x + ': ' + this.y + ' ' + unit; 
					            } 
		        	},
EOT;
		}	
		else if (is_array($key)){
			foreach ($key as $k=> $v) {
				if($k == 'formatter'){
					if (is_array($v)){
						if ($this->chartCss['defaultSeriesType'] == "'line'" || $this->chartCss['defaultSeriesType'] == "'spline'") {
							$this->tooltipCss[$k] = " function() {
														var s = '<b>'+ Highcharts.dateFormat('$v[0]',this.x) +'</b>';
               	 										$.each(this.points, function(i, point) {
										                    s += '<br/>'+ '<b style=\"color:'+point.series.color+';\">'+point.series.name +'</b>'+': '+
										                        point.y +' $v[1]';
										                });
                										return s;}";
						}						
					}
					else if ($this->chartCss['defaultSeriesType'] == "'pie'") {
						if ($v == '%') {
							$this->tooltipCss[$k] = " function() { return '<b>'+ this.point.name +'</b>: '+ twoDecimal(this.y) +'$v';}";	
						}
						else {
							$this->tooltipCss[$k] = " function() { return '<b>'+ this.point.name +'</b>: '+ this.y +'$v';}";	
						}	
					}
					else if ($this->chartCss['defaultSeriesType'] == "'column'" || $this->chartCss['defaultSeriesType'] == "'bar'") {
							$this->tooltipCss[$k] = " function() {return '<b>'+ this.series.name +'</b><br/>'+this.x+': '+ this.y;}";
					}
					else {
							$this->tooltipCss[$k] = " function() {return '<b>'+ this.point.category +' : '+ twoDecimal(this.y) +'$v';}";	
					}
				}		
				else if (in_array($k, self::$tooltipAttr)) {
					$this->tooltipCss[$k] = $v;
				}			
			}				
		}
		else {
			if (in_array($key, self::$tooltipAttr)) {
				if ($this->chartCss['defaultSeriesType'] == "'line'" || $this->chartCss['defaultSeriesType'] == "'spline'") {
						$this->tooltipCss[$k] = " function() {return '<b>'+ this.series.name +'</b><br/>'+Highcharts.dateFormat('$v', this.x)+': '+ this.y;}";	
				}						
				else if ($this->chartCss['defaultSeriesType'] == "'pie'") {
					$this->tooltipCss[$k] = " function() { return '<b>'+ this.point.name +'</b>: '+ this.percentage +'$v';}";	
				}
				else if ($this->chartCss['defaultSeriesType'] == "'column'" || $this->chartCss['defaultSeriesType'] == "'bar'") {
					$this->tooltipCss[$k] = " function() {return '<b>'+ this.series.name +'</b><br/>'+this.x+': '+ this.y;}";
				}				
				$this->tooltipCss[$key] = $value;
			}			
		}
		return $this;			
	}
		
	/**
	 * 
	 * 返回json数据
	 * 
	 * @param $array
	 */
	public function getJson($array) {
		return str_replace("\"", '', json_encode($array));
	}
	
	/**
	 * 获取chart
	 * 
	 */
	public function getChart() {
		// chart
		$mergeCss = array_merge(self::$chartAttr, $this->chartCss);
		if (count($mergeCss)) {
			$this->chart = 'chart : ' . $this->getJson($mergeCss).' ,';	
		}
		return $this->chart;		
	}
	
	/**
	 * 获取title
	 * 
	 */
	public function getTitle() {
		// title
		$mergeCss = array_merge(self::$titleAttr, $this->titleCss);
		if (count($mergeCss)) {
			$this->title = 'title : ' . $this->getJson($mergeCss).' ,';		
		}	
		return $this->title;	
	}

	/**
	 * 获取subtitle
	 * 
	 */
	public function getSubtitle() {
		// subtitle
		$mergeCss = array_merge(self::$subtitleAttr, $this->subtitleCss);
		if (count($mergeCss)) {
			$this->subtitle = 'subtitle : ' . $this->getJson($mergeCss).' ,';			
		}	
		return $this->subtitle;	
	}
	
	/**
	 * 获取xAxis
	 * 
	 */
	public function getXaxis() {
		// xAxis
		if (count($this->xaxisCss)) {
			foreach ($this->xaxisCss as $key => $value) {
				if ($key == 'labels') {
					$this->xaxis.= " $key : { $value },";	
				}
				else if ($key == 'categories') {
					$this->xaxis.= " $key : $value ,";	
				}
				else if (is_string($value)) {
					$this->xaxis.= " $key : '$value',";		
				}
				else {
					$this->xaxis.= " $key : $value ,";	
				}
			}			
		}	
		return 'xAxis : {' . substr($this->xaxis, 0, -1) . '},';
	}
	
	/**
	 * 获取yAxis
	 */
	public function getYaxis() {
		// yAxis
		if (count($this->yaxisCss)) {
			$this->yaxis = 'yAxis : ' . $this->getJson($this->yaxisCss).' ,';			
		}	
		return $this->yaxis;		
	}

	/**
	 * 获取tooltip
	 * 
	 */
	public function getTooltip() {
		// tooltip
		if (count($this->tooltipCss) > 0) {
			foreach ($this->tooltipCss as $key => $value) {
				if ($key == 'formatter') {
					$this->tooltip.= " $key : $value ,";	
				}
				else if (is_string($value)) {
					$this->tooltip.= " $key : '$value',";		
				}
				else {
					$this->tooltip.= " $key : $value ,";	
				}
			}
			return 'tooltip : {' . substr($this->tooltip, 0, -1) . '},';			
		}	
		else {
			return $this->tooltip;
		}
	}
	
	/**
	 * 获取legend
	 */
	public function getLegend() {
		// legend
		$merge = array_merge(self::$legendAttr,$this->legendCss);
		if (count($merge)) {
			$this->legend = 'legend : ' . $this->getJson($merge).' ,';	
		}
		return $this->legend;	
	}
	
	/**
	 * 获取series
	 */
	public function getSeries() {
		// series
		if (count($this->seriesCss) == 1) {
			$this->series = 'series : [' . $this->getJson($this->seriesCss).'] ,';	
		}
		else {
			$this->series = 'series : ' . $this->getJson($this->seriesCss).' ,';
		}
		if (empty($this->series)) {
			return 'series : [],';	
		}
		else {
			return $this->series;
		}	
	}

	/**
	 * 获取credits logo
	 */
	public function getCredits() {
		// credits
		if (count($this->creditsCss)) {
			$this->credits = 'credits : ' . $this->getJson($this->creditsCss).' ,';	
		}
		return $this->credits;	
	}

	/**
	 * 获取划分选项Plotoptions
	 */
	public function getPlotoptions() {
		return 'plotOptions : { '.$this->plotoptions.' },';	
	}

	public function setPieLegend($value) {
		$this->pieLegend = $value;
	}
	
	// 設置chart X軸時間間隔
	public function setTimeLength($diff, $chart) {
		if ($diff == 1) {
			$chart->setXaxis( array('type'=>'time', 'labels'=>'%H', 'tickInterval'=>3600 * 1000 * 1) );
			$chart->setTooltip( array('formatter'=> array( '%Y-%m-%d,%H:00', "" )) );		
		}
		else if ($diff <= 20 ) {
			$chart->setXaxis( array('type'=>'datetime', 'labels'=>'%m/%d', 'tickInterval'=>86400000 * 1) );					
		}
		else if ($diff > 20 && $diff <= 31) {
			$chart->setXaxis( array('type'=>'datetime', 'labels'=>'%m/%d', 'tickInterval'=>86400000 * 2) );					
		}
		else if ($diff > 31 && $diff < 90) {
			$chart->setXaxis( array('type'=>'datetime', 'labels'=>'%m/%d', 'tickInterval'=>86400000 * 7) );
		}			
		else if ($diff >= 90 && $diff < 180) {
			$chart->setXaxis( array('type'=>'datetime', 'labels'=>'%m/%d', 'tickInterval'=>86400000 * 7) );	
		}
		else if ($diff >= 180 && $diff < 300) {
			$chart->setXaxis( array('type'=>'datetime', 'labels'=>'%m/%d', 'tickInterval'=>86400000 * 14) );
		}
		else if ($diff >= 300) {
			$chart->setXaxis( array('type'=>'datetime', 'labels'=>'%Y/%m/%d', 'tickInterval'=>86400000 * 30) );
		}		
	}
	/**
	 * 根据请求输出样式
	 */
	public function toString() {
		$str = '';
		$css = '';
		$sign = 0;
		$ajax = '';
		$css = substr($this->getChart() . $this->getTitle() . $this->getSubtitle() . $this->getXaxis() . $this->getYaxis() . $this->getSeries().$this->getLegend(). $this->getCredits() . $this->getTooltip(). $this->getPlotoptions(), 0, -1);
		if ($this->xaxisCss['type'] == 'time') {
			$sign = 1;
		}
		// 是否ajax請求
		if ($this->ajax == 1) {
			$ajax =<<<EOT
			if (data.temp == 1) {
	            $.each(data.data, function(key, value) {
                    var series = {};
                    series.name = key;
                    series.data = value;
                    options.series.push(series); 
                });
        	}
	        else if (data.temp == 2) {
		            $.each(data.data, function(key, value) {
	                    var series = {};
	                    series.name = key;
	                    $.each(value, function(k, v) {
	                    	series.type = k;
	                    	if ( k == 'column') {
	                    		series.yAxis = 1;
							}
	                    	series.data = v;
						});
	                    options.series.push(series); 
	                });        
			}
EOT;
		}
		$str =<<<EOT
	    	    var options = {
    	        		{$css}
    	    	};
    			Highcharts.setOptions({ 
    			    global: {
    			        useUTC: {$sign} 
    			    } 
    			});   	    	
				{$ajax}
                new Highcharts.Chart(options);	
EOT;
		return $str; 		
	}
}
