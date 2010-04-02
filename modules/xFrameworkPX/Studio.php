<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * xFrameworkPX_Studio Class File
 *
 * PHP versions 5
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX_Studio
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    SVN $Id: Studio.php 1468 2010-01-22 12:45:16Z kotsutsumi $
 */

// {{{ xFrameworkPX_Studio

/**
 * xFrameworkPX_Studio Class
 *
 * @category   xFrameworkPX
 * @package    xFrameworkPX
 * @author     Kazuhiro Kotsutsumi <kotsutsumi@xenophy.com>
 * @copyright  Copyright (c) 2006-2010 Xenophy.CO.,LTD All rights Reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @version    Release: 1.0.0
 */
class xFrameworkPX_Studio extends xFrameworkPX_Model
{
    // {{{ props

    /**
     * 使用テーブル設定
     *
     * @var string
     */
    public $usetable = false;

    // }}}
    // {{{ getVirtualScreen

    public function getVirtualScreen($id) {

        $out = array();

        if ($id == 'root') {

            $out = $this->_getList($this->conf->px['WEBROOT_DIR']);

        } else {
            $out = $this->_getList($id);
        }

        return $out;
    }

    // }}}
    // {{{ _getList
    
    private function _getList($target)
    {
        $ret = array();
        $files = scandir($target);
        $file = array();
        $dir = array();

        foreach ($files as $entry) {
            if (
                is_dir($target . DS . $entry) &&
                $entry != '.' &&
                $entry != '..' &&
                !startsWith($entry, '.')
            ) {
                array_push($dir, array(
                    'id' => $target . DS . $entry,
                    'text' => $entry,
                    'leaf' => false
                ));
            } else if(
                !is_dir($target . DS . $entry) &&
                startsWith($entry, '.') &&
                endsWith($entry, '.php')
            ) {
                array_push($file, array(
                    'id' => $target . DS . $entry,
                    'iconCls' => 'pxstudio-navi-vscreen',
                    'text' => substr(get_filename($entry), 1),
                    'leaf' => true
                ));
            } else if(
                !is_dir($target . DS . $entry) &&
                pathinfo($entry, PATHINFO_EXTENSION) === 'html' &&
                !$this->_isExistsFile($file, get_filename($entry))
            ) {
                array_push($file, array(
                    'id' => $target . DS . $entry,
                    'iconCls' => 'pxstudio-navi-vscreen-nocon',
                    'text' => get_filename($entry),
                    'leaf' => true
                ));
            }
        }

        $ret = array_merge($ret, $dir);
        $ret = array_merge($ret, $file);
        
        return $ret;
    }
    
    // }}}
    // {{{ _isExistsFile
    
    private function _isExistsFile($array, $text)
    {
        foreach ($array as $value) {
            if ($value['text'] === $text) {
                return true;
            }
        }
    
        return false;
    }
    
    // }}}
    // {{{ createVScreen
    
    public function createVScreen($data)
    {
        $webroot = $this->conf->px['WEBROOT_DIR'];

        // テンプレート作成
        if ($data->template === true) {

            $file = $webroot . DS . $data->path . DS . $data->name . '.html';

            // テンプレート作成
            $this->view->smarty->assign('title', $data->name);
            $tpl = $this->view->smarty->fetch(
                $this->conf->px['TEMPLATE_DIR'] . '/xFrameworkPXStudio/template.html'
            );

            // テンプレート出力
            file_put_contents($file, $tpl);
        }

        // コントローラー生成
        if ($data->controller === true) {

            $file = $webroot . DS . $data->path . DS . '.' .$data->name . '.php';

            // テンプレート作成
            $this->view->smarty->assign('title', $data->name);
            $tpl = $this->view->smarty->fetch(
                $this->conf->px['TEMPLATE_DIR'] . '/xFrameworkPXStudio/controller.php'
            );

            // テンプレート出力
            file_put_contents($file, $tpl);

        }

    }
    
    // }}}
    // {{{ getOpenUrl
    
    public function getOpenUrl($id) {

        if (pathinfo($id, PATHINFO_EXTENSION) === 'php') {
            $id = dirname($id) . '/' . substr(get_filename($id), 1) . '.html';
        }

        $url = 
            base_name() . normalize_path(
                substr_replace($id, '', 0, strlen($this->conf->px['WEBROOT_DIR']) + 1),
                '/'
            );

        return $url;
    }
    
    // }}}
    
}

// }}}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
