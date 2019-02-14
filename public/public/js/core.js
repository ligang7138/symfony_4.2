var _0x6d38 = ['_queue', 'each', 'unshift', '.app', 'components', '_components', 'init', 'mode', 'default', '_initDefault', '_initComponent', 'api', '_initApi', 'defaults', 'component:', '\x20脚本文件没有注册任何信息！', 'site', 'notifyFn', 'helper', 'iconType', 'SYSTEM', 'TASK', 'fa-tasks\x20task', 'fa-cog\x20setting', 'EVENT', 'fa-calendar\x20event', 'fa-comment-o\x20other', 'split', 'timeDistance', 'getTime', 'toFixed', '分钟前', '小时前', 'leavePage', 'configs', 'length', 'get', 'colors', 'undefined', 'getDefaults', 'extend', 'storage', 'error', '该浏览器不支持localStorage对象', 'object', 'stringify', 'setItem', 'parse', 'exp', 'remove', 'val', 'removeItem', 'sessionStorage', 'string', 'objExtend', 'dequeue', 'prepare', 'trigger', 'before.run', 'run', 'complete', 'after.run', 'getQueue', 'call', 'isFunction', 'isArray'];
(function(_0x4489cd, _0x83d7ee) {
    var _0x3d202c = function(_0x134751) {
        while (--_0x134751) {
            _0x4489cd['push'](_0x4489cd['shift']());
        }
    };
    _0x3d202c(++_0x83d7ee);
}(_0x6d38, 0x1ef));
var _0x86d3 = function(_0x122096, _0x60c295) {
    _0x122096 = _0x122096 - 0x0;
    var _0x3d1966 = _0x6d38[_0x122096];
    return _0x3d1966;
};
(function(_0x26b0d9, _0x2423a8, _0x4d96c2) {
    'use strict';
    _0x4d96c2[_0x86d3('0x0')] = null;
    _0x4d96c2['ctx'] = '';
    _0x4d96c2['configs'] = _0x4d96c2[_0x86d3('0x1')] || {};
    _0x4d96c2['extend'](_0x4d96c2[_0x86d3('0x1')], {
        '_data': {},
        'get': function(_0x455b9d) {
            var _0x2501dd = this['_data'];
            for (var _0x2d8272 = 0x0; _0x2d8272 < arguments[_0x86d3('0x2')]; _0x2d8272++) {
                _0x455b9d = arguments[_0x2d8272];
                _0x2501dd = _0x2501dd[_0x455b9d];
            }
            return _0x2501dd;
        },
        'set': function(_0x33d4aa, _0x2eaa15) {
            this['_data'][_0x33d4aa] = _0x2eaa15;
        },
        'extend': function(_0x552b07, _0x2aea31) {
            var _0x3ab01e = this[_0x86d3('0x3')](_0x552b07);
            return _0x4d96c2['extend'](!![], _0x3ab01e, _0x2aea31);
        }
    });
    _0x4d96c2['colors'] = function(_0x3ed317, _0x555e6d) {
        if (!_0x4d96c2['configs']['colors'] && typeof _0x4d96c2[_0x86d3('0x1')][_0x86d3('0x4')][_0x3ed317] === 'undefined') {
            return null;
        }
        if (_0x555e6d && typeof _0x4d96c2[_0x86d3('0x1')]['colors'][_0x3ed317][_0x555e6d] !== _0x86d3('0x5')) {
            return _0x4d96c2[_0x86d3('0x1')][_0x86d3('0x4')][_0x3ed317][_0x555e6d];
        } else {
            return _0x4d96c2[_0x86d3('0x1')][_0x86d3('0x4')][_0x3ed317];
        }
    };
    _0x4d96c2['po'] = function(_0x1b8d0a, _0xbbca86) {
        var _0x12ef4a = _0x4d96c2['components'][_0x86d3('0x6')](_0x1b8d0a);
        return _0x4d96c2[_0x86d3('0x7')](!![], {}, _0x12ef4a, _0xbbca86);
    };
    _0x4d96c2[_0x86d3('0x8')] = _0x4d96c2[_0x86d3('0x8')] || {};
    _0x4d96c2[_0x86d3('0x7')](_0x4d96c2[_0x86d3('0x8')], {
        'set': function(_0x1ff008, _0x44436d, _0x53b437) {
            var _0x2d5ba9, _0x281da0;
            if (!localStorage) {
                console[_0x86d3('0x9')](_0x86d3('0xa'));
            }
            if (!_0x1ff008 || !_0x44436d) {
                return null;
            }
            if (!_0x53b437 || isNaN(_0x53b437)) {
                _0x2d5ba9 = null;
            } else {
                _0x2d5ba9 = new Date() - 0x1 + _0x53b437 * 0x3e8 * 0x3c;
            }
            _0x281da0 = {
                'val': _0x44436d,
                'exp': _0x2d5ba9
            };
            if (typeof _0x44436d === _0x86d3('0xb')) {
                _0x281da0 = JSON[_0x86d3('0xc')](_0x281da0);
            }
            localStorage[_0x86d3('0xd')](_0x1ff008, _0x281da0);
        },
        'get': function(_0x32dee7) {
            var _0x9b76f2, _0x5e9ecc, _0x191ba5;
            if (!localStorage) {
                console['error'](_0x86d3('0xa'));
            }
            _0x9b76f2 = localStorage['getItem'](_0x32dee7);
            if (!_0x9b76f2) {
                return null;
            }
            if (typeof _0x9b76f2 === 'string') {
                _0x9b76f2 = JSON[_0x86d3('0xe')](_0x9b76f2);
            }
            _0x5e9ecc = new Date() - 0x1;
            _0x191ba5 = _0x9b76f2[_0x86d3('0xf')];
            if (_0x191ba5 && _0x5e9ecc > _0x191ba5) {
                this[_0x86d3('0x10')](_0x32dee7);
                return null;
            }
            return _0x9b76f2[_0x86d3('0x11')];
        },
        'remove': function(_0x5654ab) {
            if (!localStorage) {
                console[_0x86d3('0x9')]('该浏览器不支持localStorage对象');
            }
            localStorage[_0x86d3('0x12')](_0x5654ab);
        }
    });
    _0x4d96c2[_0x86d3('0x13')] = _0x4d96c2[_0x86d3('0x13')] || {};
    _0x4d96c2['extend'](_0x4d96c2[_0x86d3('0x13')], {
        'set': function(_0x72b3d4, _0x1fa4ac) {
            if (!sessionStorage) {
                console['error']('该浏览器不支持sessionStorage对象');
            }
            if (!_0x72b3d4 || !_0x1fa4ac) {
                return null;
            }
            if (typeof _0x1fa4ac === _0x86d3('0xb')) {
                _0x1fa4ac = JSON['stringify'](_0x1fa4ac);
            }
            sessionStorage[_0x86d3('0xd')](_0x72b3d4, _0x1fa4ac);
        },
        'get': function(_0x1bea5e) {
            var _0x3dfecd;
            if (!sessionStorage) {
                console[_0x86d3('0x9')]('该浏览器不支持sessionStorage对象');
            }
            _0x3dfecd = sessionStorage['getItem'](_0x1bea5e);
            if (!_0x3dfecd) {
                return null;
            }
            if (typeof _0x3dfecd === _0x86d3('0x14')) {
                _0x3dfecd = JSON[_0x86d3('0xe')](_0x3dfecd);
            }
            return _0x3dfecd;
        },
        'remove': function(_0x50291c) {
            if (!sessionStorage) {
                console[_0x86d3('0x9')]('该浏览器不支持sessionStorage对象');
            }
            sessionStorage[_0x86d3('0x12')](_0x50291c);
        }
    });
    _0x4d96c2[_0x86d3('0x15')] = _0x4d96c2[_0x86d3('0x15')] || {};
    _0x4d96c2[_0x86d3('0x7')](_0x4d96c2[_0x86d3('0x15')], {
        '_queue': {
            'prepare': [],
            'run': [],
            'complete': []
        },
        'run': function() {
            var _0x14c9ff = this;
            this[_0x86d3('0x16')](_0x86d3('0x17'), function() {
                _0x14c9ff[_0x86d3('0x18')](_0x86d3('0x19'), _0x14c9ff);
            });
            this[_0x86d3('0x16')](_0x86d3('0x1a'), function() {
                _0x14c9ff[_0x86d3('0x16')](_0x86d3('0x1b'), function() {
                    _0x14c9ff['trigger'](_0x86d3('0x1c'), _0x14c9ff);
                });
            });
        },
        'dequeue': function(_0x403be3, _0x2bda35) {
            var _0x31cefe = this,
                _0x7709f0 = this[_0x86d3('0x1d')](_0x403be3),
                _0x245e39 = _0x7709f0['shift'](),
                _0x4a5ff0 = function() {
                    _0x31cefe['dequeue'](_0x403be3, _0x2bda35);
                };
            if (_0x245e39) {
                _0x245e39[_0x86d3('0x1e')](this, _0x4a5ff0);
            } else if (_0x4d96c2[_0x86d3('0x1f')](_0x2bda35)) {
                _0x2bda35[_0x86d3('0x1e')](this);
            }
        },
        'getQueue': function(_0x4ca4fc) {
            if (!_0x4d96c2[_0x86d3('0x20')](this[_0x86d3('0x21')][_0x4ca4fc])) {
                this['_queue'][_0x4ca4fc] = [];
            }
            return this[_0x86d3('0x21')][_0x4ca4fc];
        },
        'extend': function(_0x375264) {
            _0x4d96c2[_0x86d3('0x22')](this[_0x86d3('0x21')], function(_0x1196b6, _0x2fdad0) {
                if (_0x4d96c2[_0x86d3('0x1f')](_0x375264[_0x1196b6])) {
                    _0x2fdad0[_0x86d3('0x23')](_0x375264[_0x1196b6]);
                    delete _0x375264[_0x1196b6];
                }
            });
            _0x4d96c2['extend'](this, _0x375264);
            return this;
        },
        'trigger': function(_0x4cdf45, _0x536b98, _0x19cabb) {
            if (typeof _0x4cdf45 === 'undefined') {
                return;
            }
            if (typeof _0x19cabb === _0x86d3('0x5')) {
                _0x19cabb = _0x4d96c2('#admui-pageContent');
            }
            _0x19cabb[_0x86d3('0x18')](_0x4cdf45 + _0x86d3('0x24'), _0x536b98);
        }
    });
    _0x4d96c2[_0x86d3('0x25')] = _0x4d96c2[_0x86d3('0x25')] || {};
    _0x4d96c2[_0x86d3('0x7')](_0x4d96c2[_0x86d3('0x25')], {
        '_components': {},
        'register': function(_0x2d15b9, _0x4f5875) {
            this[_0x86d3('0x26')][_0x2d15b9] = _0x4f5875;
        },
        'init': function(_0x47cb00, _0x5e2519, _0x178fc6) {
            var _0x3e115d = this,
                _0x3ff0fd;
            _0x47cb00 = _0x47cb00 || !![];
            if (typeof _0x5e2519 === _0x86d3('0x5')) {
                _0x4d96c2['each'](this[_0x86d3('0x26')], function(_0x42ac0d) {
                    _0x3e115d[_0x86d3('0x27')](_0x47cb00, _0x42ac0d);
                });
            } else {
                _0x178fc6 = _0x178fc6 || _0x2423a8;
                _0x3ff0fd = this['get'](_0x5e2519);
                if (!_0x3ff0fd) {
                    return;
                }
                switch (_0x3ff0fd[_0x86d3('0x28')]) {
                    case _0x86d3('0x29'):
                        return this[_0x86d3('0x2a')](_0x5e2519, _0x178fc6);
                    case _0x86d3('0x27'):
                        return this[_0x86d3('0x2b')](_0x3ff0fd, _0x178fc6);
                    case _0x86d3('0x2c'):
                        return this[_0x86d3('0x2d')](_0x3ff0fd, _0x178fc6, _0x47cb00);
                    default:
                        this[_0x86d3('0x2d')](_0x3ff0fd, _0x178fc6, _0x47cb00);
                        this['_initComponent'](_0x3ff0fd, _0x178fc6);
                        return;
                }
            }
        },
        '_initDefault': function(_0x22bc98, _0x470978) {
            if (!_0x4d96c2['fn'][_0x22bc98]) {
                return;
            }
            var _0x2bdc67 = this[_0x86d3('0x6')](_0x22bc98);
            _0x4d96c2('[data-plugin=' + _0x22bc98 + ']', _0x470978)[_0x86d3('0x22')](function() {
                var _0x8ff605 = _0x4d96c2(this),
                    _0x6f72be = _0x4d96c2[_0x86d3('0x7')](!![], {}, _0x2bdc67, _0x8ff605['data']());
                _0x8ff605[_0x22bc98](_0x6f72be);
            });
        },
        '_initComponent': function(_0x587747, _0x10978b) {
            if (_0x4d96c2['isFunction'](_0x587747['init'])) {
                _0x587747[_0x86d3('0x27')][_0x86d3('0x1e')](_0x587747, _0x10978b);
            }
        },
        '_initApi': function(_0x43490a, _0x35d6fe, _0x23ef72) {
            if (_0x23ef72 && _0x4d96c2['isFunction'](_0x43490a[_0x86d3('0x2c')])) {
                _0x43490a[_0x86d3('0x2c')][_0x86d3('0x1e')](_0x43490a, _0x35d6fe);
            }
        },
        'getDefaults': function(_0x584dc8) {
            var _0x39d6cd = this[_0x86d3('0x3')](_0x584dc8);
            return _0x39d6cd && typeof _0x39d6cd['defaults'] !== _0x86d3('0x5') ? _0x39d6cd[_0x86d3('0x2e')] : {};
        },
        'get': function(_0x5d9c61) {
            if (typeof this['_components'][_0x5d9c61] !== 'undefined') {
                return this[_0x86d3('0x26')][_0x5d9c61];
            } else {
                console[_0x86d3('0x9')](_0x86d3('0x2f') + _0x5d9c61 + _0x86d3('0x30'));
                return undefined;
            }
        }
    });
    _0x4d96c2[_0x86d3('0x31')] = _0x4d96c2[_0x86d3('0x31')] || {};
    _0x26b0d9['Content'] = _0x4d96c2[_0x86d3('0x7')]({}, _0x4d96c2[_0x86d3('0x15')]);
    _0x26b0d9['notifyFn'] = _0x26b0d9[_0x86d3('0x32')] || {
        'render': function() {
            var _0x432133 = this;
            template[_0x86d3('0x33')](_0x86d3('0x34'), function(_0x45db98) {
                switch (_0x45db98) {
                    case _0x86d3('0x35'):
                        return 'fa-desktop\x20system';
                    case _0x86d3('0x36'):
                        return _0x86d3('0x37');
                    case 'SETTING':
                        return _0x86d3('0x38');
                    case _0x86d3('0x39'):
                        return _0x86d3('0x3a');
                    default:
                        return _0x86d3('0x3b');
                }
            });
            template[_0x86d3('0x33')]('timeMsg', function(_0x97c5b8) {
                var _0x3e6620, _0x3226b5, _0x14fa58 = new Date();
                _0x3226b5 = _0x97c5b8[_0x86d3('0x3c')](/[- : \/]/);
                _0x3e6620 = new Date(_0x3226b5[0x0], _0x3226b5[0x1] - 0x1, _0x3226b5[0x2], _0x3226b5[0x3], _0x3226b5[0x4], _0x3226b5[0x5]);
                return _0x432133[_0x86d3('0x3d')](_0x3e6620, _0x14fa58);
            });
        },
        'timeDistance': function(_0x1a4345, _0x734d8e) {
            var _0x28054a;
            _0x28054a = _0x734d8e[_0x86d3('0x3e')]() - _0x1a4345[_0x86d3('0x3e')]();
            for (var _0x1236ea = 0x0; _0x1236ea < 0x6; _0x1236ea++) {
                switch (_0x1236ea) {
                    case 0x0:
                        _0x28054a = _0x28054a / 0x3e8;
                        if (_0x28054a >= 0x1 && _0x28054a < 0x3c) {
                            return _0x28054a['toFixed'](0x0) + '秒前';
                        } else if (_0x28054a < 0x1) {
                            return '刚刚';
                        }
                        break;
                    case 0x1:
                        _0x28054a = _0x28054a / 0x3c;
                        if (_0x28054a >= 0x0 && _0x28054a < 0x3c) {
                            return _0x28054a[_0x86d3('0x3f')](0x0) + _0x86d3('0x40');
                        }
                        break;
                    case 0x2:
                        _0x28054a = _0x28054a / 0x3c;
                        if (_0x28054a >= 0x0 && _0x28054a < 0x3c) {
                            return _0x28054a['toFixed'](0x0) + _0x86d3('0x41');
                        }
                        break;
                    case 0x3:
                        _0x28054a = _0x28054a / 0x18;
                        if (_0x28054a >= 0x0 && _0x28054a < 0x3c) {
                            return _0x28054a[_0x86d3('0x3f')](0x0) + '天前';
                        }
                        break;
                    case 0x4:
                        _0x28054a = _0x28054a / 0x1e;
                        if (_0x28054a >= 0x0 && _0x28054a < 0x3c) {
                            return _0x28054a[_0x86d3('0x3f')](0x0) + '月前';
                        }
                        break;
                    case 0x5:
                        _0x28054a = _0x28054a / 0x16d;
                        if (_0x28054a >= 0x0 && _0x28054a < 0x3c) {
                            return _0x28054a[_0x86d3('0x3f')](0x0) + '年前';
                        }
                        break;
                }
            }
        }
    };
}(window, document, jQuery));