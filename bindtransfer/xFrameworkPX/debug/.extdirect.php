<?php

class extdirect extends xFrameworkPX_Controller_ExtDirect
{
    public $direct = array(
        'descriptor' => 'PXDEBUG.app.REMOTING_API'
    );

    public $modules = array(
        'xFrameworkPX_DebugTools' => array(),
    );
}
