<?php

class Docs_Tutorial_Database_Ormapping3_sample extends xFrameworkPX_Model
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

