<?php

class save extends xFrameworkPX_Controller_Action
{
    public $modules = array(
        'Docs_Tutorial_Rapid_Valid_user'
    );

    public $rapid = array(
        'mode' => 'save',
        'transaction' => true,
        'lock' => true,
        'prevAction' => 'verify',
        'nextAction' => 'fin'
    );
}
