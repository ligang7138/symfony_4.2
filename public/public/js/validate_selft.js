//添加表单验证
$.validator.setDefaults({
    highlight: function (element) {
        $(element).closest('.form-bolck').removeClass('has-success').addClass('has-error');
    },
    success: function (element) {
        $(element).closest('.form-bolck').removeClass('has-error');
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        if (element.is(":radio") || element.is(":checkbox")) {
            error.appendTo(element.parent().parent().parent());
        } else {
            error.appendTo(element.parent());
        }
    },
    errorClass: "help-block m-b-none",
    validClass: "help-block m-b-none",
});

//兼容chose select控件
$.validator.setDefaults({ ignore: ":hidden:not(select)" });

//添加手机号验证
$.validator.addMethod("isMobile", function(value, element) {
    var length = value.length;
    var mobile = /^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/;
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "<i class='fa fa-times-circle'></i>  请正确填写您的手机号码");