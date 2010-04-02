<?php

class hasOne7 extends xFrameworkPX_Controller_Action
{

    public $modules = array(
        'Docs_Tutorial_Association_hasOne7_uriage',
    );

    public function execute() {

        // テストした結果（配列）を格納
        $this->set('test', $this->Docs_Tutorial_Association_hasOne7_uriage->test());

    }

}
