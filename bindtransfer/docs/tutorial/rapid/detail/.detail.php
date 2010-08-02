<?php

class detail extends xFrameworkPX_Controller_Action
{
    public $modules = array(
        'Docs_Tutorial_Rapid_user'
    );

    public $rapid = array(
        'mode' => 'detail',
        'count' => 5,
        'module' => 'Docs_Tutorial_Rapid_user',
        'param_name_id' => 'id',
        'search' => array(
            'del' => '0'
        ),
        'field_filter' => array(
            'tbl_user' => array('del')
        ),
        'prevAction' => 'index'
    );
}
