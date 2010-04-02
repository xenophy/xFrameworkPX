<?php

class detail extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Component_RapidDrive1_sample');

    public $rapid = array(
        'mode' => 'detail',
        'field_filter' => array(
            'id'
        ),
    );

}

