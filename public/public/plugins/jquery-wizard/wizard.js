var _0xa67e=['back','</a>','<a\x20class=\x22btn\x20btn-success\x20btn-outline\x20pull-right\x22\x20href=\x22#','finish','<a\x20class=\x22btn\x20btn-default\x20btn-outline\x20pull-right\x22\x20href=\x22#','\x22\x20data-wizard=\x22next\x22\x20role=\x22button\x22>','</div>','<i\x20class=\x22icon\x20wb-chevron-right\x22\x20aria-hidden=\x22true\x22></i>','<i\x20class=\x22icon\x20wb-check\x22\x20aria-hidden=\x22true\x22></i>','.panel-actions','#exampleWizardProgressbar','$progressbar','$element','tab','show','removeClass','progress-bar-striped','progress-bar-success','css','find','.sr-only','#exampleWizardTabs','>\x20.nav\x20>\x20li\x20>\x20a','color-error','success','.tab-content','components','#exampleWizardAccordion','.panel-title[data-toggle=\x22collapse\x22]','<div\x20class=\x22panel-footer\x22>','buttons','call','collapse','hide','.panel-collapse','#exampleAccountForm','formValidation','请填写用户名','用户名长度为6-30个字符','用户名只能由字母，数字，小数点和下划线组成','请填写密码','username','密码不能和用户名一致','#exampleBillingForm','请填写CVV码','#partner_authenticationForm,\x20#exampleWizardForm','wizard','.panel-body','get','setValidator','data','validate','isValid','#exampleBilling','#partner_authenticationFormContainer,\x20#exampleWizardFormContainer','请填写信用卡卡号','isValidContainer','#exampleWizardPager','.wizard-pane','options','<a\x20class=\x22btn\x20btn-default\x20btn-outline\x22\x20href=\x22#','\x22\x20data-wizard=\x22back\x22\x20role=\x22button\x22>','buttonLabels'];(function(_0x1969a7,_0x5a133a){var _0x3d665f=function(_0x39c64b){while(--_0x39c64b){_0x1969a7['push'](_0x1969a7['shift']());}};_0x3d665f(++_0x5a133a);}(_0xa67e,0x19d));var _0xea67=function(_0x3b1d71,_0x38ef41){_0x3b1d71=_0x3b1d71-0x0;var _0x73c100=_0xa67e[_0x3b1d71];return _0x73c100;};(function(_0xf4c330,_0x5c4057,_0x5ed7f8){'use strict';(function(){_0x5ed7f8(_0xea67('0x0'))[_0xea67('0x1')](_0x5ed7f8['po']('formValidation',{'fields':{'username':{'validators':{'notEmpty':{'message':_0xea67('0x2')},'stringLength':{'min':0x6,'max':0x1e,'message':_0xea67('0x3')},'regexp':{'regexp':/^[a-zA-Z0-9_\.]+$/,'message':_0xea67('0x4')}}},'password':{'validators':{'notEmpty':{'message':_0xea67('0x5')},'different':{'field':_0xea67('0x6'),'message':_0xea67('0x7')}}}}}));_0x5ed7f8(_0xea67('0x8'))[_0xea67('0x1')](_0x5ed7f8['po'](_0xea67('0x1'),{'fields':{'number':{'validators':{'notEmpty':{'message':'请填写信用卡卡号'}}},'cvv':{'validators':{'notEmpty':{'message':_0xea67('0x9')}}}}}));var _0x11a410=_0x5ed7f8(_0xea67('0xa'))[_0xea67('0xb')](_0x5ed7f8['po'](_0xea67('0xb'),{'buttonsAppendTo':_0xea67('0xc')}))['data'](_0xea67('0xb'));_0x11a410[_0xea67('0xd')]('#exampleAccount')[_0xea67('0xe')](function(){var _0x392858=_0x5ed7f8(_0xea67('0x0'))[_0xea67('0xf')]('formValidation');_0x392858[_0xea67('0x10')]();return _0x392858[_0xea67('0x11')]();});_0x11a410[_0xea67('0xd')](_0xea67('0x12'))[_0xea67('0xe')](function(){var _0x7eb140=_0x5ed7f8(_0xea67('0x8'))[_0xea67('0xf')]('formValidation');_0x7eb140['validate']();return _0x7eb140['isValid']();});}());(function(){_0x5ed7f8(_0xea67('0x13'))[_0xea67('0xb')](_0x5ed7f8['po'](_0xea67('0xb'),{'onInit':function(){_0x5ed7f8('#exampleFormContainer')['formValidation'](_0x5ed7f8['po'](_0xea67('0x1'),{'fields':{'username':{'validators':{'notEmpty':{'message':_0xea67('0x2')}}},'password':{'validators':{'notEmpty':{'message':_0xea67('0x5')}}},'number':{'validators':{'notEmpty':{'message':_0xea67('0x14')}}},'cvv':{'validators':{'notEmpty':{'message':'请填写CVV码'}}}}}));},'validator':function(){var _0x3235ce=_0x5ed7f8('#exampleFormContainer')[_0xea67('0xf')](_0xea67('0x1'));var _0x4d3160=_0x5ed7f8(this);_0x3235ce['validateContainer'](_0x4d3160);var _0x14a568=_0x3235ce[_0xea67('0x15')](_0x4d3160);return!(_0x14a568===![]||_0x14a568===null);},'onFinish':function(){},'buttonsAppendTo':_0xea67('0xc')}));}());(function(){_0x5ed7f8(_0xea67('0x16'))[_0xea67('0xb')](_0x5ed7f8['po']('wizard',{'step':_0xea67('0x17'),'templates':{'buttons':function(){var _0x55e660=this[_0xea67('0x18')];return'<div\x20class=\x22btn-group\x20btn-group-sm\x22>'+_0xea67('0x19')+this['id']+_0xea67('0x1a')+_0x55e660[_0xea67('0x1b')][_0xea67('0x1c')]+_0xea67('0x1d')+_0xea67('0x1e')+this['id']+'\x22\x20data-wizard=\x22finish\x22\x20role=\x22button\x22>'+_0x55e660[_0xea67('0x1b')][_0xea67('0x1f')]+'</a>'+_0xea67('0x20')+this['id']+_0xea67('0x21')+_0x55e660[_0xea67('0x1b')]['next']+_0xea67('0x1d')+_0xea67('0x22');}},'buttonLabels':{'next':_0xea67('0x23'),'back':'<i\x20class=\x22icon\x20wb-chevron-left\x22\x20aria-hidden=\x22true\x22></i>','finish':_0xea67('0x24')},'buttonsAppendTo':_0xea67('0x25')}));}());(function(){_0x5ed7f8(_0xea67('0x26'))['wizard'](_0x5ed7f8['po'](_0xea67('0xb'),{'step':_0xea67('0x17'),'onInit':function(){this[_0xea67('0x27')]=this[_0xea67('0x28')]['find']('.progress-bar')['addClass']('progress-bar-striped');},'onBeforeShow':function(_0xaee4e1){_0xaee4e1[_0xea67('0x28')][_0xea67('0x29')](_0xea67('0x2a'));},'onFinish':function(){this[_0xea67('0x27')][_0xea67('0x2b')](_0xea67('0x2c'))['addClass'](_0xea67('0x2d'));},'onAfterChange':function(_0x15cc1d,_0x556777){var _0x2e8a5c=this['length']();var _0x28633e=_0x556777['index']+0x1;var _0x4674c1=_0x28633e/_0x2e8a5c*0x64;this[_0xea67('0x27')][_0xea67('0x2e')]({'width':_0x4674c1+'%'})[_0xea67('0x2f')](_0xea67('0x30'))['text'](_0x28633e+'/'+_0x2e8a5c);},'buttonsAppendTo':_0xea67('0xc')}));}());(function(){_0x5ed7f8(_0xea67('0x31'))[_0xea67('0xb')](_0x5ed7f8['po'](_0xea67('0xb'),{'step':_0xea67('0x32'),'onBeforeShow':function(_0x2683f4){_0x2683f4[_0xea67('0x28')][_0xea67('0x29')]('show');},'classes':{'step':{'error':_0xea67('0x33')}},'onFinish':function(){toastr[_0xea67('0x34')]('完成');},'buttonsAppendTo':_0xea67('0x35')}));}());(function(){var _0x4c42c7=_0x5ed7f8[_0xea67('0x36')]['getDefaults'](_0xea67('0xb'));_0x5ed7f8(_0xea67('0x37'))[_0xea67('0xb')](_0x5ed7f8['po'](_0xea67('0xb'),{'step':_0xea67('0x38'),'classes':{'step':{'error':_0xea67('0x33')}},'templates':{'buttons':function(){return _0xea67('0x39')+_0x4c42c7['templates'][_0xea67('0x3a')][_0xea67('0x3b')](this)+_0xea67('0x22');}},'onBeforeShow':function(_0x42dabe){_0x42dabe['$pane']['collapse'](_0xea67('0x2a'));},'onBeforeHide':function(_0x52661e){_0x52661e['$pane'][_0xea67('0x3c')](_0xea67('0x3d'));},'onFinish':function(){toastr[_0xea67('0x34')]('完成');},'buttonsAppendTo':_0xea67('0x3e')}));}());}(document,window,jQuery));

//   点击下一步按钮页面回到最顶部
$('.wizard-buttons a[data-wizard=next]').click(function () {
    $('body,html').animate({
        scrollTop: 0
    },0);
})