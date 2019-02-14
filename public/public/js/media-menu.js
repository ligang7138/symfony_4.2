var _0x8ecc = ['.site-menu', '.site-menu-sub', 'site', 'menubar', '$body', 'body', '$html', 'html', '#admui-navTabs', 'css-menubar', 'addClass', 'js-menubar', 'tabId', '#admui-navbar', 'find', 'li.active\x20>\x20a', 'attr', 'href', '.nav-tabs\x20li.active', 'ul>li.active>a', 'length', '.site-menubar-fold-alt', 'foldAlt', '.site-menubar-keep', 'hasClass', 'site-menubar-fold', 'fold', 'site-menubar-unfold', 'unfold', 'changed.site.menubar', 'update', 'shown.bs.tab', 'hoverscroll', 'enable', 'slimScroll', 'change', 'current', 'auto', 'reset', 'name', 'hide', 'leave', ':visible', 'is-load', 'site-menubar-changing', 'call', '$instance', 'trigger', 'changing.site.menubar', 'removeClass', 'folded', 'site-menubar-hide\x20site-menubar-open\x20site-menubar-fold\x20site-menubar-unfold', 'disable-scrolling', 'animate', 'site-menubar-open\x20site-menubar-unfold', 'opened', 'disable', 'site-menubar-open', 'site-menubar-hide\x20site-menubar-unfold', 'destroy', 'responsiveHorizontalTabs', 'open', 'native', 'removeAttr', 'style', 'children', 'div', 'api', 'asHoverScroll', 'vertical'];
(function(_0x37d3e6, _0x5a6a9a) {
    var _0x92a399 = function(_0x1aa795) {
        while (--_0x1aa795) {
            _0x37d3e6['push'](_0x37d3e6['shift']());
        }
    };
    _0x92a399(++_0x5a6a9a);
}(_0x8ecc, 0x11a));
var _0xc8ec = function(_0x14add3, _0x13ce8d) {
    _0x14add3 = _0x14add3 - 0x0;
    var _0x4a174f = _0x8ecc[_0x14add3];
    return _0x4a174f;
};
(function(_0x222acc, _0x2d4949, _0x1a52d5) {
    'use strict';
    _0x1a52d5[_0xc8ec('0x0')][_0xc8ec('0x1')] = {
        'opened': null,
        'folded': null,
        'top': ![],
        'foldAlt': ![],
        'auto': !![],
        'init': function() {
            var _0x50a763 = this,
                _0x17eeb0 = this[_0xc8ec('0x2')] = _0x1a52d5(_0xc8ec('0x3')),
                _0x20d827 = this[_0xc8ec('0x4')] = _0x1a52d5(_0xc8ec('0x5')),
                _0x90354b = this['$instance'] = _0x1a52d5(_0xc8ec('0x6'));
            _0x20d827['removeClass'](_0xc8ec('0x7'))[_0xc8ec('0x8')](_0xc8ec('0x9'));
            this[_0xc8ec('0xa')] = _0x1a52d5(_0xc8ec('0xb'))[_0xc8ec('0xc')](_0xc8ec('0xd'))[_0xc8ec('0xe')](_0xc8ec('0xf'));
            if (this[_0xc8ec('0xa')] === '#') {
                this[_0xc8ec('0xa')] = _0x1a52d5(_0xc8ec('0x10'))[_0xc8ec('0xc')](_0xc8ec('0x11'))[_0xc8ec('0xe')](_0xc8ec('0xf'));
            }
            if (!_0x90354b[_0xc8ec('0x12')]) {
                return;
            }
            if (_0x17eeb0['is'](_0xc8ec('0x13'))) {
                this[_0xc8ec('0x14')] = !![];
            }
            if (_0x17eeb0['is'](_0xc8ec('0x15'))) {
                if (_0x17eeb0[_0xc8ec('0x16')](_0xc8ec('0x17'))) {
                    this['auto'] = _0xc8ec('0x18');
                } else if (_0x17eeb0['hasClass'](_0xc8ec('0x19'))) {
                    this['auto'] = _0xc8ec('0x1a');
                }
            }
            _0x90354b['on'](_0xc8ec('0x1b'), function() {
                _0x50a763[_0xc8ec('0x1c')]();
            });
            _0x1a52d5('.nav-tabs\x20li:not(.no-menu)')['on'](_0xc8ec('0x1d'), function(_0x4ff13c) {
                var _0x3428a8 = _0x50a763['tabId'] = _0x1a52d5(_0x4ff13c['target'])[_0xc8ec('0xe')](_0xc8ec('0xf'));
                if (_0x17eeb0[_0xc8ec('0x16')](_0xc8ec('0x17'))) {
                    _0x50a763[_0xc8ec('0x1e')][_0xc8ec('0x1f')](_0x3428a8);
                } else if (_0x17eeb0['hasClass'](_0xc8ec('0x19'))) {
                    _0x50a763[_0xc8ec('0x20')][_0xc8ec('0x1f')]();
                }
            });
            this[_0xc8ec('0x21')]();
        },
        'change': function() {
            var _0x3c14cb = Breakpoints[_0xc8ec('0x22')]();
            if (this[_0xc8ec('0x23')] !== !![]) {
                switch (this[_0xc8ec('0x23')]) {
                    case _0xc8ec('0x18'):
                        this[_0xc8ec('0x24')]();
                        if (_0x3c14cb[_0xc8ec('0x25')] === 'xs') {
                            this['hide']();
                        } else {
                            this[_0xc8ec('0x18')]();
                        }
                        return;
                    case _0xc8ec('0x1a'):
                        this[_0xc8ec('0x24')]();
                        if (_0x3c14cb[_0xc8ec('0x25')] === 'xs') {
                            this[_0xc8ec('0x26')]();
                        } else {
                            this[_0xc8ec('0x1a')]();
                        }
                        return;
                }
            }
            this['reset']();
            if (_0x3c14cb) {
                switch (_0x3c14cb[_0xc8ec('0x25')]) {
                    case 'lg':
                        this[_0xc8ec('0x1a')]();
                        break;
                    case 'md':
                    case 'sm':
                        this[_0xc8ec('0x18')]();
                        break;
                    case 'xs':
                        this['hide']();
                        break;
                }
            }
            Breakpoints['on']('xs', _0xc8ec('0x27'), function() {
                _0x1a52d5(_0xc8ec('0xb'))['responsiveHorizontalTabs']({
                    'tabParentSelector': _0xc8ec('0x6'),
                    'fnCallback': function(_0x31a02d) {
                        if (_0x1a52d5(_0xc8ec('0xb'))['is'](_0xc8ec('0x28'))) {
                            _0x31a02d['removeClass'](_0xc8ec('0x29'));
                        }
                    }
                });
            });
        },
        'animate': function(_0x10ecce, _0x828b3) {
            var _0x19c5c6 = this,
                _0x1b7f66 = _0x19c5c6[_0xc8ec('0x2')];
            _0x1b7f66[_0xc8ec('0x8')](_0xc8ec('0x2a'));
            _0x10ecce[_0xc8ec('0x2b')](_0x19c5c6);
            this[_0xc8ec('0x2c')][_0xc8ec('0x2d')](_0xc8ec('0x2e'));
            _0x828b3['call'](_0x19c5c6);
            _0x1b7f66[_0xc8ec('0x2f')](_0xc8ec('0x2a'));
            _0x19c5c6['$instance']['trigger'](_0xc8ec('0x1b'));
        },
        'reset': function() {
            this['opened'] = null;
            this[_0xc8ec('0x30')] = null;
            this['$body'][_0xc8ec('0x2f')](_0xc8ec('0x31'));
            this['$html'][_0xc8ec('0x2f')](_0xc8ec('0x32'));
        },
        'open': function() {
            if (this['opened'] !== !![]) {
                this[_0xc8ec('0x33')](function() {
                    this['$body']['removeClass']('site-menubar-hide')[_0xc8ec('0x8')](_0xc8ec('0x34'));
                    this[_0xc8ec('0x35')] = !![];
                    this[_0xc8ec('0x4')]['addClass']('disable-scrolling');
                }, function() {
                    this[_0xc8ec('0x20')][_0xc8ec('0x1f')]();
                });
            }
        },
        'hide': function() {
            this[_0xc8ec('0x1e')][_0xc8ec('0x36')]();
            if (this[_0xc8ec('0x35')] !== ![]) {
                this[_0xc8ec('0x33')](function() {
                    this[_0xc8ec('0x4')][_0xc8ec('0x2f')](_0xc8ec('0x32'));
                    this[_0xc8ec('0x2')][_0xc8ec('0x2f')](_0xc8ec('0x37'))[_0xc8ec('0x8')](_0xc8ec('0x38'));
                    this['opened'] = ![];
                }, function() {
                    this[_0xc8ec('0x20')][_0xc8ec('0x1f')]();
                });
            }
        },
        'unfold': function() {
            this['hoverscroll'][_0xc8ec('0x36')]();
            if (this[_0xc8ec('0x30')] !== ![]) {
                this[_0xc8ec('0x33')](function() {
                    this['$body'][_0xc8ec('0x2f')](_0xc8ec('0x17'))[_0xc8ec('0x8')]('site-menubar-unfold');
                    this[_0xc8ec('0x30')] = ![];
                }, function() {
                    this['slimScroll']['enable']();
                });
            }
        },
        'fold': function() {
            this[_0xc8ec('0x20')][_0xc8ec('0x39')]();
            if (this[_0xc8ec('0x30')] !== !![]) {
                this[_0xc8ec('0x33')](function() {
                    this[_0xc8ec('0x2')][_0xc8ec('0x2f')](_0xc8ec('0x19'))['addClass'](_0xc8ec('0x17'));
                    this[_0xc8ec('0x30')] = !![];
                }, function() {
                    this[_0xc8ec('0x1e')]['enable'](this[_0xc8ec('0xa')]);
                });
            }
        },
        'toggle': function() {
            var _0x2a8abc = Breakpoints[_0xc8ec('0x22')](),
                _0x2ad91c = this[_0xc8ec('0x30')],
                _0x2a2756 = this[_0xc8ec('0x35')];
            switch (_0x2a8abc['name']) {
                case 'lg':
                    if (_0x2ad91c === null || _0x2ad91c === ![]) {
                        this['fold']();
                    } else {
                        this[_0xc8ec('0x1a')]();
                    }
                    break;
                case 'md':
                case 'sm':
                    if (_0x2ad91c === null || _0x2ad91c === !![]) {
                        this[_0xc8ec('0x1a')]();
                    } else {
                        this[_0xc8ec('0x18')]();
                    }
                    _0x1a52d5(_0xc8ec('0xb'))[_0xc8ec('0x3a')]({
                        'tabParentSelector': '#admui-navTabs'
                    });
                    break;
                case 'xs':
                    if (_0x2a2756 === null || _0x2a2756 === ![]) {
                        this[_0xc8ec('0x3b')]();
                    } else {
                        this[_0xc8ec('0x26')]();
                    }
                    break;
            }
        },
        'update': function() {
            this[_0xc8ec('0x1e')]['update']();
        },
        'slimScroll': {
            'api': null,
            'native': ![],
            'init': function() {
                if (_0x1a52d5(_0xc8ec('0x3'))['is']('.site-menubar-native')) {
                    this[_0xc8ec('0x3c')] = !![];
                    return;
                }
                _0x1a52d5[_0xc8ec('0x0')]['menubar'][_0xc8ec('0x2c')][_0xc8ec('0x20')](_0x1a52d5['po']('slimScroll'));
            },
            'enable': function() {
                if (this[_0xc8ec('0x3c')]) {
                    return;
                }
                this['init']();
            },
            'destroy': function() {
                _0x1a52d5['site'][_0xc8ec('0x1')][_0xc8ec('0x2c')][_0xc8ec('0x20')]({
                    'destroy': !![]
                });
                _0x1a52d5['site'][_0xc8ec('0x1')][_0xc8ec('0x2c')][_0xc8ec('0x3d')](_0xc8ec('0x3e'));
            }
        },
        'hoverscroll': {
            'api': null,
            'init': function(_0x2d6ed9) {
                _0x1a52d5[_0xc8ec('0x0')][_0xc8ec('0x1')][_0xc8ec('0x2c')]['find'](_0x2d6ed9)[_0xc8ec('0x3f')](_0xc8ec('0x40'))[_0xc8ec('0xe')](_0xc8ec('0x3e'), '');
                this[_0xc8ec('0x41')] = _0x1a52d5[_0xc8ec('0x0')][_0xc8ec('0x1')]['$instance']['find'](_0x2d6ed9)[_0xc8ec('0x42')]({
                    'namespace': 'hoverscorll',
                    'direction': _0xc8ec('0x43'),
                    'list': _0xc8ec('0x44'),
                    'item': '>\x20li',
                    'exception': _0xc8ec('0x45'),
                    'fixed': ![],
                    'boundary': 0x64,
                    'onEnter': function() {},
                    'onLeave': function() {}
                })['data'](_0xc8ec('0x42'));
            },
            'update': function() {
                if (this['api']) {
                    this['api'][_0xc8ec('0x1c')]();
                }
            },
            'enable': function(_0x30bcc3) {
                if (_0x30bcc3 !== this[_0xc8ec('0xa')]) {
                    this[_0xc8ec('0xa')] = _0x30bcc3;
                    this['init'](_0x30bcc3);
                } else {
                    this[_0xc8ec('0x41')][_0xc8ec('0x1f')]();
                }
            },
            'disable': function() {
                if (this['api']) {
                    this['api'][_0xc8ec('0x36')]();
                }
            }
        }
    };
}(window, document, jQuery));