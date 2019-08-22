<?php
// Here you can initialize variables that will be available to your tests

Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/active', 1, 'default', 0);
Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo_flag', 1, 'default', 0);
Mage::getConfig()->saveConfig('payment/webjump_braspag_post_passthru_cc/acquirer_cielo', '997', 'default', 0);
Mage::getConfig()->reinit();
