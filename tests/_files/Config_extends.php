<?php

// {{{ 

class Config_extends extends xFrameworkPX_Config {

    // {{{ __construct

    /**
     * コンストラクタ
     *
     * @access private
     */
    private function __construct() {

    }

    // }}}
    // {{{ getInstance

    /**
     * インスタンス取得メソッド
     *
     * @return xFrameworkPXインスタンス
     * @access public
     */
    public static function getInstance() {

        // {{{ インスタンス取得

        if( !isset( self::$_instance ) ) {
            self::$_instance = new Config_extends();
        }

        // }}}

        return self::$_instance;
    }

    // }}}
    // {{{ endTest

    /**
     * インスタンス破棄メソッド
     *
     * @return void
     * @access public
     */
    public function endTest() {

        self::$_instance = null;

    }

    // }}}

}

// }}}

?>