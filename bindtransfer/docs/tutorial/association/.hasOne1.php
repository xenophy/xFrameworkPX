<?php

class hasOne1 extends xFrameworkPX_Controller_Action
{

    public $modules = array(
        'Docs_Tutorial_Association_hasOne1_uriage',
        'Docs_Tutorial_Association_hasOne1_customer',
    );

    public function execute() {

        // テストした結果（配列）を格納
        $this->set('test', $this->Docs_Tutorial_Association_hasOne1_uriage->test());

    }

}
