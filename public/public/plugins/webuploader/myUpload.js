/**
 * 需引用js
 * <script src="/public/js/jquery.min.js?v=1513845015"></script>
 * <script src="/public/js/md5.js"></script>
 * <script src="/public/js/json.js"></script>
 * <script src="/public/js/plugins/webuploader/webuploader.js"></script>
 *
 * 需引用css
 * <link href="/public/js/plugins/webuploader/webuploader.css" rel="stylesheet">
 *
 * opt:{
 *    inputName:'imgs', //隐藏域的name及id名
 *    fileNumLimit:3,
 *    fileHost:'http://ysfq-test.oss-cn-beijing.aliyuncs.com/', //图片域名
 *    server:'http://127.0.0.1:8804/file/upload.html', //上传服务器路径
 *    imgList:['img1.png'],//图片列表，用于编辑时显示默认图片(json字符串)
 * }
 *
 * 使用方法：
 * html
 * <div id="uploader" class="wu-example">
 * </div>
 *
 * js
 * $('#uploader').MyUpload({
 *           fileNumLimit:3,
 *           fileHost:'http://ysfq-test.oss-cn-beijing.aliyuncs.com/',
 *           server:'http://127.0.0.1:8804/file/upload.html',
 *           inputName:'imgs',
 *           imgList:'[]'
 *      });
 */
;(function ($,window,document,undifined) {
    var MyUpload = function (ele,opt) {
        this.$element = ele;
        this.defaults = {
            pick: {
                id: ".filePicker",
                label: "点击选择图片"
            },
            imgList:'[]',
            inputName:'imgs',
            //dnd: "#uploader .queueList",
            paste: document.body,
            accept: {
                title: "Images",
                extensions: "gif,jpg,jpeg,bmp,png",
                mimeTypes: "image/*"
            },
            swf: '/public/js/plugins/webuploader/Uploader.swf',
            disableGlobalDnd: !0,
            chunked: !0,
            server: "http://127.0.0.1:8804/file/upload.html",
            fileHost:'',
            fileNumLimit: 1,
            fileSizeLimit: 5242880,
            fileSingleSizeLimit: 1048576
        };
        this.staticOptions = {
            m:0,
            h:0,
            g:window.devicePixelRatio || 1,
            v:110 * this.g,
            b:110 * this.g,
            k:"pedding",
            w:{},
            x: function () {
                var e = document.createElement("p").style,
                    a = "transition" in e || "WebkitTransition" in e || "MozTransition" in e || "msTransition" in e || "OTransition" in e;
                return e = null, a
            }()
        };
        this.options = $.extend({},this.defaults,opt,this.staticOptions);
    }
    MyUpload.prototype = {
        init:function () {
            if (!WebUploader.Uploader.support()) throw alert("Web Uploader 不支持您的浏览器！如果你使用的是IE浏览器，请尝试升级 flash 播放器"), new Error("WebUploader does not support the browser you are using.");
            this.render();
            var that = this;
            this.failFileList=new Array();
            this.fileList = new Object();
            this.options.headers={'Authorization':this.getAuthCode()};
            this.n = WebUploader.create(this.options);
            this.n.addButton({
                id: "#filePicker2",
                label: "继续添加"
            });
            this.n.onUploadProgress = function (e, a) {
                var i = $("#" + e.id),
                    t = i.find(".progress span");
                t.css("width", 100 * a + "%"), that.options.w[e.id][1] = a, that.s();
                if(3000 == a.status){
                    that.$element.text(a.msg);
                }
            };
            this.n.onFileQueued = function (a) {
                that.options.m++, that.options.h += a.size, 1 === that.options.m && (that.u.addClass("element-invisible"), that.d.show()), that.e(a), that.t("ready"), that.s();
                that.failFileList=new Array();
                that.c.text('保存并关闭').removeClass("disabled");
                that.c.text('开始上传').on("click", function () {
                    return that.n.upload();
                });
            };
            this.n.onFileDequeued = function (e) {
                that.options.m--, that.options.h -= e.size, that.options.m || that.t("pedding"), that.a(e), that.s()
            };
            this.n.on( 'uploadSuccess', function( file ,data) {
                console.log(data);
                if(2000 == data.status){
                    that.fileList[file.id] = that.options.fileHost + data.result.url;
                    console.log(that.fileList);
                }else{
                    that.$element.text(data.msg);
                }
            });
            this.n.on( 'uploadError', function( file,data ) {
                that.failFileList.push(file);
            });
            this.n.on( 'ready', function() {
                    var picList = $.evalJSON(that.options.imgList);
                    $.each(picList, function(index,item){
                        that.getFileObject(item, function (fileObject) {
                            var wuFile = new WebUploader.Lib.File(WebUploader.guid('rt_'),fileObject);
                            var file = new WebUploader.File(wuFile);
                            that.n.addFiles(file);
                            file.setStatus('complete',WebUploader.File.COMPLETE);
                        })
                    });
                });

            this.n.on( 'fileQueued', function( file ) {
                that.fileList[file.id] = file.name;
            });
            this.n.on( 'uploadComplete', function( file ) {

            });
            this.n.on("all", function (e) {
                    switch (e) {
                        case "uploadFinished":
                            that.t("confirm");
                            var temp_list = Object.keys(that.fileList);
                            var file_nums = temp_list.length;
                            console.log(that.fileList);
                            if(file_nums>0){
                                that.$img_input.val($.toJSON(Object.values(that.fileList)));
                            }
                            break;
                        case "startUpload":
                            that.t("uploading");
                            break;
                        case "stopUpload":
                            that.t("paused")
                    }
                });
            this.n.onError = function (e) {
                if(e == 'Q_EXCEED_NUM_LIMIT'){
                    alert("上传文件数量超过限制！")
                }else if(e == 'F_DUPLICATE'){
                    alert("选择文件重复！")
                }else{
                    alert("Eroor: " + e)
                }
            };
            this.c.on("click", function () {
                return $(this).hasClass("disabled") ? !1 : void("ready" === this.k ? that.n.upload() : "paused" === this.k ? that.n.upload() : "uploading" === that.k && that.n.stop())
            });
            this.p.on("click", ".retry", function (e) {
                this.n.retry()
            });
            this.p.on("click", ".ignore", function () {
                for(var i=0;i<that.failFileList.length;i++){
                    n.removeFile(that.failFileList[i],true);
                }
            });
            this.c.addClass("state-" + this.options.k)
            this.s();
        },
        getAuthCode : function () {
            var date = new Date();
            var seperator1 = "-";
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var strDate = date.getDate();
            if (month >= 1 && month <= 9) {
                month = "0" + month;
            }
            if (strDate >= 0 && strDate <= 9) {
                strDate = "0" + strDate;
            }
            var currentdate = year + seperator1 + month + seperator1 + strDate;
            return hex_md5(currentdate);
        },
        getFileBlob : function (url, cb) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", url);
            xhr.responseType = "blob";
            xhr.addEventListener('load', function() {
                cb(xhr.response);
            });
            xhr.send();
        },
        blobToFile : function (blob, name) {
            blob.lastModifiedDate = new Date();
            blob.name = name;
            return blob;
        },
        getFileObject : function(filePathOrUrl, cb) {
            var that = this
            this.getFileBlob(filePathOrUrl, function (blob) {
                cb(that.blobToFile(blob, filePathOrUrl));
            });
        },
        render:function () {
            var tpl = '<div class="queueList">\n' +
                '                                        <div id="dndArea" class="placeholder">\n' +
                '                                            <div id="filePicker" class="filePicker"></div>\n' +
                '                                        </div>\n' +
                '                                    </div>\n' +
                '                                    <input id="'+this.options.inputName+'" name="'+this.options.inputName+'" type="hidden" class="form-control"\n' +
                '                                           aria-required="true" value="'+this.options.imgList+'">\                 n' +
                '                                    <div class="statusBar" style="display:none;">\n' +
                '                                        <div class="progress">\n' +
                '                                            <span class="text">0%</span> <span class="percentage"></span>\n' +
                '                                        </div>\n' +
                '                                        <div class="info"></div>\n' +
                '                                        <div class="btns">\n' +
                '                                            <div id="filePicker2"></div>\n' +
                '                                            <div class="uploadBtn">开始上传</div>\n' +
                '                                        </div>\n' +
                '                                    </div>'
            this.$element.html(tpl);
            this.$img_input = $('#'+this.options.inputName);
            this.l = $('<ul class="filelist"></ul>').appendTo(this.$element.find(".queueList"));
            this.d = this.$element.find(".statusBar");
            this.p = this.d.find(".info");
            this.c = this.$element.find(".uploadBtn");
            this.u = this.$element.find(".placeholder");
            this.f = this.d.find(".progress").hide();
        },
        a:function(e) {
            var a = $("#" + e.id);
            delete this.options.w[e.id], this.s(), a.off().find(".file-panel").off().end().remove()
        },

        removeByValue:function (arr, val) {
            for(var i=0; i<arr.length; i++) {
                if(arr[i] == val) {
                    arr.splice(i, 1);
                    break;
                }
            }
        },
        s:function () {
            var e, a = 0,
                s = 0,
                t = this.f.children();
            $.each(this.options.w, function (e, i) {
                s += i[0], a += i[0] * i[1]
            }), e = s ? a / s : 0, t.eq(0).text(Math.round(100 * e) + "%"), t.eq(1).css("width", Math.round(100 * e) + "%"), this.i()
        },
        i:function i() {
            var e, a = "";
            "ready" === this.options.k ? a = "选中" + this.options.m + "张图片，共" + WebUploader.formatSize(this.options.h) + "。" : "confirm" === this.options.k ? (e = this.n.getStats(), e.uploadFailNum && (a = "已成功上传" + e.successNum + "张图片，" + e.uploadFailNum + '张图片上传失败，<a class="retry" href="#">重新上传</a>失败图片或<a class="ignore" href="#">忽略失败图片</a>')) : (e = this.n.getStats(), a = "共" + this.options.m + "张（" + WebUploader.formatSize(this.options.h) + "）" , e.uploadFailNum && (a += "，失败" + e.uploadFailNum + "张")), this.p.html(a)
        },
        t:function t(e) {
            var a;
            if (e !== this.options.k) {
                switch (this.c.removeClass("state-" + this.options.k), this.c.addClass("state-" + e), this.options.k = e) {
                    case "pedding":
                        this.u.removeClass("element-invisible"), this.l.parent().removeClass("filled"), this.l.hide(), this.d.addClass("element-invisible"), this.n.refresh();
                        break;
                    case "ready":
                        this.u.addClass("element-invisible"), this.l.parent().addClass("filled"), this.l.show(), this.d.removeClass("element-invisible"), this.n.refresh();
                        break;
                    case "uploading":
                        $("#filePicker2").addClass("element-invisible"), this.f.show(), this.c.text("暂停上传");
                        break;
                    case "paused":
                        this.f.show(), this.c.text("继续上传");
                        break;
                    case "confirm":
                        if (this.f.hide(), this.c.text("开始上传").addClass("disabled"), a = this.n.getStats(), a.successNum && !a.uploadFailNum) return void this.t("finish");
                        break;
                    case "finish":
                        $("#filePicker2").removeClass("element-invisible"),
                            a = this.n.getStats(), a.successNum ? console.log(a) : (k = "done");
                }
                this.i()
            }
        },
        e : function(e) {
            var that = this;
            var a = $('<li id="' + e.id + '"><p class="title">' + e.name + '</p><p class="imgWrap"></p><p class="progress"><span></span></p></li>'),
                s = $('<div class="file-panel"><span class="cancel">删除</span><span class="rotateRight">向右旋转</span><span class="rotateLeft">向左旋转</span></div>').appendTo(a),
                i = a.find("p.progress span"),
                t = a.find("p.imgWrap"),
                r = $('<p class="error"></p>'),
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
            "invalid" === e.getStatus() ? d(e.statusText) : (t.text("预览中"), this.n.makeThumb(e, function (e, a) {
                if (e) return void t.text("不能预览");
                var s = $('<img src="' + a + '">');
                t.empty().append(s)
            }, this.v, this.b), this.options.w[e.id] = [e.size, 0], e.rotation = 0), e.on("statuschange", function (t, n) {
                "progress" === n ? i.hide().width(0) : "queued" === n && (''),
                    "error" === t || "invalid" === t ? (console.log(e.statusText), d(e.statusText), that.options.w[e.id][1] = 1) : "interrupt" === t ? d("interrupt") : "queued" === t ? that.options.w[e.id][1] = 0 : "progress" === t ? (r.remove(), i.css("display", "block")) : "complete" === t && a.append('<span class="success"></span>'), a.removeClass("state-" + n).addClass("state-" + t)
            }), a.on("mouseenter", function () {
                s.stop().animate({
                    height: 30
                })
            }), a.on("mouseleave", function () {
                s.stop().animate({
                    height: 0
                })
            }), s.on("click", "span", function () {
                var a, s = $(this).index();
                switch (s) {
                    case 0:
                        var old_list = $.evalJSON(that.options.imgList);
                        old_list = that.removeByValue(old_list,e.name)
                        that.$img_input.val($.toJSON(old_list));
                        delete that.fileList[e.id];
                        return void that.n.removeFile(e);
                    case 1:
                        e.rotation += 90;
                        break;
                    case 2:
                        e.rotation -= 90
                }
                that.options.x ? (a = "rotate(" + e.rotation + "deg)", t.css({
                    "-webkit-transform": a,
                    "-mos-transform": a,
                    "-o-transform": a,
                    transform: a
                })) : t.css("filter", "progid:DXImageTransform.Microsoft.BasicImage(rotation=" + ~~(e.rotation / 90 % 4 + 4) % 4 + ")")
            }), a.appendTo(this.l)
        }
    }
    $.fn.MyUpload = function (options) {
        var myUpload = new MyUpload(this,options);
        console.log(options);
        return myUpload.init();
    }
})($,window,document);