<?php

class bind extends xFrameworkPX_Controller_Action
{

    public $modules = array(
        'Docs_Tutorial_Database_bind_item',
    );

    public function execute() {

        $this->set('test', $this->Docs_Tutorial_Database_bind_item->test());

    }

}
