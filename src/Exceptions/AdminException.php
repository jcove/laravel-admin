<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/23
 * Time: 11:18
 */

namespace Jcove\Admin\Exceptions;


class AdminException extends \Exception
{

    /**
     * AdminException constructor.
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}