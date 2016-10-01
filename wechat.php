<?php
namespace LaneWeChat;

use LaneWeChat\Core\Wechat;
use LaneWeChat\Core\Menu;
/**
 * 微信插件唯一入口文件.
 * @Created by Lane.
 * @Author: lane
 * @Mail lixuan868686@163.com
 * @Date: 14-1-10
 * @Time: 下午4:00
 * @Blog: Http://www.lanecn.com
 */
//引入配置文件
include_once __DIR__.'/config.php';
//引入自动载入函数
include_once __DIR__.'/autoloader.php';
//调用自动载入函数
AutoLoader::register();
//初始化微信类
$wechat = new WeChat(WECHAT_TOKEN, TRUE);

/*创建公众号底部菜单*/
$menuList = array(
				array('id'=>'1', 'pid'=>'0', 'name'=>'用户', 'type'=>'', 'code'=>''),
				array('id'=>'11', 'pid'=>'1', 'name'=>'注册', 'type'=>'view', 'code'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WECHAT_APPID.'e&redirect_uri='.urlencode(WECHAT_URL."/oauth2.php").'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect'),
				array('id'=>'12', 'pid'=>'1', 'name'=>'个人信息', 'type'=>'view', 'code'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WECHAT_APPID.'&redirect_uri='.urlencode(WECHAT_URL."/edituserinfo.php").'&response_type=code&scope=snsapi_base&state=122#wechat_redirect'),
				array('id'=>'13', 'pid'=>'1', 'name'=>'搜索用户', 'type'=>'view', 'code'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WECHAT_APPID.'&redirect_uri='.urlencode(WECHAT_URL."/search.php").'&response_type=code&scope=snsapi_base&state=122#wechat_redirect'),
				
				array('id'=>'2', 'pid'=>'0', 'name'=>'功能', 'type'=>'', 'code'=>''),
				array('id'=>'21', 'pid'=>'2', 'name'=>'捐款排行', 'type'=>'view', 'code'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WECHAT_APPID.'e&redirect_uri='.urlencode(WECHAT_URL."/fund.php").'&response_type=code&scope=snsapi_base&state=126#wechat_redirect'),
				array('id'=>'22', 'pid'=>'2', 'name'=>'联系学院', 'type'=>'click', 'code'=>'lane_wechat_menu_connect'),
				array('id'=>'23', 'pid'=>'2', 'name'=>'发布动态', 'type'=>'view', 'code'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WECHAT_APPID.'&redirect_uri='.urlencode(WECHAT_URL."/editBlog.php").'&response_type=code&scope=snsapi_base&state=127#wechat_redirect'),
				array('id'=>'24', 'pid'=>'2', 'name'=>'浏览动态', 'type'=>'view', 'code'=>'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WECHAT_APPID.'&redirect_uri='.urlencode(WECHAT_URL."/allBlog.php").'&response_type=code&scope=snsapi_base&state=128#wechat_redirect'),
				
				array('id'=>'3', 'pid'=>'0', 'name'=>'新闻', 'type'=>'', 'code'=>''),
				array('id'=>'31', 'pid'=>'3', 'name'=>'最新', 'type'=>'click', 'code'=>'lane_wechat_menu_3'),
				array('id'=>'32', 'pid'=>'3', 'name'=>'往期', 'type'=>'view', 'code'=>WECHAT_URL."news_module/previousnews.php"),
            );
$menu = new Menu();
echo $menu->setMenu($menuList);

//首次使用需要注视掉下面这1行（26行），并打开最后一行（29行）
//echo $wechat->run();
//首次使用需要打开下面这一行（29行），并且注释掉上面1行（26行）。本行用来验证URL
//$wechat->checkSignature();