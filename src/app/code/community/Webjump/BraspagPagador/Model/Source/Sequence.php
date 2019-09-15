<?php

class Webjump_BraspagPagador_Model_Source_Sequence extends Webjump_BraspagPagador_Model_Source_Abstract_Config
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'AnalyseFirst',
                'label' => __('Analyse First'),
            ],
            [
                'value' => 'AuthorizeFirst',
                'label' => __('Authorize First')
            ]
        ];
    }
}
