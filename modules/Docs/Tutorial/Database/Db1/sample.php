<?php

class Docs_Tutorial_Database_Db1_sample extends xFrameworkPX_Model
{
    public function getData()
    {
        return $this->row(
            array(
                'query' => 'SELECT * FROM ' . $this->getTableName()
            )
        );
    }

    public function getDataAll()
    {
        return $this->rowAll(
            array(
                'query' => 'SELECT * FROM ' . $this->getTableName()
            )
        );
    }
}
