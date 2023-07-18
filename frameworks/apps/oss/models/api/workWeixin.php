<?php
/**
+----------------------------------------------------------
 * 封装企业微信相关接口的对象
+----------------------------------------------------------
 * @author heyonzghen
 * @version 2023.6.1
+---------------------------------------------------------- 
 */
class workWeixin
{

    public $_appAccessToken;
	public $_contactAccessToken;
	public $_conversationAccessToken;

    /**
     *
     * @param [string] $corpId 企业ID，【注意】企业ID和应用ID不一样，避免混淆使用了
     * @param [string] $appSecret   自建应用Secret
     * @param [string] $contactSecret 通讯录同步Secret
     */
    function __construct($corpId, $appSecret, $contactSecret = '', $conversationSecret = '') 
    {
        // 获取自建应用access_token
		$this->_appAccessToken = $this->getAccessToken($corpId, $appSecret);

        // 根据通讯录同步secret获取access_token
        if ($contactSecret) {
            $this->_contactAccessToken = $this->getAccessToken($corpId, $contactSecret);
        }

        // 根据会话内容存档应用secret获取access_token
        if ($conversationSecret) {
            $this->_conversationAccessToken = $this->getAccessToken($corpId, $conversationSecret);
        }
    }

    /**
	 * 获取access_token
	 * 
	 * 【说明】
     * 普通企业内部应用 secret 只能进行“查询”、“邀请”等非写操作，而且只能操作应用可见范围内的通讯录。
     * 要对企业通讯录进行更完整的管理，需要使用通讯录专有的 secret。
     * 代开发应用获取access_token，应使用自建应用获取access_token的接口，而非第三方应用获取access_token的接口。
     * 
     * 【IP白名单】
     * 若调用者是第三方应用或服务商代开发应用，请确认该IP已经配置到“服务商管理后台”-“服务商信息”-“基本信息”-“IP白名单”。
     * 配置完可信IP之后，需要1分钟后才生效。
     * 
	 */
	public function getAccessToken($corpId, $secret){

        //TODO: access_token 应该全局存储与更新，以下代码以写入到文件中做示例                      
		//TODO: 每个应用的access_token应独立存储，此处用secret作为区分应用的标识

		$time = time();
        $path = C('APP_LIST_PATH').'oss/models/cache/'.$secret.'.php';
		$tokenConfig = include($path);

		if ($tokenConfig && ($time < $tokenConfig['expire_time']) ) {

			// access_token在有效期内
			$access_token = $tokenConfig['access_token'];

            // echo "<pre>";
            // var_dump('**********');
            // var_dump($access_token);
            // exit;

		} else {

			// access_token第一次获取或者过期
			$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$corpId&corpsecret=$secret";

			$outputJson = httpRequest($url);
			$output = json_decode($outputJson, true);

            // echo "<pre>";
            // var_dump($output);
            // exit;

            // array(4) {
            //     ["errcode"]=>
            //     int(0)
            //     ["errmsg"]=>
            //     string(2) "ok"
            //     ["access_token"]=>
            //     string(214) "PeOhwfX5MTOuqh6b1gZ-TRJehqrdrMR-AU2uP1G4AX1LUIwtWidEZanJFHF3JB1bv7dRRb9ERTQcBRSVuxFkoUTzSfshhD94ONRO0ujEvCSJdB8rJhbjTOp4CtpZ7GbvOJ_MC87Zj1c0Q56Cu-WWWJ7dhyLyqtJA7H0Ci9SxDGX7g_pX8cOnleAUxVJiD2pDNiWhj39Rnx34gLBZiV2fyQ"
            //     ["expires_in"]=>
            //     int(7200)
            //   }

            if ($res['errcode'] == 0) {

                // access_token保存到配置文件
                $expire_time = $time + 7000; // expires_in默认为7200s，可以提前200s获取最新access_token
                $data = array(
                    'access_token' => $output['access_token'],
                    'expire_time'  => $expire_time
                );

                // file_put_content 不能直接将数组写入文件，需要使用var_export函数把数组变成字符串，把数组变成字符串后再写入文件。注意var_export函数第2个参数设置为true，将输出结果返回给$config变量
                $config  = "<?php";
                $config .= "\n";
                $config .= "return ";
                $config .= var_export($data, true);
                $config .= ";";
                file_put_contents($path, $config);

                $access_token = $output['access_token'];

            }
            
		}

        // echo "<pre>";
        // var_dump($access_token);
        // exit;

		return $access_token;
	}

    /**
	 * 获取指定企业成员微信添加的客户userid列表
	 * 
	 * @param mixed $access_token 
	 * @param mixed $userid 企业成员的userid
	 */
	public function getExternalUserid($userid)
	{
		// echo "<pre>";
		// var_dump($access_token);
		// var_dump($userid);
		// exit;
        
		$url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/list?access_token=".$this->_appAccessToken."&userid=$userid";
		$resJson = httpRequest($url);
		$res = json_decode($resJson, true);
		// echo "<pre>";
		// var_dump($url);
		// var_dump($res);
		// exit;
		if ($res['errcode'] == 0) {

			return $res["external_userid"];

		}

		// external_userid : 外部联系人的userid列表, 也就是当前企业成员微信号添加的客户userid列表

		// array(3) {
		// 	["errcode"]=>
		// 	int(0)
		// 	["errmsg"]=>
		// 	string(2) "ok"
		// 	["external_userid"]=>
		// 	array(3) {
		// 		[0]=>
		// 		string(32) "woFrvHCQAA_CA2_E5Z1TJqw5G3UEuuJw"
		// 		[1]=>
		// 		string(32) "wmFrvHCQAATywPBdmDPAyGIrBTY5ARtg"
		// 		[2]=>
		// 		string(32) "wmFrvHCQAAs_grwhgKbYa5LGmEmkBICQ"
		// 	}
		// }
	}

    /**
	 * 获取配置了客户联系功能的成员列表
	 * 
	 * @param mixed $access_token 调用接口凭证
	 */
	public function getFollowuserList()
	{
		$url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/get_follow_user_list?access_token=".$this->_appAccessToken;

		$resJson = httpRequest($url);
		$res = json_decode($resJson, true);

		// echo "<pre>";
		// var_dump($res);
		// exit;

		if ($res['errcode'] == 0) {
			return $res['follow_user'];
		}

	}

    /**
	 * 获取客户详情
     * 
     * 【注意】
	 * 接口调用成功的必要条件是客户存在于服务人员的外部联系人好友列表中，有以下情况会导致报84061错误：
	 * 1) 如果客户删除了服务人员，此时是还存在单向好友关系，可以调用客户联系相关接口。反之，如果是服务人员删除了客户，则不再存在好友关系，无法调用接口。
	 * 2) 服务人员开启了免验证的情况下，客户可以跟服务人员进行会话，但是此时并没有真正添加为好友关系，需要服务人员添加好友后才可以调用接口。
	 * 
	 * @param mixed access_token
	 * @param mixed external_userid
	 * @param mixed cursor 上次请求返回的next_cursor，也就是上次的页数。当客户在企业内的跟进人超过500人时需要使用cursor参数进行分页获取。
	 */
	public function externalcontact($external_userid, $cursor = "")
	{
		$url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/get?access_token=".$this->_appAccessToken."&external_userid=$external_userid&cursor=$cursor";

		$resJson = httpRequest($url);
		$res = json_decode($resJson, true);

		// echo "<pre>";
		// var_dump($url);
		// var_dump($res);
		// exit;

		if ($res['errcode'] == 0) {
			return $res;
		}

	}

    /**
     * 修改客户备注信息
     */
    public function updateCustomerRemark($data)
    {
        // 上报修改信息到企业微信
        $url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/remark?access_token=".$this->_appAccessToken;

        // $body = '{
        // 	"userid":"zhangsan",
        // 	"external_userid":"woAJ2GCAAAd1asdasdjO4wKmE8Aabj9AAA",
        // 	"remark":"备注信息",
        // 	"description":"描述信息",
        // 	"remark_company":"腾讯科技",
        // 	"remark_mobiles":[
        // 		"13800000001",
        // 		"13800000002"
        // 	],
        // 	"remark_pic_mediaid":"MEDIAID"
        // }';

        $bodyArray = array(
            "userid" => $data["userid"],
            "external_userid" => $data["external_userid"],
            "remark" => $data["remark"],
            "description" => $data["description"],
            "remark_company" => $data["remark_company"],
            "remark_mobiles" => $data["remark_mobiles"],
        );
        $body = json_encode($bodyArray) ;

        $resJson = httpRequest($url, $body, '', 'json');
        $res = json_decode($resJson, true);

        return $res;

    }

        /**
	 * 批量获取客户详情
	 * 
	 * @param mixed access_token
	 * @param mixed userid_list 数组类型，企业成员的userid列表，一次最多支持100个
	 * @param mixed cursor 上次请求返回的next_cursor，也就是上次的页数。当客户在企业内的跟进人超过500人时需要使用cursor参数进行分页获取。
	 * @param mixed limit 返回的最大记录数，整型，最大值100，默认值50，超过最大值时取最大值
	 * 
	 */
	public function batchGetExternalcontact($userid_list, $cursor = "", $limit = 100)
	{
		$url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/batch/get_by_user?access_token=".$this->_appAccessToken;

		// $body = '{"userid_list":["HeYongZhen","cr7"],"cursor":"'.$cursor.'","limit":'.$limit.'}';

		$body = json_encode(
					array(
						"userid_list" => $userid_list,
						"cursor" => (string)$cursor,
						"limit" => $limit
					)
				);
		// {"userid_list":["HeYongZhen","cr7"],"cursor":"","limit":100}

		$resJson = httpRequest($url, $body, '', 'json');
		$res = json_decode($resJson, true);

		// echo "<pre>";
		// var_dump($body);
		// var_dump($res);
		// exit;
		
		if ($res['errcode'] == 0) {
			return $res['external_contact_list'];
		}
	}

    /**
     * 分配在职成员的客户
     * 
     *【注意】
     * 不能同时转接不同企业成员的客户，一次只能把同一个企业成员的一个或者多个客户转接分配给同一个接替企业成员。
     * 为保障客户服务体验，90个自然日内，在职成员的每位客户仅可被转接2次。
     */
    public function transferCustomer($data)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/externalcontact/transfer_customer?access_token=".$this->_appAccessToken;

        // $data = '{
        //     "handover_userid": "zhangsan",
        //     "takeover_userid": "lisi",
        //     "external_userid":
        //     [
        //         "woAJ2GCAAAXtWyujaWJHDDGi0mACAAAA",
        //         "woAJ2GCAAAXtWyujaWJHDDGi0mACBBBB"
        //      ],
        //     "transfer_success_msg":"您好，您的服务已升级，后续将由我的同事李四@腾讯接替我的工作，继续为您服务。"
        //  }';
        
        $dataArray = array(
            "handover_userid" => $data["handover_userid"], // 原跟进成员的userid
            "takeover_userid" => $data["takeover_userid"], // 接替成员的userid
            "external_userid" => $data["external_userid"], // 类型为数组，客户的external_userid列表，每次最多分配100个客户
            // "transfer_success_msg" => "您好，您的服务已升级，后续将由我的其他同事接替我的工作，继续为您服务。"
        );
        $body = json_encode($dataArray);

		$resJson = httpRequest($url, $body, '', 'json');
		$res = json_decode($resJson, true);

        // 记录在职转接操作日志
		error_log("\n\n".date("[Y-m-d H:i:s]").":\n". "transferCustomer res: ". $resJson. "\n\n", 3, C('DEDE_DATA_PATH')."/logs/transferCustomer_".date('Ymd').".txt");

        // echo "<pre>";
		// var_dump($body);
		// var_dump($res);
		// exit;

        // array(3) {
        //     ["errcode"]=>
        //     int(0)
        //     ["errmsg"]=>
        //     string(2) "ok"
        //     ["customer"]=>
        //     array(1) {
        //       [0]=>
        //       array(2) {
        //         ["errcode"]=>
        //         int(0)
        //         ["external_userid"]=>
        //         string(32) "wmFrvHCQAAWBOh3HVu3OCzcFL-G6KcQQ"
        //       }
        //     }
        //   }

        // $res["customer"]["errcode"]为40129表示当前客户正在转接中

        if ($res["errcode"] == 0) {
            return $res["customer"];
        }
        
    }

    /**
     * 
     * 获取成员ID列表
     * 
     * 获取企业成员的userid与对应的部门ID列表
     * 
     * @param string $cursor 用于分页查询的游标，字符串类型，由上一次调用返回，首次调用不填
     * @param int $limit 分页，预期请求的数据量，取值范围 1 ~ 10000
     * 
     * 【说明】
     * 1、写通讯录接口，只能由通讯录同步助手的access_token来调用。同时需要保证通讯录同步功能是开启的。
     * 2、通讯录同步助手的access_token，仅用于同步通讯录，不能用于发消息
     * 
     * 【权限说明】
     * 仅支持通过“通讯录同步secret”调用。
     * 
     */
    public function getEmployeeUserid($cursor = '', $limit = 10000)
    {

        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/list_id?access_token=".$this->_contactAccessToken;

        // $body = '{
        //     "cursor": "xxxxxxx",
        //     "limit": 10000
        // }';

        $body = json_encode(array(
            'cursor' => $cursor,
            'limit' => $limit
        ));

        $resJson = httpRequest($url, $body, '', 'json');
		$res = json_decode($resJson, true);

        // echo "<pre>";
        // var_dump($res);
        // exit;

        if ($res['errcode'] == 0) {
			return $res['dept_user'];
		} 

    }

    /**
     * 读取成员
     * 获取可见范围内的成员详情信息
     * 
     * 【注意】
     * 1、必须要使用自建应用的access_token才能调用此接口。因为从2022年8月15日10点开始，除了自建应用和代开发应用，“企业管理后台 - 管理工具 - 通讯录同步”的新增IP将不能再调用此接口。
     * 简单理解就是之前是通过通讯录同步的access_token调用所有通讯录接口，现在通讯录同步的access_token只能获取到成员ID和部门ID了，获取成员详情和部门详情需要使用自建应用access_token
     * 
     * 2、从2022年6月20号20点开始，除通讯录同步以外的基础应用（如客户联系、微信客服、会话存档、日程等），
     * 以及新创建的自建应用与代开发应用，调用该接口时，不再返回以下字段：头像、性别、手机、邮箱、企业邮箱、员工个人二维码、地址，
     * 应用需要通过oauth2手工授权的方式获取管理员与员工本人授权的字段。
     * 简单理解就是从2022年6月20号20点开始，新创建的自建应用与代开发应用，调用该接口时，不再返回这些信息：头像、性别、手机、邮箱、企业邮箱、员工个人二维码、地址。如要获取成员这些信息需要成员登录系统时提示手动授权后才能获取。
     * 
     * 
     * @param [string] $employeeUserid 企业成员userid
     */
    public function getEmployeeList($employeeUserid)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=".$this->_appAccessToken."&userid=".$employeeUserid;

        $resJson = httpRequest($url);
        $res = json_decode($resJson, true);

        // echo "<pre>";
        // var_dump($url);
        // var_dump($res);
        // exit;

        /*array(17) {
            ["errcode"]=>
            int(0)
            ["errmsg"]=>
            string(2) "ok"
            ["userid"]=>
            string(7) "prolove"
            ["name"]=>
            string(6) "罗江"
            ["department"]=>
            array(1) {
              [0]=>
              int(1)
            }
            ["position"]=>
            string(0) ""
            ["status"]=>
            int(1)
            ["isleader"]=>
            int(0)
            ["extattr"]=>
            array(1) {
              ["attrs"]=>
              array(0) {
              }
            }
            ["telephone"]=>
            string(0) ""
            ["enable"]=>
            int(1)
            ["hide_mobile"]=>
            int(0)
            ["order"]=>
            array(1) {
              [0]=>
              int(0)
            }
            ["main_department"]=>
            int(1)
            ["alias"]=>
            string(0) ""
            ["is_leader_in_dept"]=>
            array(1) {
              [0]=>
              int(0)
            }
            ["direct_leader"]=>
            array(0) {
            }
          }*/

        if ($res['errcode'] == 0) {
			return $res;
		} 

    }

    /**
     * 更新成员
     * 
     * 【权限说明】
     * 仅通讯录同步助手或第三方通讯录应用可调用。
     * 
     */
    public function updateEmployee($data)
    {

        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/update?access_token=".$this->_contactAccessToken;

        // {
        //     "userid": "zhangsan",
        //     "name": "李四",
        //     // "department": [1],
        //     "position": "后台工程师",
        //     "alias": "jackzhang"
        // }

        $bodyArray = array(
            'userid' => $data['userid']
        );

        if ($data['name']) {
            $bodyArray['name'] = $data['name'];
        }

        // 部门
        if ($data['department']) {
            $bodyArray['department'] = array((int)$data['department']);
        }

        if ($data['position']) {
            $bodyArray['position'] = $data['position'];
        }

        if ($data['alias']) {
            $bodyArray['alias'] = $data['alias'];
        }

        // echo "<pre>";
        // var_dump($url);
        // var_dump($bodyArray);
        // var_dump(json_encode($bodyArray));
        // exit;

        $body = json_encode($bodyArray);
        $resJson = httpRequest($url, $body, '', 'json');
		$res = json_decode($resJson, true);

        // echo "<pre>";
        // var_dump($url);
        // var_dump($res);
        // exit;

        /*
        array(2) {
            ["errcode"]=>
            int(0)
            ["errmsg"]=>
            string(7) "updated"
        }
        */

        if ($res['errcode'] == 0) {
            return true;
        }

    }

    /**
     * 获取子部门ID列表
     * 
     * 【使用说明】
     * 需要在通讯录同步应用配置企业可信IP
     * 
     * 【权限说明】
     * 普通自建应用：只能拉取token对应的应用的权限范围内的部门列表
     * 通讯录同步助手：可获取企业所有部门id
     * 代开发自建应用：只能拉取token对应的应用的权限范围内的部门列表
     * 
     * @param int $id 部门id。获取指定部门及其下的子部门（以及子部门的子部门等等，递归）。 如果不填，默认获取全量组织架构
     */
    public function getDepartmentId($id='')
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/department/simplelist?access_token=".$this->_contactAccessToken."&id=".$id;

        $resJson = httpRequest($url);
        $res = json_decode($resJson, true);

        // echo "<pre>";
        // var_dump($url);
        // var_dump($res);
        // exit;

        /*
            array(3) {
                ["errcode"]=>
                int(0)
                ["errmsg"]=>
                string(46) "ok. WARNING: field `id` expect type `uint32`. "
                ["department_id"]=>
                array(11) {
                    [0]=>
                    array(3) {
                        ["id"]=>
                        int(1)
                        ["parentid"]=>
                        int(0)
                        ["order"]=>
                        int(100000000)
                    }
                    [1]=>
                    array(3) {
                        ["id"]=>
                        int(2)
                        ["parentid"]=>
                        int(1)
                        ["order"]=>
                        int(100000000)
                    }
                }
           }
        */
         

        if ($res['errcode'] == 0) {
			return $res['department_id'];
		} 
    }

    /**
     * 获取单个部门详情
     * 
     * 【权限说明】
     * 普通自建应用：只能拉取token对应的应用的权限范围内的部门详情
     * 通讯录同步助手：可获取企业所有部门详情。从2022年8月15日10点开始，“企业管理后台 - 管理工具 - 通讯录同步”的新增IP将不能再调用此接口。只能使用自建应用和代开发自建应用的方式调用此接口。
     * 代开发自建应用：只能拉取token对应的应用的权限范围内的部门详情
     * 
     */
    public function getDepartmentDetail($departmentId)
    {
        if ($departmentId) {
            $url = "https://qyapi.weixin.qq.com/cgi-bin/department/get?access_token=".$this->_appAccessToken."&id=".$departmentId;
            
            $resJson = httpRequest($url);
            $res = json_decode($resJson, true);

            // echo "<pre>";
            // var_dump($url);
            // var_dump($res);
            // exit;

            /*
                array(3) {
                    ["errcode"]=>
                    int(0)
                    ["errmsg"]=>
                    string(2) "ok"
                    ["department"]=>
                        array(5) {
                        ["id"]=>
                        int(1)
                        ["name"]=>
                        string(12) "广州乾游"
                        ["parentid"]=>
                        int(0)
                        ["order"]=>
                        int(100000000)
                        ["department_leader"]=>
                        array(1) {
                            [0]=>
                            string(4) "Xing"
                        }
                    }
                }
            */

            if ($res['errcode'] == 0) {
                return $res['department'];
            }

        }
    }

    /**
     * 获取会话内容存档开启成员列表
     * 
     * 【权限说明】
     * 企业需要使用会话内容存档应用secret所获取的accesstoken来调用
     * 
     */
    public function getPermitUserList($type = '')
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/msgaudit/get_permit_user_list?access_token=".$this->_conversationAccessToken;

        $bodyArray = array(
            'type' => $type // 拉取对应版本的开启成员列表。1表示办公版；2表示服务版；3表示企业版。非必填，不填写的时候返回全量成员列表。
        );
        $body = json_encode($bodyArray);

        $resJson = httpRequest($url, $body, '', 'json');
		$res = json_decode($resJson, true);

        echo "<pre>";
        var_dump($res);
        exit;

        /*
        {
            "errcode": 0,
            "errmsg": "ok",
            "ids":[
                 "userid_111",
                 "userid_222",
                 "userid_333",
            ],
         }
        */ 

        if ($res['errcode'] == 0) {
            return $res['ids'];

        } elseif ($res['errcode'] == 301053) {
            // 错误说明：会话存档服务未开启

            return $res['ids'];
        }
         
    }

    /**
     * 获取用户登录身份
     * 
     * 扫码登录成功，根据返回的code值获取用户登录身份
     * 
     * 权限说明：跳转的域名须完全匹配access_token对应应用的可信域名，否则会返回50001错误。
     * 
     * @param [string] $code 通过成员授权获取到的code，最大为512字节。每次成员授权带上的code将不一样，code只能使用一次，5分钟未被使用自动过期。
     * 
     */
    public function getUserInfo($code)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/auth/getuserinfo?access_token=".$this->_appAccessToken."&code=".$code;

        $resJson = httpRequest($url);
		$res = json_decode($resJson, true);

        // echo "<pre>";
        // var_dump($res);
        // exit;

        // array(3) {
        //     ["userid"]=>
        //     string(10) "HeYongZhen"
        //     ["errcode"]=>
        //     int(0)
        //     ["errmsg"]=>
        //     string(2) "ok"
        //   }
        

        /**
         * API错误码：40029
         * 
         * oauth_code参数错误。确认：
         * 1）code只能消费一次，不能重复消费。比如说，是否存在多个服务器同时消费同一code情况。
         * 2）code需要在有效期间消费（5分钟），过期会自动失效。
         * 
         * 备注：https://open.work.weixin.qq.com/devtool/query?e=40029
         * 
         */

        if ($res['errcode'] == 0) {
            return $res['userid'];
        } else {
            // 记录获取用户登录身份信息失败日志
            error_log("\n".date("[Y-m-d H:i:s]").":\n". 'getUserInfo API URL: '. $url. "\n". 'getUserInfo API RES: '. $resJson. "\n\n", 3, C('DEDE_DATA_PATH')."logs/wxWebLogin_error_".date("ymd").".txt");
            
        }

    }

}