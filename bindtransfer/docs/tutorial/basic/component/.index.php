<?php

class index extends xFrameworkPX_Controller_Action
{
    protected $_components = array(
        array(
            'clsName' => 'Docs_Tutorial_Basic_Comp',
            'bindName' => 'Comp'
        )
    );

    public function execute()
    {
        $this->set('data', $this->Comp->getTestData());
    }
}
