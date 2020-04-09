<?php

/**
 * Model Cart
 *
 * @package     Webjump_AmbevCart
 * @author      Webjump Core Team <contato@webjump.com.br>
 * @copyright   2019 Webjump. (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 * @link        http://www.webjump.com.br
 */
class Braspag_Pagador_Model_Block_Composite
{
    protected $children;

    public function __construct()
    {
        $this->children = new \SplObjectStorage;
    }

    /**
     * @param Mage_Core_Block_Template $handler
     */
    public function addChild(Mage_Core_Block_Template $handler)
    {
        $this->children->attach($handler);
    }

    /**
     * @param $block
     * @return $this
     */
    public function processBlock($block)
    {
        foreach ($this->children as $child) {
            $block->setChild($child);
        }

        return $block;
    }

    public function getChildren()
    {
        return $this->children;
    }
}
