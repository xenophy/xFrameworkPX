<?php

// SVN $Id: articletest.php 951 2009-12-25 11:40:13Z tamari $

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
 * @version       $Revision: 951 $
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
class article_test_Model extends xFrameworkPX_Model_RapidDrive {

    // {{{ props


    protected $useTable = 'tbl_article';

    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @return void
     * @access public
     */
    public function __construct( $objParam, $test ) {

        $this->useTable = $test;

        parent::__construct( $objParam );

    }

    // }}}

    /**
     * バリデーター設定
     *
     * @var array
     * @access protected
     */
    protected $arrValidator = array(

        // {{{ titleフィールド

        'title' => array(

            // {{{ ルール設定

            'rule' => 'NotEmpty',

            // }}}
            // {{{ エラーメッセージ

            'message' => 'タイトルを入力してください。'

            // }}}

        ),

        // }}}
        // {{{ messageフィールド

        'message' => array(

            // {{{ ルール設定

            'rule' => 'NotEmpty',

            // }}}
            // {{{ エラーメッセージ

            'message' => 'メッセージを入力してください。'

            // }}}

        )

        // }}}

    );

    // }}}

}

// }}}

?>