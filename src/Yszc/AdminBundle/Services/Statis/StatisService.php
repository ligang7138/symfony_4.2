<?php

namespace App\Yszc\AdminBundle\Services\Statis;


interface StatisService
{
    const STATIS_TOTAL_NUMS         = 'statis:shop_total_nums:';
    const STATIS_ORDER_NUMS         = 'statis:statis_order_nums:';
    const STATIS_PAY_NUMS         = 'statis:statis_pay_nums:';

    const STATIS_USERREG_NUMS       = 'statis:shop_userreg_nums:';
    const STATIS_REAL_USERREG_NUMS  = 'statis:shop_real_userreg_nums:';
    const STATIS_BANK_USERREG_NUMS  = 'statis:shop_bank_userreg_nums:';

    const GROUP_TYPE_DAY = '1';

    const GROUP_TYPE_MONTH = '2';


}