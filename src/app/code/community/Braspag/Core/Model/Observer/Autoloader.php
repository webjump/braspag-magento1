<?php
/**
 * Webjump BrasPag Pagador
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.webjump.com.br
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@webjump.com so we can send you a copy immediately.
 *
 * @category  Model
 * @package   Braspag_Core_Model_Pagador
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 */

/**
 * Observer Autoloader
 *
 * @category  Model
 * @package   Braspag_Core_Model_Observer
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2019 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Braspag_Core_Model_Observer_Autoloader
{
    /**
     * This an observer function for the event 'controller_front_init_before'.
     * It prepends our autoloader, so we can load the extra libraries.
     *
     * @param Varien_Event_Observer $event Observer
     *
     * @return  void
     */
    public function controllerFrontInitBefore(Varien_Event_Observer $event)
    {
        spl_autoload_register(array($this, 'load'), true, true);
    }

    /**
     * This function can autoloads classes starting with:
     * - Braspag_Lib
     *
     * @param string $class class name
     *
     * @return void
     */
    public static function load($class)
    {
        if (preg_match('#^(Braspag_Lib)\b#', $class)) {
            require_once(Mage::getBaseDir('lib') . '/' . str_replace('_', '/', $class) . '.php');
        }
    }
}