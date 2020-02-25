<?php

class Braspag_AntiFraud_Model_Source_SequenceCriteria extends Braspag_Core_Model_Source_Abstract_Config
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
