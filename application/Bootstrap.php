<?php
/**
 * Zend Framework Pastebin Application
 *
 * @author Seb Flippence
 * @see http://seb.flippence.net
 * @version v0.5
 * @license GNU General Public License - 2009
 */

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default_',
            'basePath'  => dirname(__FILE__),
        ));
        return $autoloader;
    }


    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');

    }


}

