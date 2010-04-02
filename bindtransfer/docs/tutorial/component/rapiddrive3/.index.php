<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Component_RapidDrive1_sample');

    public $rapid = array(
        'mode' => 'list',
        'count' => 2,
        'next_action' => 'detail',
        'search_field' => array(
            'title',
            'note'
        ),
        'field_filter' => array(
            'id'
        ),
        'order_field' => array(
            'id' => ''
        ),
        'no_item_message' => 'データがありません。'
    );
}
