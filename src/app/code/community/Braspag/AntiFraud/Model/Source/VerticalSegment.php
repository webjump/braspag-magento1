<?php

class Braspag_AntiFraud_Model_Source_VerticalSegment extends Braspag_Core_Model_Source_Abstract_Config
{
    public function toOptionArray()
    {
        return [
            [
                'value' => 'Retail',
                'label' => __('Retail'),
            ],
            [
                'value' => 'Cosmeticos',
                'label' => __('Cosmeticos')
            ],
            [
                'value' => 'Joalheria',
                'label' => __('Joalheria')
            ],
            [
                'value' => 'DigitalGoods',
                'label' => __('Digital Goods')
            ],
            [
                'value' => 'Servicos',
                'label' => __('Servicos')
            ],
            [
                'value' => 'Turismo',
                'label' => __('Turismo')
            ],
            [
                'value' => 'Generico',
                'label' => __('Generico')
            ]
        ];
    }
}
