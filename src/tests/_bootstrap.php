<?php

require_once __DIR__ . '/../vendor/autoload.php';

\Codeception\Util\Autoload::registerSuffix('Page', __DIR__ . DIRECTORY_SEPARATOR . '_pages');
\Codeception\Util\Autoload::registerSuffix('PaymentMethod', __DIR__ . DIRECTORY_SEPARATOR . '_pages/_paymentmethod');
\Codeception\Util\Autoload::register('Codeception\Module\Magento', '', __DIR__ . DIRECTORY_SEPARATOR . '_support/_magento');

$app = \Codeception\Module\Magento\App::init();
$app->createExampleCustomer();
