<?php

class verify extends xFrameworkPX_Controller_Action
{
    public $modules = array(
        'Docs_Tutorial_Rapid_Valid_user'
    );

    public $rapid = array(
        'mode' => 'verify',
        'prevAction' => 'edit',
        'nextAction' => 'save'
    );
}
