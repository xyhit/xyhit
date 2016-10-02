<?php
namespace LaneWeChat\Core;
include_once __DIR__.'/../config.php';
defined('NEWS_PICTURE_ABS_PATH') or define('NEWS_PICTURE_ABS_PATH', "news_module/newspicture");
/**
 * 处理请求
 * Created by Lane.
 * User: lane
 * Date: 13-12-19
 * Time: 下午11:04
 * Mail: lixuan868686@163.com
 * Website: http://www.lanecn.com
 */

class WechatRequest{
    /**
     * @descrpition 分发请求
     * @param $request
     * @return array|string
     */
    public static function switchType(&$request){
        $data = array();
        switch ($request['msgtype']) {
            //事件
            case 'event':
                $request['event'] = strtolower($request['event']);
                switch ($request['event']) {
                    //关注
                    case 'subscribe':
                        //二维码关注
                        if(isset($request['eventkey']) && isset($request['ticket'])){
                            $data = self::eventQrsceneSubscribe($request);
                        //普通关注
                        }else{
                            $data = self::eventSubscribe($request);
                        }
                        break;
                    //扫描二维码
                    case 'scan':
                        $data = self::eventScan($request);
                        break;
                    //地理位置
                    case 'location':
                        $data = self::eventLocation($request);
                        break;
                    //自定义菜单 - 点击菜单拉取消息时的事件推送
                    case 'click':
                        $data = self::eventClick($request);
                        break;
                    //自定义菜单 - 点击菜单跳转链接时的事件推送
                    case 'view':
                        $data = self::eventView($request);
                        break;
                    //自定义菜单 - 扫码推事件的事件推送
                    case 'scancode_push':
                        $data = self::eventScancodePush($request);
                        break;
                    //自定义菜单 - 扫码推事件且弹出“消息接收中”提示框的事件推送
                    case 'scancode_waitmsg':
                        $data = self::eventScancodeWaitMsg($request);
                        break;
                    //自定义菜单 - 弹出系统拍照发图的事件推送
                    case 'pic_sysphoto':
                        $data = self::eventPicSysPhoto($request);
                        break;
                    //自定义菜单 - 弹出拍照或者相册发图的事件推送
                    case 'pic_photo_or_album':
                        $data = self::eventPicPhotoOrAlbum($request);
                        break;
                    //自定义菜单 - 弹出微信相册发图器的事件推送
                    case 'pic_weixin':
                        $data = self::eventPicWeixin($request);
                        break;
                    //自定义菜单 - 弹出地理位置选择器的事件推送
                    case 'location_select':
                        $data = self::eventLocationSelect($request);
                        break;
                    //取消关注
                    case 'unsubscribe':
                        $data = self::eventUnsubscribe($request);
                        break;
                    //群发接口完成后推送的结果
                    case 'masssendjobfinish':
                        $data = self::eventMassSendJobFinish($request);
                        break;
                    //模板消息完成后推送的结果
                    case 'templatesendjobfinish':
                        $data = self::eventTemplateSendJobFinish($request);
                        break;
                    default:
                        return Msg::returnErrMsg(MsgConstant::ERROR_UNKNOW_TYPE, '收到了未知类型的消息', $request);
                        break;
                }
                break;
            //文本
            case 'text':
                $data = self::text($request);
                break;
            //图像
            case 'image':
                $data = self::image($request);
                break;
            //语音
            case 'voice':
                $data = self::voice($request);
                break;
            //视频
            case 'video':
                $data = self::video($request);
                break;
            //小视频
            case 'shortvideo':
                $data = self::shortvideo($request);
                break;
            //位置
            case 'location':
                $data = self::location($request);
                break;
            //链接
            case 'link':
                $data = self::link($request);
                break;
            default:
                return ResponsePassive::text($request['fromusername'], $request['tousername'], '收到未知的消息，我不知道怎么处理');
                break;
        }
        return $data;
    }


    /**
     * @descrpition 文本
     * @param $request
     * @return array
     */
    public static function text(&$request){
        $reply = '已收到您的消息，我们将尽快回复。';
		$id = $request['fromusername'];
		$recv_send = "R";
		$content = $request['content'];
		include_once __DIR__.'/../sql/sql.php';
		$time = date("Y-m-d H:i:s", time());
        /*insert('think_formalmsg', array(
            'userid'=$id

        ));*/
		mysql_query("insert into formalmsg(id, recv_send, time, content) values('$id', '$recv_send', '$time', '$content')", $db);
		$sql = mysql_query("select id from latestmsg where id='$id'", $db);
		if ($sql){
			if (mysql_num_rows($sql) > 0){
				mysql_query("update latestmsg set latesttime='$time'", $db);
			}
			else{
				mysql_query("insert into latestmsg(id, latesttime) values('$id', '$time')");
			}
		}
		else{
			$reply = "数据库连接发生错误，请重新发送消息。";
		}
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $reply);
    }

    /**
     * @descrpition 图像
     * @param $request
     * @return array
     */
    public static function image(&$request){
        $content = '收到图片';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 语音
     * @param $request
     * @return array
     */
    public static function voice(&$request){
        if(!isset($request['recognition'])){
            $content = '收到语音';
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }else{
            $content = '收到语音识别消息，语音识别结果为：'.$request['recognition'];
            return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
        }
    }

    /**
     * @descrpition 视频
     * @param $request
     * @return array
     */
    public static function video(&$request){
        $content = '收到视频';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 视频
     * @param $request
     * @return array
     */
    public static function shortvideo(&$request){
        $content = '收到小视频';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 地理
     * @param $request
     * @return array
     */
    public static function location(&$request){
        $content = '收到上报的地理位置';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 链接
     * @param $request
     * @return array
     */
    public static function link(&$request){
        $content = '收到连接';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 关注
     * @param $request
     * @return array
     */
    public static function eventSubscribe(&$request){
        $content = '欢迎您关注我们的微信，将为您竭诚服务.';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 取消关注
     * @param $request
     * @return array
     */
    public static function eventUnsubscribe(&$request){
        $content = '为什么不理我了？';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 扫描二维码关注（未关注时）
     * @param $request
     * @return array
     */
    public static function eventQrsceneSubscribe(&$request){
        /*
        *用户扫描带参数二维码进行自动分组
        *此处添加此代码是大多数需求是在扫描完带参数二维码之后对用户自动分组
        */
		if (strlen($request['eventkey']) > 8)
			$sceneid = substr($request['eventkey'], 8);
		else
			$sceneid = $request['eventkey'];
        //$sceneid = str_replace("qrscene_","",$request['eventkey']);
        //移动用户到相应分组中去,此处的$sceneid依赖于之前创建时带的参数
        if(!empty($sceneid)){
            //UserManage::editUserGroup($request['fromusername'], $sceneid);
            //$result=UserManage::getGroupByOpenId($request['fromusername']);
            //方便开发人员调试时查看参数正确性
            //$content = '欢迎您关注我们的微信，将为您竭诚服务。二维码Id:'.$result['groupid'];
			include_once __DIR__.'/../conn.php';
			if (mysql_num_rows(mysql_query("select id from user where id='".$sceneid."'")) > 0){
				$content = "请点击此链接为好友标记: "."https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$sceneid)."&response_type=code&scope=snsapi_base&state=124#wechat_redirect";
			}
			else{
				$content = "对不起，您要标记的用户不存在！";
			}
        }else{
			$content = '欢迎您关注我们的微信，将为您竭诚服务。';
		}
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 扫描二维码（已关注时）
     * @param $request
     * @return array
     */
    public static function eventScan(&$request){
		$sceneid = $request['eventkey'];
		if(!empty($sceneid)){
			include_once __DIR__.'/../conn.php';
			if (mysql_num_rows(mysql_query("select id from user where id='".$sceneid."'")) > 0){
				$content = "请点击此链接为好友标记: "."https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$sceneid)."&response_type=code&scope=snsapi_base&state=124#wechat_redirect";
				//"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx47b09b3c8dd8305e&redirect_uri=".urlencode("http://1.xyhit.applinzi.com/userinfo.php?id=".$_SESSION['mark_id'])."&response_type=code&scope=snsapi_base&state=124#wechat_redirect"
			}
			else{
				$content = "对不起，您要标记的用户不存在！";
			}
		}else{
			$content = '您已经关注了哦～';
		}
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 上报地理位置
     * @param $request
     * @return array
     */
    public static function eventLocation(&$request){
        $content = '收到上报的地理位置';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 点击菜单拉取消息时的事件推送
     * @param $request
     * @return array
     */
    public static function eventClick(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
		switch ($eventKey){
			case 'lane_wechat_menu_3':   //拉取新闻事件
				$query_array = array(
					"max(time)"
				);
				$condition_array = array();
				$result = SqlQuery::query("news", $query_array, $condition_array);
				if ($result[0] == 0 && count($result[1]) == 0){
					echo "暂无新闻！";
				}
				else if ($result[0] == -1){
					echo "数据库查询出错！";
				}
				
				$query_array = array(
					"id",
					"title",
					"description"
				);
				$condition_array = array(
					"time"=>$result[1][0][0]
				);
				$result = SqlQuery::query("news", $query_array, $condition_array);
				if ($result[0] == 0 && count($result[1]) == 0){
					echo "暂无新闻！";
				}
				else if ($result[0] == -1){
					echo "数据库查询出错！";
				}

				$id = $result[1][0]['id'];
				$title = $result[1][0]['title'];
				$desc = $result[1][0]['description'];
				
				$query_array = array(
					"id"
				);
				$condition_array = array(
					"news_id"=>$result[1][0]['id']
				);
				$picresult = SqlQuery::query("newspicture", $query_array, $condition_array);
				if ($picresult[0] == 0 && count($picresult[1]) == 0){
					if (DEBUG == true)
						echo "暂无新闻！";
					else{
						$picture = "default.png";
					}
				}
				else if ($picresult[0] == -1){
					if (DEBUG == true)
						echo "数据库查询出错！";
					else{
						$picture = "default.png";
					}
				}
				$picture = $picresult[1][0]['id'].".png";
				
				$newArr = array(
							array(
								"title"=>$title,
								"description"=>$desc,
								"picurl"=>WECHAT_URL."/".NEWS_PICTURE_ABS_PATH."/".$picture,
								"url"=>WECHAT_URL."/news_module/news.php?id=".(string)$id
							)
						);
				$itemArray = array();
				foreach ($newArr as $item){
					$itemArray[] = ResponsePassive::newsItem($item["title"], $item["description"], $item["picurl"], $item["url"]);
				}
				return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemArray);
				break;
			case 'lane_wechat_menu_connect':     //联系学院事件
				$content = "联系学院请直接通过公众号发送消息给我们，我们会尽快给您回复。";
				return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
				break;
			default:
				break;
		}
        //$content = '收到点击菜单事件，您设置的key是' . $eventKey;
        //return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 点击菜单跳转链接时的事件推送
     * @param $request
     * @return array
     */
    public static function eventView(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到跳转链接事件，您设置的key是' . $eventKey;
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 扫码推事件的事件推送
     * @param $request
     * @return array
     */
    public static function eventScancodePush(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到扫码推事件的事件，您设置的key是' . $eventKey;
        $content .= '。扫描信息：'.$request['scancodeinfo'];
        $content .= '。扫描类型(一般是qrcode)：'.$request['scantype'];
        $content .= '。扫描结果(二维码对应的字符串信息)：'.$request['scanresult'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 扫码推事件且弹出“消息接收中”提示框的事件推送
     * @param $request
     * @return array
     */
    public static function eventScancodeWaitMsg(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到扫码推事件且弹出“消息接收中”提示框的事件，您设置的key是' . $eventKey;
        $content .= '。扫描信息：'.$request['scancodeinfo'];
        $content .= '。扫描类型(一般是qrcode)：'.$request['scantype'];
        $content .= '。扫描结果(二维码对应的字符串信息)：'.$request['scanresult'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出系统拍照发图的事件推送
     * @param $request
     * @return array
     */
    public static function eventPicSysPhoto(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到弹出系统拍照发图的事件，您设置的key是' . $eventKey;
        $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
        $content .= '。发送的图片数量：'.$request['count'];
        $content .= '。图片列表：'.$request['piclist'];
        $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出拍照或者相册发图的事件推送
     * @param $request
     * @return array
     */
    public static function eventPicPhotoOrAlbum(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到弹出拍照或者相册发图的事件，您设置的key是' . $eventKey;
        $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
        $content .= '。发送的图片数量：'.$request['count'];
        $content .= '。图片列表：'.$request['piclist'];
        $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出微信相册发图器的事件推送
     * @param $request
     * @return array
     */
    public static function eventPicWeixin(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到弹出微信相册发图器的事件，您设置的key是' . $eventKey;
        $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
        $content .= '。发送的图片数量：'.$request['count'];
        $content .= '。图片列表：'.$request['piclist'];
        $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出地理位置选择器的事件推送
     * @param $request
     * @return array
     */
    public static function eventLocationSelect(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到点击跳转事件，您设置的key是' . $eventKey;
        $content .= '。发送的位置信息：'.$request['sendlocationinfo'];
        $content .= '。X坐标信息：'.$request['location_x'];
        $content .= '。Y坐标信息：'.$request['location_y'];
        $content .= '。精度(可理解为精度或者比例尺、越精细的话 scale越高)：'.$request['scale'];
        $content .= '。地理位置的字符串信息：'.$request['label'];
        $content .= '。朋友圈POI的名字，可能为空：'.$request['poiname'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * 群发接口完成后推送的结果
     *
     * 本消息有公众号群发助手的微信号“mphelper”推送的消息
     * @param $request
     */
    public static function eventMassSendJobFinish(&$request){
        //发送状态，为“send success”或“send fail”或“err(num)”。但send success时，也有可能因用户拒收公众号的消息、系统错误等原因造成少量用户接收失败。err(num)是审核失败的具体原因，可能的情况如下：err(10001), //涉嫌广告 err(20001), //涉嫌政治 err(20004), //涉嫌社会 err(20002), //涉嫌色情 err(20006), //涉嫌违法犯罪 err(20008), //涉嫌欺诈 err(20013), //涉嫌版权 err(22000), //涉嫌互推(互相宣传) err(21000), //涉嫌其他
        $status = $request['status'];
        //计划发送的总粉丝数。group_id下粉丝数；或者openid_list中的粉丝数
        $totalCount = $request['totalcount'];
        //过滤（过滤是指特定地区、性别的过滤、用户设置拒收的过滤，用户接收已超4条的过滤）后，准备发送的粉丝数，原则上，FilterCount = SentCount + ErrorCount
        $filterCount = $request['filtercount'];
        //发送成功的粉丝数
        $sentCount = $request['sentcount'];
        //发送失败的粉丝数
        $errorCount = $request['errorcount'];
        $content = '发送完成，状态是'.$status.'。计划发送总粉丝数为'.$totalCount.'。发送成功'.$sentCount.'人，发送失败'.$errorCount.'人。';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * 群发接口完成后推送的结果
     *
     * 本消息有公众号群发助手的微信号“mphelper”推送的消息
     * @param $request
     */
    public static function eventTemplateSendJobFinish(&$request){
        //发送状态，成功success，用户拒收failed:user block，其他原因发送失败failed: system failed
        $status = $request['status'];
        if($status == 'success'){
            //发送成功
        }else if($status == 'failed:user block'){
            //因为用户拒收而发送失败
        }else if($status == 'failed: system failed'){
            //其他原因发送失败
        }
        return true;
    }


    public static function test(){
        // 第三方发送消息给公众平台
        $encodingAesKey = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG";
        $token = "pamtest";
        $timeStamp = "1409304348";
        $nonce = "xxxxxx";
        $appId = "wxb11529c136998cb6";
        $text = "<xml><ToUserName><![CDATA[oia2Tj我是中文jewbmiOUlr6X-1crbLOvLw]]></ToUserName><FromUserName><![CDATA[gh_7f083739789a]]></FromUserName><CreateTime>1407743423</CreateTime><MsgType><![CDATA[video]]></MsgType><Video><MediaId><![CDATA[eYJ1MbwPRJtOvIEabaxHs7TX2D-HV71s79GUxqdUkjm6Gs2Ed1KF3ulAOA9H1xG0]]></MediaId><Title><![CDATA[testCallBackReplyVideo]]></Title><Description><![CDATA[testCallBackReplyVideo]]></Description></Video></xml>";


        $pc = new Aes\WXBizMsgCrypt($token, $encodingAesKey, $appId);
        $encryptMsg = '';
        $errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
        if ($errCode == 0) {
            print("加密后: " . $encryptMsg . "\n");
        } else {
            print($errCode . "\n");
        }

        $xml_tree = new \DOMDocument();
        $xml_tree->loadXML($encryptMsg);
        $array_e = $xml_tree->getElementsByTagName('Encrypt');
        $array_s = $xml_tree->getElementsByTagName('MsgSignature');
        $encrypt = $array_e->item(0)->nodeValue;
        $msg_sign = $array_s->item(0)->nodeValue;

        $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
        $from_xml = sprintf($format, $encrypt);

// 第三方收到公众号平台发送的消息
        $msg = '';
        $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
        if ($errCode == 0) {
            print("解密后: " . $msg . "\n");
        } else {
            print($errCode . "\n");
        }
    }

}
