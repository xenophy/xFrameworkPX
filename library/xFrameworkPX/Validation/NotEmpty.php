<?php

// SVN $Id: NotEmpty.php 1178 2010-01-05 15:13:08Z tamari $

/**
 * xFrameworkPX_Validation_NotEmpty Class File
 *
 * PHP versions 5
 *
 * xFrameworkPX : MVC Web application framework (http://px.xframework.net)
 * Copyright 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 *
 * Licensed under The MIT
 *
 * @filesource
 * @copyright     (c) 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 * @link          http://www.xframeworkpx.com xFrameworkPX
 * @package       xFrameworkPX\Validation
 * @since         xFrameworkPX 3.5.0
 * @version       $Revision: 1178 $
 * @license       http://www.opensource.org/licenses/mit-license.php
 */

// {{{ xFrameworkPX_Validation_NotEmpty

/**
 * xFrameworkPX_Validation_NotEmpty Class
 *
 * @final
 * @copyright     (c) 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 * @link          http://www.xframeworkpx.com xFrameworkPX
 * @package       xFrameworkPX\Validation
 * @version       xFrameworkPX 3.5.0
 * @license       http://www.opensource.org/licenses/mit-license.php
 */
class xFrameworkPX_Validation_NotEmpty
{

    // {{{ validate

    /**
     * validate
     *
     * @param mixed 検査データ
     * @return bool true:OK, false:NG
     */
    public function validate($target)
    {
        return  $target !== '';
    }

    // }}}

}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
