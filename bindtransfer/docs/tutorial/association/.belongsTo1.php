<?php

class belongsTo1 extends xFrameworkPX_Controller_Action
{
    public $modules = array(
        'Docs_Tutorial_Association_belongsTo1_item',
    );

    public function execute() {

        // テストした結果（配列）を格納
        $this->set('test', $this->Docs_Tutorial_Association_belongsTo1_item->test());

    }

}
