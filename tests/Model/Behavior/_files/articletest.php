<?php

// SVN $Id: articletest.php 943 2009-12-25 07:12:54Z tamari $

/**
 * articletest Class File
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
 * @package       xFrameworkPX\Tests\Model\Behavior
 * @since         xFrameworkPX 3.5.0
 * @version       $Revision: 943 $
 * @license       http://www.opensource.org/licenses/mit-license.php
 */

// {{{ articletest

/**
 * articletest Class
 *
 * @copyright     (c) 2006 - 2009 Xenophy CO., LTD.(http://www.xenophy.com)
 * @link          http://www.xframeworkpx.com xFrameworkPX
 * @version       xFrameworkPX 3.5.0
 * @license       http://www.opensource.org/licenses/mit-license.php
 */
class article_test extends xFrameworkPX_Model_RapidDrive {

    public $usetable = 'tbl_article_test';

    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @return void
     * @access public
     */
    public function __construct( $objParam ) {

        parent::__construct( $objParam );

    }

    // }}}

}

// }}}

?>