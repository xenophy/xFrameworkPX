<?php

class bind1 extends xFrameworkPX_Controller_Action
{

    public $modules = array(
        'Docs_Tutorial_Association_bind1_item',
    );

    public function execute() {

        $this->set('test', $this->Docs_Tutorial_Association_bind1_item->test());

    }

}
