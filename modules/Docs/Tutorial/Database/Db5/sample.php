<?php

class Docs_Tutorial_Database_Db5_sample extends xFrameworkPX_Model
{
    public function deleteData($id)
    {
        $this->delete(
            array(
                'bind' => array(
                    'id' => $id,
                ),
                'where' => array(
                    'id = :id'
                )
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

