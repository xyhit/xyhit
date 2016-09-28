<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
	<title>动态浏览</title>
	<meta http-equiv="Access-Control-Allow-Origin" content="*">
	<!-- 强制让文档的宽度与设备的宽度保持1:1，并且文档最大的宽度比例是1.0，且不允许用户点击屏幕放大浏览 -->
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width, minimal-ui">
	<!-- iphone设备中的safari私有meta标签，它表示：允许全屏模式浏览 -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="stylesheet" href="./weui/dist/style/weui.css"/>
</head>
<body>

<div class="container">
    <div class="weui_tab">
        <div class="weui_navbar">
            <div class="weui_navbar_item">
                按钮
            </div>
            <div class="weui_navbar_item">
                表单
            </div> 
        </div>
        <div class="weui_tab_bd">
            <div class="weui_tab_bd_item" style="display: none;">
                <div class="padding">
                    <a href="javascript:;" id="btnAlert" class="weui_btn weui_btn_warn">Alert</a>
                    <a href="javascript:;" id="btnConfirm" class="weui_btn weui_btn_primary">Confirm</a>
                    <a href="javascript:;" id="btnDialog" class="weui_btn weui_btn_default">Dialog</a>
                    <a href="javascript:;" id="btnToast" class="weui_btn weui_btn_primary">Toast</a>
                    <a href="javascript:;" id="btnLoading" class="weui_btn weui_btn_default">Loading</a>
                    <a href="javascript:;" id="btnTopTips" class="weui_btn weui_btn_default">TopTips</a>
                    <a href="javascript:;" id="btnActionSheet" class="weui_btn weui_btn_default">ActionSheet</a>
                </div>
            </div>
            <div class="weui_tab_bd_item" style="display: none;">
                <div class="weui_cells weui_cells_form">
                    <div class="weui_cell">
                        <div class="weui_cell_bd weui_cell_primary">
                            <div id="uploader"></div>
                        </div>
                    </div>
                </div>

                <div class="weui_cells_title">表单</div>
                <form id="form">
                    <div class="weui_cells weui_cells_form">
                        <div class="weui_cell">
                            <div class="weui_cell_hd"><label class="weui_label">手机号</label></div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <input class="weui_input" type="tel" required pattern="[0-9]{11}" maxlength="11" placeholder="输入你现在的手机号" emptyTips="请输入手机号" notMatchTips="请输入正确的手机号">
                            </div>
                            <div class="weui_cell_ft">
                                <i class="weui_icon_warn"></i>
                            </div>
                        </div>
                        <div class="weui_cell weui_vcode">
                            <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
                            <div class="weui_cell_bd weui_cell_primary">
                                <input class="weui_input" type="number" required placeholder="点击验证码更换" tips="请输入验证码">
                            </div>
                            <div class="weui_cell_ft">
                                <i class="weui_icon_warn"></i>
                                <img src="http://weui.github.io/weui/images/vcode.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="weui_btn_area">
                        <a id="formSubmitBtn" href="javascript:" class="weui_btn weui_btn_primary">提交</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script src="./static/jquery.min.js"></script>
<script src="./static/js/weui.js"></script>
<script src="./static/js/uploader.js"></script>
<script type="text/javascript">
// codepen 没办法直接在 body 标签加属性，所以用这里用 js 给 body 添加 ontouchstart 属性来触发 :active
document.body.setAttribute('ontouchstart', '');

$(function () {
    $('.container').on('click', '#btnAlert', function (e) {
        $.weui.alert('警告你', function () {
            console.log('知道了...');
        });
    }).on('click', '#btnConfirm', function (e) {
        $.weui.confirm('确认删除吗？', function () {
            console.log('删除了...');
        }, function () {
            console.log('不删除...');
        });
    }).on('click', '#btnDialog', function (e) {
        $.weui.dialog({
            title: '自定义标题',
            content: '自定义内容',
            buttons: [{
                label: '知道了',
                type: 'default',
                onClick: function () {
                    console.log('知道了......');
                }
            }, {
                label: '好的',
                type: 'primary',
                onClick: function () {
                    console.log('好的......');
                }
            }]
        });
    }).on('click', '#btnToast', function (e) {
        $.weui.toast('已完成');
    }).on('click', '#btnLoading', function (e) {
        $.weui.loading('数据加载中...');
        setTimeout($.weui.hideLoading, 3000);
    }).on('click', '#btnTopTips', function (e) {
        $.weui.topTips('格式不对');
    }).on('click', '#btnActionSheet', function (e) {
        $.weui.actionSheet([{
            label: '示例菜单',
            onClick: function () {
                console.log('click1');
            }
        }, {
            label: '示例菜单',
            onClick: function () {
                console.log('click2');
            }
        }, {
            label: '示例菜单',
            onClick: function () {
                console.log('click3');
            }
        }]);
    });

    $('#uploader').uploader({
        maxCount: 4,
        onChange: function (file) {
            var update = this.update;
            var success = this.success;
            var error = this.error;
            $.ajax({
                type: 'POST',
                url: '/api/v1/upload?format=base64',
                data: {
                    data: file.data
                },
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.addEventListener('progress', function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            update(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(res){
                    success();
                },
                error: function (err){
                    error();
                }
            });
        }
    });

    // 为表单加入检测功能：当required的元素blur时校验，并弹出错误提示
    var $form = $("#form");
    $form.form();

    // 表单校验
    $("#formSubmitBtn").on("click", function(){
        $form.validate();
        // $form.validate(function(error){ console.log(error);}); // error: {$dom:[$Object], msg:[String]}
    });

    // tab
    $('.weui_tab').tab();
});


</script>
</html>