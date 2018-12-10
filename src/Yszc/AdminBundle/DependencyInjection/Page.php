<?php
namespace App\Yszc\AdminBundle\DependencyInjection;

    /**
     * 分页类
     * @author ydmx_lei
     *  2013-07-05
     */
class Page {
    public $perPage = 5;           //页码数上下幅度
    public $swpara = 4; //首尾页省略号参数
    public $paramStr = "";          //分页参数字符串变量，连接地址
    public $currentPage = 1;         //当前页码
    public $recordCount = 0;
    public $paramName = 'p';   //翻页参数名称
    public $pageSize = 20;          //每页记录数
    public $tabid = '';    //模块区分标识
    public $isJS = false;    //是否js显示链接
    public $prefix = '.html';    //后缀
    public $is_page_nums = '2';
    public $one_show =true;//只有一页时是否显示页码
    public $trunStr = ["首页", "上页", "下页", "末页"]; //翻页字符

    /*
     * 构造函数
     * $recordCount 为记录总数量
     * $pageSize     为每页显示记录数
     */
    public function __construct($request,$pageParams) {
        $this->paramName = $pageParams['paramName'];
        $this->pageSize = $pageParams['pageSize'];
        $this->paramStr = md5($request->getCurrentRequest()->server->get('REQUEST_URI'));
    }


    public function setPage($recordCount = 0, $currentPage = 1, $isJS = false, $router = '',$pageSize=null) {
        if (!is_numeric($recordCount))
            return;
        if (!is_numeric($pageSize))
            $pageSize = $this->pageSize;

        //记录总数
        if (intval($recordCount) == 0)
            $recordCount = $this->recordCount;
        else
            $this->recordCount = $recordCount;

        //每页显示记录数
        if (intval($pageSize)<= 0)
            $pageSize = $this->pageSize;
        else
            $this->pageSize = $pageSize;
        
        $this->isJS = $isJS;        
        $this->paramName = $this->paramName;
        $this->tabid = (empty($router) && $this->isJS==false) ? $this->paramStr : (empty($router) && $this->isJS ? '' : $router);
        $this->currentPage = $currentPage < 1 ? 1 : $currentPage;

        $this->currentPage(); //当前页码处理
        $this->paramStr(); //当前页地址参数

        //为当前页定界
        if ($this->currentPage > $this->pageCount())
            $this->currentPage = $this->pageCount() < 1 ? 1 : $this->pageCount();
    }

    public function show() {
        $pageStr = '';
        if ($this->recordCount > 0) {
            if ('0' == $this->is_page_nums) {
                $pageStr = $this->getTotalCount(); //总记录
                $pageStr .= $this->trunUp();     //向上
                $pageStr .= $this->trunNum();    //数字页码
                $pageStr .= $this->trunDown();   //向下
                $pageStr .= $this->jumpTo(); //跳转
            } else if ('1' == $this->is_page_nums) {
                $pageStr = $this->upTurn();     //向上
                $pageStr .= $this->downTurn();   //向下
            } else if ('2' == $this->is_page_nums) {
	            $pageStr = $this->getTotalCount(); //总记录
                $pageStr .= $this->trunUp();     //向上
                $pageStr .= $this->trunNum();    //数字页码
                $pageStr .= $this->trunDown();   //向下
            } else if ('3' == $this->is_page_nums) {
                if($this->pageCount()>1){
                    $this->trunStr = array('<','>');
                    $pageStr = $this->trunUp3();     //向上
                    $pageStr .= $this->trunNum3();    //数字页码
                    $pageStr .= $this->trunDown3();   //向下
                    $pageStr .= $this->jumpTo3(); //跳转
                }
            }
        }

        if(1==$this->pageCount() && !$this->one_show ){
            $pageStr = '';
        }

        return $pageStr;
    }

    public function getPageInfo() {
        $pageArr = [];
        $pageArr['total'] = $this->recordCount;
        $pageArr['pageCount'] =  $this->pageCount();
        $pageArr['currPage'] = $this->currentPage;
        $pageArr['nextPage'] = $this->currentPage;
        $pageArr['prePage'] = $this->currentPage;
        if ($this->recordCount > 0) {
            if($pageArr['pageCount'] > $this->currentPage){
                $pageArr['nextPage'] = $this->currentPage++;
            }

            if(1 < $this->currentPage){
                $pageArr['nextPage'] = $this->currentPage--;
            }
        }


        return $pageArr;
    }

    //向上翻处理
    public function trunUp() {
        $pageStr = '';
        if ($this->currentPage == 1) {
            if ($this->isJS) {
                $pageStr .= '<a class="current">' . $this->trunStr[0] . '</a> <a class="current">' . $this->trunStr[1] . '</a>';
                $pageStr .= ' <a class="current">1</a> ';
            } else {
                $pageStr .= '<a class="disabled" href="javascript:void(0);">' . $this->trunStr[0] . "</a> <a class=\"disabled\" href=\"javascript:void(0);\">" . $this->trunStr[1] . "</a> ";
                $pageStr .= ' <a class="current">1</a> ';
            }
        } else {
            if ($this->isJS) {
                $pageStr .= "<a onclick=\"get" . $this->tabid . "Page(1);\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[0] . "\">" . $this->trunStr[0] . "</a> ";
                $pageStr .= "<a onclick=\"get" . $this->tabid . "Page(" . ($this->currentPage - 1) . ");\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[1] . "\">" . $this->trunStr[1] . "</a> ";
                if ($this->currentPage > $this->swpara) {
                    $pageStr .= "<a onclick=\"get" . $this->tabid . "Page(1);\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[0] . "\">1</a> <span> ... </span>";
                } else {
                    $pageStr .= "<a onclick=\"get" . $this->tabid . "Page(1);\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[0] . "\">1</a> ";
                }
            } else {
                $pageStr .= "<a href=\"" . $this->paramStr. "1". $this->prefix."\" title=\"" . $this->trunStr[0] . "\">" . $this->trunStr[0] . "</a> ";
                $pageStr .= "<a href=\"" . $this->paramStr . ($this->currentPage - 1) . $this->prefix."\" title=\"" . $this->trunStr[1] . "\">" . $this->trunStr[1] . "</a> ";
                if ($this->currentPage > $this->swpara) {
                    $pageStr .= "<a href=\"" . $this->paramStr . "1".$this->prefix."\" title=\"" . $this->trunStr[0] . "\">1</a><span class=\"none\"> ... </span>";
                } else {
                    $pageStr .= "<a href=\"" . $this->paramStr . "1".$this->prefix."\" title=\"" . $this->trunStr[0] . "\">1</a> ";
                }
            }
        }
        return $pageStr;
    }

    //每页显示的页码连接
    public function trunNum() {
        $pageStr = '';
        if ($this->currentPage - $this->perPage >= 1) {
            $start = $this->currentPage - $this->perPage;
        } else {
            $start = 1;
        }
        if ($this->currentPage + $this->perPage <= $this->pageCount()) {
            $end = $this->currentPage + $this->perPage;
        } else {
            $end = $this->pageCount();
        }
        for ($i = $start; $i <= $end; $i++) {
            if ($i == 1 || $i == $this->pageCount()) {
                continue;
            } else {
                if ($this->isJS) {
                    $pageStr .= $i != $this->currentPage ? "<a onclick=\"get" . $this->tabid . "Page(" . $i . ");\" href=\"javascript:void(0);\">$i</a> " : "<a class=\"current\">$i</a> ";
                } else {
                    $pageStr .= $i != $this->currentPage ? "<a href=\"" . $this->paramStr . $i.$this->prefix."\">$i</a> " : "<a  class=\"current\">$i</a> ";
                }
            }
        }
        return $pageStr;
    }

    //向下翻处理
    public function trunDown() {
        $pageStr = '';
        if ($this->pageCount() == $this->currentPage || $this->pageCount() == 0) {
            if ($this->isJS) {
                if ($this->currentPage != 1) {
                    $pageStr .= ' <a class="current">' . $this->pageCount() . '</a> ';
                }
                $pageStr .= ' <a class="current">' . $this->trunStr[2] . '</a>  <a class="current">' . $this->trunStr[3] . '</a> ';
            } else {
                $pageStr .= (1==$this->pageCount()) ? '' : '<a class="current">' . $this->pageCount() . '</a> ';
                $pageStr .= '<a class="disabled" href="javascript:void(0);">' . $this->trunStr[2] . "</a> <a class=\"disabled\" href=\"javascript:void(0);\">" . $this->trunStr[3] . "</a> ";
            }
        } else {
            if ($this->currentPage >= 1 && ($this->pageCount() - $this->swpara) > $this->currentPage) {
                if ($this->isJS) {
                    $pageStr .= "<span> ... </span><a onclick=\"get" . $this->tabid . "Page(" . $this->pageCount() . ");\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[0] . "\">" . $this->pageCount() . "</a>";
                } else {
                    $pageStr .= " <span class=\"none\"> ... </span><a href=\"" . $this->paramStr . $this->pageCount() .$this->prefix. "\" title=\"" . $this->trunStr[0] . "\">" . $this->pageCount() . "</a>";
                }
            } else {
                if ($this->isJS) {
                    $pageStr .= "<a onclick=\"get" . $this->tabid . "Page(" . $this->pageCount() . ");\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[0] . "\">" . $this->pageCount() . "</a>";
                } else {
                    $pageStr .= "<a href=\"" . $this->paramStr . $this->pageCount() .$this->prefix. "\" title=\"" . $this->trunStr[0] . "\">" . $this->pageCount() . "</a>";
                }
            }

            if ($this->isJS) {
                $pageStr .= " <a onclick=\"get" . $this->tabid . "Page(" . ($this->currentPage + 1) . ");\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[2] . "\">" . $this->trunStr[2] . "</a> ";
                $pageStr .= " <a onclick=\"get" . $this->tabid . "Page(" . ($this->pageCount()) . ");\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[3] . "\">" . $this->trunStr[3] . "</a> ";
            } else {
                $pageStr .= " <a href=\"" . $this->paramStr . ($this->currentPage + 1) .$this->prefix. "\"  title=\"" . $this->trunStr[2] . "\">" . $this->trunStr[2] . "</a> ";
                $pageStr .= " <a href=\"" . $this->paramStr . $this->pageCount() . $this->prefix."\" title=\"" . $this->trunStr[3] . "\">" . $this->trunStr[3] . "</a> ";
            }
        }
        return $pageStr;
    }

    //当前页参数
    public function paramStr() {
        unset($_GET[$this->paramName]); //去掉翻页参数
        if (count($_GET)) {
            foreach ($_GET AS $param => $value) {
                $this->paramStr .= $this->paramStr ? "&" : "?";
                $this->paramStr .= $param . "=" . $value;
            }
            $this->paramStr .= "&" . $this->paramName . "=";
        } else {
            $this->paramStr = "?$this->paramName=";
        }
    }

    //取得当前页码
    public function currentPage() {
        $p =$this->paramName;
        if (isset($p) && intval($p) > 0) {
            $this->currentPage = intval($p);
        }
        return $this->currentPage;
    }

    //总页数
    public function pageCount() {
        return ceil($this->recordCount / $this->pageSize); //总页数
    }

    public function frontTurn() {
        $pageStr = '';
        if ($this->currentPage == 1) {
            if ($this->isJS) {
                $pageStr .= '<a class="current">1</a> ';
            } else {
                $pageStr .= $this->currentPage . " ";
            }
        } else {
            if ($this->currentPage >= $this->swpara) {
                if ($this->isJS) {
                    $pageStr .= "<a onclick=\"get" . $this->tabid . "Page(1);\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[0] . "\">1</a> <span> ... </span>";
                }else{
                    $pageStr .= "<a href=\"" . $this->paramStr . (1) . $this->prefix . "\" title=\"1\">1</a><span> ... </span>";
                }

            } else {
                if ($this->isJS) {
                    $pageStr .= "<a onclick=\"get" . $this->tabid . "Page(1);\" href=\"javascript:void(0);\" title=\"" . ($this->currentPage - 1) . "\">" . ($this->currentPage - 1) . "</a> ";
                } else {
                    $pageStr .= "<a href=\"" . $this->paramStr . (1) . $this->prefix . "\" title=\"1\">1</a> ";
                }

            }
        }
        return $pageStr;
    }

    public function nextTurn() {
        $pageStr = '';
        if ($this->pageCount() == $this->currentPage || $this->pageCount() == 0) {
            if ($this->currentPage > 1) {
                if ($this->isJS) {
                    $pageStr .= '  <a class="current">' . $this->pageCount() . '</a> ';
                } else {
                    $pageStr .= $this->pageCount() . " ";
                }
            }
        } else {
            if (($this->pageCount() - $this->swpara) >= $this->currentPage - 1) {
                if ($this->isJS) {
                    $pageStr .= "<span> ... </span><a onclick=\"get" . $this->tabid . "Page(" . $this->pageCount() . ");\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[0] . "\">" . $this->pageCount() . "</a>";
                } else {
                    $pageStr .= " <span> ... </span><a href=\"" . $this->paramStr . $this->pageCount() .$this->prefix. "\" title=\"" . $this->trunStr[0] . "\">" . $this->pageCount() . "</a>";
                }
            } else {
                if ($this->isJS) {
                    $pageStr .= "<a onclick=\"get" . $this->tabid . "Page(" . $this->pageCount() . ");\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[0] . "\">" . $this->pageCount() . "</a>";
                } else {
                    $pageStr .= "<a href=\"" . $this->paramStr . $this->pageCount() .$this->prefix. "\" title=\"" . $this->trunStr[0] . "\">" . $this->pageCount() . "</a>";
                }
            }
        }
        return $pageStr;
    }

    public function upTurn() {
        $pageStr = '';
        if ($this->currentPage == 1) {
            if ($this->isJS) {
                $pageStr .= '<a class="current">' . $this->trunStr[1] . '</a>';
            } else {
                $pageStr .= ' ' . $this->trunStr[1];
            }
        } else {
            if ($this->isJS) {
                $pageStr .= "<a onclick=\"get" . $this->tabid . "Page(" . ($this->currentPage - 1) . ");\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[1] . "\">" . $this->trunStr[1] . "</a> ";
            } else {
                $pageStr .= "<a href=\"" . $this->paramStr . ($this->currentPage - 1) . $this->prefix . "\" title=\"" . $this->trunStr[1] . "\">" . $this->trunStr[1] . "</a> ";
            }
        }
        return $pageStr;
    }

    public function downTurn() {
        $pageStr = '';
        if ($this->pageCount() == $this->currentPage || $this->pageCount() == 0) {
            if ($this->isJS) {
                $pageStr .= '  <a class="current">' . $this->trunStr[2] . '</a> ';
            } else {
                $pageStr .= $this->trunStr[2] . " ";
            }
        } else {
            if ($this->isJS) {
                $pageStr .= " <a onclick=\"get" . $this->tabid . "Page(" . ($this->currentPage + 1) . ");\" href=\"javascript:void(0);\" title=\"" . $this->trunStr[2] . "\">" . $this->trunStr[2] . "</a> ";
            } else {
                $pageStr.="<a href=\"" . $this->paramStr . ($this->currentPage + 1) . $this->prefix . "\" title=\"" . $this->trunStr[2] . "\">" . $this->trunStr[2] . "</a> ";
            }
        }
        return $pageStr;
    }

    //向上翻处理 适用于静态url
    public function trunUp3() {
        $pageStr = '';
        if ($this->currentPage == 1) {
            if ($this->isJS) {
                $pageStr .= "<span class=\"page01\"><a href=\"javascript:void(0);\">" . $this->trunStr[0] . "</a></span>";
                $pageStr .= '<span class="page-xu"><a href="javascript:void(0);">1</a></span>';
            } else {
                $pageStr .= "<span class=\"page01\"><a href=\"javascript:void(0);\">" . $this->trunStr[0] . "</a></span>";
                $pageStr .= '<span class="page-xu"><a href="javascript:void(0);">1</a></span>';
            }
        } else {
            if ($this->isJS) {
                $pageStr .= "<span class=\"page02\"><a onclick=\"get" . $this->tabid . "Page(" . ($this->currentPage - 1) . ");\" href=\"javascript:void(0);\">" . $this->trunStr[0] . "</a></span>";
                if ($this->currentPage > $this->swpara) {
                    $pageStr .= "<span class=\"page02\"><a onclick=\"get" . $this->tabid . "Page(1);\" href=\"javascript:void(0);\">1</a></span><span><a href=\"javascript:void(0);\">...</a></span>";
                } else {
                    $pageStr .= "<span class=\"page02\"><a onclick=\"get" . $this->tabid . "Page(1);\" href=\"javascript:void(0);\">1</a></span>";
                }

            } else {
                $pageStr .= "<span class=\"page02\"><a href=\"" . $this->paramStr . ($this->currentPage - 1) . $this->prefix . "\">" . $this->trunStr[0] . "</a></span>";
                if ($this->currentPage > $this->swpara) {
                    $pageStr .= "<span class=\"page02\"><a href=\"" . $this->paramStr . '1' . $this->prefix . "\">1</a></span><span><a href=\"javascript:void(0);\">...</a></span>";
                } else {
                    $pageStr .= "<span class=\"page02\"><a href=\"" . $this->paramStr . '1' . $this->prefix . "\">1</a></span>";
                }
            }
        }
        return $pageStr;
    }

    //每页显示的页码连接
    public function trunNum3() {
        $pageStr = '';
        if ($this->currentPage - $this->perPage >= 1) {
            $start = $this->currentPage - $this->perPage;
        } else {
            $start = 1;
        }
        if ($this->currentPage + $this->perPage <= $this->pageCount()) {
            $end = $this->currentPage + $this->perPage;
        } else {
            $end = $this->pageCount();
        }
        for ($i = $start; $i <= $end; $i++) {
            if ($i == 1 || $i == $this->pageCount()) {
                continue;
            } else {
                if ($this->isJS) {
                    $pageStr .= $i != $this->currentPage ? "<span><a onclick=\"get" . $this->tabid . "Page(" . $i . ");\" href=\"javascript:void(0);\">$i</a></span>" : "<span class=\"page-xu\"><a href=\"javascript:void(0);\">$i</a></span>";
                } else {
                    $pageStr .= $i != $this->currentPage ? "<span><a href=\"" . $this->paramStr . $i . $this->prefix . "\">$i</a></span>" : "<span class=\"page-xu\"><a href=\"javascript:void(0);\">$i</a></span>";
                }
            }
        }
        return $pageStr;
    }

    //向下翻处理
    public function trunDown3() {
        $pageStr = '';
        if ($this->pageCount() == $this->currentPage || $this->pageCount() == 0) {
            if ($this->isJS) {
                if ($this->currentPage != 1) {
                    $pageStr .= "<span class=\"page-xu\"><a href=\"javascript:void(0);\">" . $this->pageCount() . "</a></span>";
                }
                $pageStr .= "<span class=\"page01\"><a href=\"javascript:void(0);\">" . $this->trunStr[1] . "</a></span>";
            } else {
                if ($this->currentPage != 1) {
                    $pageStr .= "<span class=\"page-xu\"><a href=\"javascript:void(0);\">" . $this->pageCount() . "</a></span>";
                }
                $pageStr .= "<span class=\"page01\"><a href=\"javascript:void(0);\">" . $this->trunStr[1] . "</a></span>";
            }
        } else {
            if ($this->currentPage >= 1 && ($this->pageCount() - $this->swpara + 1) > $this->currentPage) {
                if ($this->isJS) {
                    $pageStr .= "<span><a href=\"javascript:void(0);\">...</a></span><span><a onclick=\"get" . $this->tabid . "Page(" . $this->pageCount() . ");\" href=\"javascript:void(0);\">" . $this->pageCount() . "</a></span>";
                } else {
                    $pageStr .= "<span><a href=\"javascript:void(0);\">...</a></span><span><a href=\"" . $this->paramStr . $this->pageCount() . $this->prefix . "\">" . $this->pageCount() . "</a></span>";
                }
            } else {
                if ($this->isJS) {
                    $pageStr .= "<span><a onclick=\"get" . $this->tabid . "Page(" . $this->pageCount() . ");\" href=\"javascript:void(0);\">" . $this->pageCount() . "</a></span>";
                } else {
                    $pageStr .= "<span><a href=\"" . $this->paramStr . $this->pageCount() . $this->prefix . "\">" . $this->pageCount() . "</a></span>";
                }
            }

            if ($this->isJS) {
                $pageStr .= "<span class=\"page02\"><a onclick=\"get" . $this->tabid . "Page(" . ($this->currentPage + 1) . ");\" href=\"javascript:void(0);\"> " . $this->trunStr[1] . " </a></span>";
            } else {
                $pageStr .= "<span class=\"page02\"><a href=\"" . $this->paramStr . ($this->currentPage + 1) . $this->prefix . "\"> " . $this->trunStr[1] . " </a></span>";
            }
        }
        return $pageStr;
    }

    //跳转
    public function jumpTo3() {
        $pageStr = '';
        $pageStr .= "<span>跳转到：</span><span><input type=\"text\" id=\"" . $this->tabid . "Nums\" value=\"" . $this->currentPage . "\" class=\"inputnum\"></span>";
        if ($this->isJS) {
            $pageStr .= "<span><a href=\"javascript:void(0);\" onclick=\"get" . $this->tabid . "Page($('#" . $this->tabid . "Nums').val());\">GO</a></span>";
        } else {
            $pageStr .= "<span><a href=\"javascript:void(0);\" onclick=\"location.href='" . $this->paramStr . "'+$('#" . $this->tabid . "Nums').val()+'". $this->prefix ."'\">GO</a></span>";
        }

        return $pageStr;
    }

    //设置每页显示页码数,页码数至少大于1
    public function setPerPage($num = 3) {
        if (intval($num) > 1)
            $this->perPage = intval($num);
    }

    //跳转
    public function jumpTo() {
        $pageStr = '';
        $pageStr .= "<span class='font'>跳转到</span><input class=\"text\" style=\"width:30px;\" type='text' id=\"" . $this->tabid . "Nums\" value=\"" . $this->currentPage . "\"/> ";
        if ($this->isJS) {
            $pageStr .= '<input class="button" value="GO" type="button" ' . " onclick=\"get" . $this->tabid . "Page($('#" . $this->tabid . "Nums').val());\"/>";
        } else {
            $pageStr .= '<input class="button"  value="GO" type="button" ' . " onclick=\"window.location='" . $this->paramStr . "'+$('#" . $this->tabid . "Nums').val();\"/>";
        }

        return $pageStr;
    }

    //总数
    public function getTotalCount() {
        $pageStr = '';
        $pageStr .= '<a title="总记录' . ($this->recordCount) . '" style="cursor:text;background: none repeat scroll 0 0;">总记录:' . ($this->recordCount) . '</a>';
        return $pageStr;
    }

}