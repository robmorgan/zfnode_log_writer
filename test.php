<?php
// Quick example to test a local Node_log server using the defaults.
// Rob Morgan - 12-02-2010

require_once 'Zend/Log.php';
require_once 'library/ZendX/Log/Writer/Nodelog.php';

$writer = new ZendX_Log_Writer_Nodelog();
$logger = new Zend_Log($writer);

$logger->info('Informational message');
$logger->debug('Debug message');