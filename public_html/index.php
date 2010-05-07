<?php

// {{{ Set Library Path

set_include_path('../library/' . PATH_SEPARATOR . get_include_path());

// }}}
// {{{ Include xFrameworPX

include '../locales/ja.php';
include 'xFrameworkPX/Loader/Core.php';

// }}}
// {{{ xFrameworkPX Run

xFrameworkPX_Dispatcher::getInstance()->run(
    array(
        'DEBUG' => 2
    )
);

// }}}
