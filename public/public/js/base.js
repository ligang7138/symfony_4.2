//自定义ajax
function fajax(url,datas,method,dataType,backFun){
    try{
        $.ajax({
           dataType: dataType,
           url: url,
           data: datas,
           type:method,
		   timeout:120000,
           beforeSend:function (XMLHttpRequest) {
               XMLHttpRequest.setRequestHeader('Authorization', hex_md5(data_str));
               XMLHttpRequest.setRequestHeader('X-PJAX',1);
               backOff(true);
           },
            error: function(XmlHttpRequest, textStatus, errorThrown){
                var msg = errorThrown.message;
                var data = XmlHttpRequest.responseText;
                alert('<span style="color:red;"><b>错误信息：</b>'+msg+'</span><br> <b>响应数据：</b>'+data);
                backOff(false);
            },
		   complete:function (XMLHttpRequest, textStatus) {
                if(null!=XMLHttpRequest){
                  var msg = '';
                  if(2==XMLHttpRequest.status){
                      msg = '文件不存在!';
                  }else if(3==XMLHttpRequest.status){
                      msg = '服务器无响应!';
                  }else if(200==XMLHttpRequest.status){
                      //成功
                  }else if(400<=XMLHttpRequest.status && 404>XMLHttpRequest.status){
                      msg = '错误请求!';
                  }else if(404==XMLHttpRequest.status){
                      msg = '文件不存在!';
                  }else if(500==XMLHttpRequest.status){
                      msg = '服务器产生内部错误!';
                  }else if(500<XMLHttpRequest.status){
                      msg = '服务器出现错误!';
                  }else{
                      alert('初始化失败!');
                      //window.location='/';
                  }
                    if(''!=msg){
                        alert(msg);
                    }
                    backOff(false);
                    return false;
                }
			},
		   success: backFun
		});
	}catch (e){
        alert(e.message);
        backOff(false);
		return false;
	}
}

//遮罩层
function backOff(on) {
    if(true == on){
        layer.load(2, {
            shade: [0.2,'#000']
        });
    }else{
        layer.closeAll('loading');
    }
}

//自定义提交
function fsubmit(formId,bacnFun){
    var but_name = $(formId).find('button[type="submit"]').eq(1).html();
    $(formId).ajaxSubmit({
        type:"post",
        dataType:"json",
        url:$(formId).attr('action'),
        beforeSend:function (XMLHttpRequest) {
            if(!$(formId).valid()){
                //alert('<span style="color:red;">必填信息有误！ </span>');
                return false;
            }
            backOff(true);
        },
        success:function(data) {
            try{
                if(301==data.code){
                    window.location='/login';
                }
                if(undefined == bacnFun || null == bacnFun){
                    if(data.closeCurrTab == true){
                        msg(data.msg);
                        close_tab();
                    }else{
                        icon = (300==data.code) ? 8 : (200==data.code ? 1 : 2);
                        msg(data.msg,null,icon);
                    }
                }else{
                    bacnFun(data);
                }
            }catch (e){
                alert(e.message);
            }
            backOff(false);
        },
        error: function(XmlHttpRequest, textStatus, errorThrown){
            backOff(false);
            var msg = errorThrown.message;
            var data = XmlHttpRequest.responseText;
            alert('<span style="color:red;"><b>错误信息：</b>'+msg+'</span><br> <b>响应数据：</b>'+data);
        }
    });
    
    $(formId).find('button[type="submit"]').html(but_name);
    return false;
}

function alert(message){
    layer.alert(message, {skin: 'layui-layer-molv',closeBtn: 0});
}

//确认框
function confirm(msg,callBack,title,colse){
    if (undefined == title || $.trim(title).length == 0){
        title = '信息';
    }
    layer.confirm(msg , {
        title:title,
        btn: ['确认','取消']
    },  function(){
        callBack();
        if (undefined == colse || $.trim(colse).length == 0 || true == colse){
            layer.closeAll('dialog');
        }
    });
}

/**
 * 弹出框
 * @param {type} url 路由地址
 * @param {type} title 标题
 * @param {type} content 内容
 * @param {type} area 弹出层宽高,默认null，  默认值['800px', '500px']
 * @param {type} ok 点击确认时函触发该函数,默认null
 * @param {type} success 弹出层后自动触发该函数,默认null
 * @param {type} cancel 点击取消时触发该函数,默认null
 * @param {type} close 点击右上角触发该函数,默认null
 * @returns {undefined}
 */
function mopen(url,title,content,area,ok,success,cancel,close){
    if(undefined != url && null != url){
        fajax(url,null,'get','html',function(d){
            ysopen(title,d,area,ok,success,cancel,close);
            if(undefined != area && null != area && typeof(area) != "string" && area.length>1){
                open_wh_auto(area);
            }
        });
    }else{
        ysopen(title,content,area,ok,success,cancel,close);
    }
}
function ysopen(title,content,area,ok,success,cancel,close){
    if(undefined == area || null == area){
        area = ['800px', '500px'];
    }
    layer.open({
        type:1,
        anim:0,//动画效果0~6
        title:title,
        content: content,
        area: area,
        shade : 0.3,
        //offset: '100px',
        btn: ['确定', '取消'],
        yes: function(index, layero){
            if(undefined != ok && null != ok){
                var ret = ok(index, layero);
                if(false==ret){
                    return false;
                }
            }else{
                layer.closeAll();
            }
        },
        btn2: function(index, layero){
            if(undefined != cancel && null != cancel){
                cancel(index, layero);
                layer.closeAll();
            }
        },
        success: function(layero, index){
            if(undefined != success && null != success){
                success(layero, index);
                layer.closeAll();
            }
        },
        cancel: function(index, layero){
            if(undefined != close && null != close){
                close(index, layero);
                layer.closeAll();
            }
        }
    });
}
function open_wh_auto(area){
    if(area[1]=="auto"||area=="auto"){
        $(".layui-layer-content").height("auto");
        $('.layui-layer-page').height("auto");
        $(".layui-layer-content").css("overflow","visible");
    }
}


//模态弹出框
function modalWindown(title,content,success,fail){
    $.confirm({
        title: title,
        content: content,
        autoClose: false,
        buttons: {
            formSubmit: {
                text: '确定',
                btnClass: 'btn-blue',
                action: function () {
                    if (undefined != success && success != null){
                        return success();
                    }
                }
            },
            cancel: {
                text: '取消',
                action: function(){
                    if (undefined != fail && fail != null){
                        return fail();
                    }
                }
            }
        }
    });
}

function sopen(url,title,area) {
    if(undefined == area || null == area){
        area = ['800px', '500px'];
    }
    layer.open({
        type: 2,
        area: area,
        fixed: false, //不固定
        maxmin: true,
        title: title,
        content: url
    });
}

//icon类型1代表成功，2代表失败，8代表警告；
function msg(msg,backFun,icon){
    if (undefined == icon || null==icon){
        icon = 1;
    }
    layer.msg(msg, {
        icon: icon,
        time: 1200
    }, function(){
        if(undefined != backFun && null != backFun){
            backFun();
        }
    });
}

//关闭当前标签
function close_tab() {
    $.site.contentTabs.closeTab();
}

//打开新标签
function open_tab(name,url) {
    $.site.contentTabs.buildTab({name: name, url: url});
}

/**
 * 自定义分页
 * @param page_nums
 * @param url
 * @param datas
 * @param string tabid 在指定的ID标签中插入分页数据, 默认为空时刷拳整个页面
 */
function onPage(page_nums,url,datas){
    if(null==page_nums || ''==page_nums || 1>page_nums){page_nums = 1;}
    var jdata=$.toJSON(datas);
    fajax(url,'p='+page_nums+'&jdata='+jdata,'post','html',function(content){
        $('#admui-pageContent').html(content);
    });

}

//  图片资料点击放大查看
function openPhotoWin(obj){
    layer.photos({
        photos:'.imgWrap'
    })
}

//读消息
function readMessage(msg_id) {
    fajax('/admin/read_message/'+msg_id+'.html',null,'POST','JSON',function (d) {
        if(301==d.code){
            window.location='/login';
        }else{
            if(200!=d.code){
                msg(d.msg,null,2);
            }
        }
        layer.closeAll();
    });
}

// 获取当前时间
function nowTime() {
    function p(s) {return s < 10 ? '0' + s: s;}
    var myDate = new Date();
    var year=myDate.getFullYear(); //获取当前年
    var month=myDate.getMonth()+1; //获取当前月
    var date=myDate.getDate();     //获取当前日
    var h=myDate.getHours();       //获取当前小时数(0-23)
    var m=myDate.getMinutes();     //获取当前分钟数(0-59)
    var s=myDate.getSeconds();
    now_date=year+'-'+p(month)+"-"+p(date);   //当前年月日
    now_time=year+'-'+p(month)+"-"+p(date)+" "+p(h)+':'+p(m)+":"+p(s);  // 当前年月日时分秒
}

// 身份证校验
function checkCard(v,sex,a) {
    var reg = /(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/;
    //获取年龄
    var myDate = new Date();
    var month = myDate.getMonth() + 1;
    var day = myDate.getDate();
    if($.trim(v.val())!=""){
        if(reg.test($.trim(v.val()))===false){
            alert("身份证输入不合法");
            v.parents('tr').find(sex).removeAttr('readonly');
            v.parents('tr').find(a).removeAttr('readonly');
            v.css({'border':'1px solid #ff000059'});
            v.parent().find('#uei_operate_time-error').remove();
            v.parent().append('<span id="uei_operate_time-error" class="help-block m-b-none" style="color: red;font-size: 12px;"><i class="fa fa-times-circle"></i>身份证格式错误！</span>');
            return false;
        }else {
            if($.trim(v.val()).length == 18){
                var age = myDate.getFullYear()- $.trim(v.val()).substring(6, 10) - 1;
                if ($.trim(v.val()).substring(10,12)<month||$.trim(v.val()).substring(10,12)==month&&$.trim(v.val()).substring(12,14)<=day){age++;}
                v.parents('tr').find(a).val(age);
                if(parseInt($.trim(v.val()).substr(16,1))%2==1){
                    v.parents('tr').find(sex).val('男');
                }else {
                    v.parents('tr').find(sex).val('女');
                }
            }else if($.trim(v.val()).length == 15){
                var age2 = myDate.getFullYear()- Number('19'+ $.trim(v.val()).substring(6, 8)) - 1;
                if ($.trim(v.val()).substring(8,10)<month||$.trim(v.val()).substring(8,10)==month&&$.trim(v.val()).substring(10,12)<=day){age2++;}
                v.parents('tr').find(a).val(age2);
                if(parseInt($.trim(v.val()).substr(14,1))%2==1){
                    v.parents('tr').find(sex).val('男');
                }else {
                    v.parents('tr').find(sex).val('女');
                }
            }
            v.parents('tr').find(sex).attr('readonly',true);
            v.parents('tr').find(a).attr('readonly',true);
            v.css({'border':'none'});
            v.parent().find('#uei_operate_time-error').remove();
        }
    }else {
        v.parents('tr').find(sex).removeAttr('readonly');
        v.parents('tr').find(a).removeAttr('readonly');
        v.css({'border':'none'});
        v.parent().find('#uei_operate_time-error').remove();
    }
}

//防暴力刷新
var clicktag = 0;
function stopFresh(){
    if (clicktag == 0) {
        clicktag = 1;
        setTimeout(function () { clicktag = 0 }, 3500);
        return true;
    }else{
        alert("刷新太频繁了！~~");
        return false;
    }
}

