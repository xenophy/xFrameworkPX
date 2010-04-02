<?php

class Docs_Tutorial_Model_Db10_sample extends xFrameworkPX_Model
{
    public function getDataAll()
    {
        return $this->get('all');
    }

    public function removeData($id)
    {
        $this->remove(array($id));
    }
}

