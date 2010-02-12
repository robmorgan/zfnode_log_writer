ZendX_Log_Writer_Nodelog: Node_log Writer for Zend Framework
============================================================

A [Node_log](http://github.com/robmorgan/node_log) log writer for [Zend Framework](http://framework.zend.com/).

Usage
-----

Prequisites:

  1. [Node_log](http://github.com/robmorgan/node_log)
  2. [Zend Framework](http://framework.zend.com/)

Download the code

    git clone git://github.com/robmorgan/zfnode_log_writer.git

Include it in your script

    require_once 'library/ZendX/Log/Writer/Nodelog.php';
    
Log an event to the Node_log server!

    $writer = new ZendX_Log_Writer_Nodelog('localhost', '8003');
    $logger = new Zend_Log($writer);
    $logger->info('Informational message');

Author
------

Rob Morgan - [robmorgan.id.au](http://robmorgan.id.au/)