<?php

class Webjump_BraspagPagador_Model_Source_SequenceCriteria extends Webjump_BraspagPagador_Model_Source_Abstract_Config
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'OnSuccess',
                'label' => __('On Success'),
            ],
            [
                'value' => 'AuthorizeFirst',
                'label' => __('Always')
            ]
        ];
    }
}
