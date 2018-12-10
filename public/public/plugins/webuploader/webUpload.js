jQuery(function () {
    function e(e) {
        var a = o('<li id="' + e.id + '"><p class="title">' + e.name + '</p><p class="imgWrap"></p><p class="progress"><span></span></p></li>'),
            s = o('<div class="file-panel"><span class="cancel">删除</span><span class="rotateRight">向右旋转</span><span class="rotateLeft">向左旋转</span></div>').appendTo(a),
            i = a.find("p.progress span"),
            t = a.find("p.imgWrap"),
            r = o('<p class="error"></p>'),
            d = function (e) {
                switch (e) {
                    case "exceed_size":
                        text = "文件太大了";
                        break;
                    case "interrupt":
                        text = "上传暂停";
                        break;
                    default:
                        text = "上传失败，请重试";
                }
                r.text(text).appendTo(a)
            };
        "invalid" === e.getStatus() ? d(e.statusText) : (t.text("预览中"), n.makeThumb(e, function (e, a) {
            if (e) return void t.text("不能预览");
            var s = o('<img src="' + a + '">');
            t.empty().append(s)
        }, v, b), w[e.id] = [e.size, 0], e.rotation = 0), e.on("statuschange", function (t, n) {
            "progress" === n ? i.hide().width(0) : "queued" === n && (a.off("mouseenter mouseleave"), s.remove()), "error" === t || "invalid" === t ? (console.log(e.statusText), d(e.statusText), w[e.id][1] = 1) : "interrupt" === t ? d("interrupt") : "queued" === t ? w[e.id][1] = 0 : "progress" === t ? (r.remove(), i.css("display", "block")) : "complete" === t && a.append('<span class="success"></span>'), a.removeClass("state-" + n).addClass("state-" + t)
        }), a.on("mouseenter", function () {
            s.stop().animate({
                height: 30
            })
        }), a.on("mouseleave", function () {
            s.stop().animate({
                height: 0
            })
        }), s.on("click", "span", function () {
            var a, s = o(this).index();
            switch (s) {
                case 0:
                    return void n.removeFile(e);
                case 1:
                    e.rotation += 90;
                    break;
                case 2:
                    e.rotation -= 90
            }
            x ? (a = "rotate(" + e.rotation + "deg)", t.css({
                "-webkit-transform": a,
                "-mos-transform": a,
                "-o-transform": a,
                transform: a
            })) : t.css("filter", "progid:DXImageTransform.Microsoft.BasicImage(rotation=" + ~~(e.rotation / 90 % 4 + 4) % 4 + ")")
        }), a.appendTo(l)
    }

    function a(e) {
        var a = o("#" + e.id);
        delete w[e.id], s(), a.off().find(".file-panel").off().end().remove()
    }

    function s() {
        var e, a = 0,
            s = 0,
            t = f.children();
        o.each(w, function (e, i) {
            s += i[0], a += i[0] * i[1]
        }), e = s ? a / s : 0, t.eq(0).text(Math.round(100 * e) + "%"), t.eq(1).css("width", Math.round(100 * e) + "%"), i()
    }

    function i() {
        var e, a = "";
        "ready" === k ? a = "选中" + m + "张图片，共" + WebUploader.formatSize(h) + "。" : "confirm" === k ? (e = n.getStats(), e.uploadFailNum && (a = "已成功上传" + e.successNum + "张图片，" + e.uploadFailNum + '张图片上传失败，<a class="retry" href="#">重新上传</a>失败图片或<a class="ignore" href="#">忽略失败图片</a>')) : (e = n.getStats(), a = "共" + m + "张（" + WebUploader.formatSize(h) + "），已上传" + e.successNum + "张", e.uploadFailNum && (a += "，失败" + e.uploadFailNum + "张")), p.html(a)
    }

    function t(e) {
        var a;
        if (e !== k) {
            switch (c.removeClass("state-" + k), c.addClass("state-" + e), k = e) {
                case "pedding":
                    u.removeClass("element-invisible"), l.parent().removeClass("filled"), l.hide(), d.addClass("element-invisible"), n.refresh();
                    break;
                case "ready":
                    u.addClass("element-invisible"), o("#filePicker2").removeClass("element-invisible"), l.parent().addClass("filled"), l.show(), d.removeClass("element-invisible"), n.refresh();
                    break;
                case "uploading":
                    o("#filePicker2").addClass("element-invisible"), f.show(), c.text("暂停上传");
                    break;
                case "paused":
                    f.show(), c.text("继续上传");
                    break;
                case "confirm":
                    if (f.hide(), c.text("开始上传").addClass("disabled"), a = n.getStats(), a.successNum && !a.uploadFailNum) return void t("finish");
                    break;
                case "finish":
                    a = n.getStats(), a.successNum ? console.log(a) : (k = "done", location.reload());
            }
            i()
        }
    }
    var n,failFileList=new Array(), o = jQuery,obj,fileList = new Array(),
        r = o("#uploader"),
        l = o('<ul class="filelist"></ul>').appendTo(r.find(".queueList")),
        d = r.find(".statusBar"),
        p = d.find(".info"),
        c = r.find(".uploadBtn"),
        u = r.find(".placeholder"),
        f = d.find(".progress").hide(),
        m = 0,
        h = 0,
        g = window.devicePixelRatio || 1,
        v = 110 * g,
        b = 110 * g,
        k = "pedding",
        w = {},
        x = function () {
            var e = document.createElement("p").style,
                a = "transition" in e || "WebkitTransition" in e || "MozTransition" in e || "msTransition" in e || "OTransition" in e;
            return e = null, a
        }();
    if (!WebUploader.Uploader.support()) throw alert("Web Uploader 不支持您的浏览器！如果你使用的是IE浏览器，请尝试升级 flash 播放器"), new Error("WebUploader does not support the browser you are using.");
    n = WebUploader.create({
        pick: {
            id: "#filePicker",
            label: "点击选择图片"
        },
        dnd: "#uploader .queueList",
        paste: document.body,
        accept: {
            title: "Images",
            extensions: "gif,jpg,jpeg,bmp,png",
            mimeTypes: "image/*"
        },
        swf: "/public/plugins/webuploader/Uploader.swf",
        disableGlobalDnd: !0,
        chunked: !0,
        server: upload_host,
        headers:{'Authorization':hex_md5(data_str)},
        fileNumLimit: 10,
        fileSizeLimit: 125829120,
        fileSingleSizeLimit: 10485760
    }), n.addButton({
        id: "#filePicker2",
        label: "继续添加"
    }), n.onUploadProgress = function (e, a) {
        var i = o("#" + e.id),
            t = i.find(".progress span");
        t.css("width", 100 * a + "%"), w[e.id][1] = a, s();
        if(3000 == a.status){
            r.text(a.msg);
        }
    }, n.onFileQueued = function (a) {
        m++, h += a.size, 1 === m && (u.addClass("element-invisible"), d.show()), e(a), t("ready"), s();
        failFileList=new Array();
        c.text('保存并关闭').removeClass("disabled");
        c.text('开始上传').on("click", function () {
            return n.upload();
        });
    }, n.onFileDequeued = function (e) {
        m--, h -= e.size, m || t("pedding"), a(e), s()
    },n.on( 'uploadSuccess', function( file ,data) {
        if(2000 == data.status){
            fileList.push(data.result.url);
        }else{
            r.text(data.msg);
        }
    }),
    n.on( 'uploadError', function( file,data ) {
        failFileList.push(file);
    }),
    n.on( 'uploadComplete', function( file ) {

    }), n.on("all", function (e) {
        switch (e) {
            case "uploadFinished":
                t("confirm");
                setTimeout(function () {
                    var file_nums = fileList.length;
                    if(file_nums>0){
                        c.text('关闭').removeClass("disabled").unbind().click(function(){
                            obj = new Object();
                            obj.p_id = p_id;
                            obj.f_type = f_type;
                            obj.fileList = fileList;

                            switch (f_type){
                                case 'a':
                                    var name = 'idUpload[]'
                                    break;
                                case 'b':
                                    var name = 'headImg'
                                    break;
                                case 'c':
                                    var name = 'partnerImg[]'
                                    break;
                                case 'd':
                                    var name = 'goodsImg[]'
                                    break;
                                case 'e':
                                    var name = 'gcateImg[]'
                                    break;
                                case 'g':
                                    var name = 'otherUpload[]'
                                    break;
                                default:
                            }
                            var add_btn = $('.filelist_'+p_id+'_'+f_type).find('.file-box:last');

                            if(p_id > 0){
                                fajax('/upload/getDaturmInfo.html',obj,'post','json',function(d){
                                if(200 == d.code){
                                    var len = d.daturm_list.length;
                                    var firstEle = $('.filelist_'+p_id+'_'+f_type).filter(":visible").find('.file-box:last');
                                    // add_btn.siblings().remove();
                                    for(index in fileList) {
                                        item = fileList[index];
                                        var newEle = firstEle.clone();
                                        newEle.attr('style','');
                                        newEle.find('div.file>a').after(newEle.find('div.file>a').html()).remove();
                                        newEle.find('input[name="'+name+'"]').remove();
                                        var newInput = $("<input name='"+name+"' type='hidden' value='"+item+"'>");
                                        // newEle.find('input[name="' + name + '"]').val(item);
                                        var img = newEle.find('.image').find('img');
                                        var file = newEle.find('.file');
                                        var newcancle = $('<span class="cancel delGcate" >删除</span>');
                                        var imgBig = newEle.find('.image');
                                        imgBig.addClass('imgWrap');
                                        file.before(newcancle);
                                        img.after(newInput);
                                        img.attr('src', ONLINE_URL + item);
                                        img.attr('onclick',"openPhotoWin(this)");
                                        var n = newEle.find('.file-name').html()
                                        var arr = n.split('-');
                                        newEle.find('.file-name').html(arr[0]+'-'+(parseInt(len+1)))
                                        add_btn.before(newEle);
                                    }
                                    layer.closeAll();
                                }else if(500 == d.code){

                                }
                            });
                            }else{
                                var firstEle = $('.filelist_'+p_id+'_'+f_type).filter(":visible").find('.file-box:last');
                                var total = $('.filelist_'+p_id+'_'+f_type).filter(":visible").find('.file-box');
                                if(total.length > 1){

                                    var len = total.length-1;

                                }else{
                                    var len = 0;
                                }
                                // add_btn.siblings().remove();
                                for(index in fileList) {
                                    item = fileList[index];
                                    var newEle = firstEle.clone();
                                    newEle.attr('style','');
                                    newEle.find('div.file>a').after(newEle.find('div.file>a').html()).remove();
                                    newEle.find('input[name="' + name + '"]').remove();
                                    var newInput = $("<input name='"+name+"' type='hidden' value='"+item+"'>");
                                    var img = newEle.find('.image').find('img');
                                    var file = newEle.find('.file');
                                    var newcancle = $('<span class="cancel delGcate" >删除</span>');
                                    var imgBig = newEle.find('.image');
                                    imgBig.addClass('imgWrap');
                                    file.before(newcancle);
                                    img.after(newInput);
                                    img.attr('src', ONLINE_URL + item);
                                    img.attr('onclick',"openPhotoWin(this)");
                                    var n = newEle.find('.file-name').html()
                                    var arr = n.split('-');
                                    newEle.find('.file-name').html(arr[0]+'-'+(parseInt(len+1)))
                                    add_btn.before(newEle);
                                    len++;
                                }
                                layer.closeAll();
                            }
                        });
                        return false;
                    }
                },500);
                break;
            case "startUpload":
                t("uploading");
                break;
            case "stopUpload":
                t("paused")
        }
    }), n.onError = function (e) {
        if('Q_EXCEED_NUM_LIMIT'==e){
            alert("一次性最大支持上传10张图片");
        }else if('F_DUPLICATE'==e){
            alert("图片已存在，请重新选择");
        }else if('F_EXCEED_SIZE'==e){
            alert("上传的图片太大，请重新选择！");
        }else{
            alert("Eroor: " + e);
        }
    }, c.on("click", function () {
        return o(this).hasClass("disabled") ? !1 : void("ready" === k ? n.upload() : "paused" === k ? n.upload() : "uploading" === k && n.stop())
    }), p.on("click", ".retry", function (e) {
        n.retry()
    }), p.on("click", ".ignore", function () {
        console.log(failFileList);
        for(var i=0;i<failFileList.length;i++){
            n.removeFile(failFileList[i],true);
        }
    }), c.addClass("state-" + k), s()
});