<?php

class Docs_Tutorial_Database_Model_sample extends xFrameworkPX_Model
{
    public function testMethod()
    {
        if ($this->usetable !== false) {
            return 'データベースと接続しています。';
        } else {
            return 'データベースと接続していません。';
        }
    }
}

