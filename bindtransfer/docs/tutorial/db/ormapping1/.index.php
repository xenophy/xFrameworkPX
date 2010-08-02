<?php

class index extends xFrameworkPX_Controller_Action
{
    public $modules = array('Docs_Tutorial_Database_Ormapping1_sample');

    public function execute()
    {
        $this->set(
            'alldata',
            $this->Docs_Tutorial_Database_Ormapping1_sample->getDataAll()
        );

        $this->set(
            'alldata2',
            $this->Docs_Tutorial_Database_Ormapping1_sample->getDataAll2()
        );

        $this->set(
            'alldata3',
            $this->Docs_Tutorial_Database_Ormapping1_sample->getDataAll3()
        );

        $this->set(
            'alldata4',
            $this->Docs_Tutorial_Database_Ormapping1_sample->getDataAll4()
        );
    }
}
