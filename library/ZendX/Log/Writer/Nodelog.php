<?php
/**
 * ZendX - Unofficial Zend Framework Components
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with Zend Framework: http://framework.zend.com/license/new-bsd.
 *
 * @category   ZendX
 * @package    ZendX_Log
 * @subpackage Writer
 * @copyright  Copyright (c) 2010 Rob Morgan. (http://robmorgan.id.au)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Log_Exception */ 
require_once 'Zend/Log/Exception.php';

/** Zend_Log_Writer_Abstract */
require_once 'Zend/Log/Writer/Abstract.php';

/** Zend_Log_Formatter_Simple */
require_once 'Zend/Log/Formatter/Simple.php';

/**
 * @category   ZendX
 * @package    ZendX_Log
 * @subpackage Writer
 * @copyright  Copyright (c) 2010 Rob Morgan. (http://robmorgan.id.au)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZendX_Log_Writer_Nodelog extends Zend_Log_Writer_Abstract
{
    /**
     * Default timeout in seconds for initiating a session
     */
    const TIMEOUT_CONNECTION = 30;

    /**
     * Remote hostname or i.p.
     *
     * @var string
     */
    protected $_host;

    /**
     * Port number
     *
     * @var integer|null
     */
    protected $_port;
    
    /**
     * Socket connection resource
     * @var resource
     */
    protected $_socket;
    
    /**
     * Constructor.
     *
     * @param  string $host OPTIONAL (Default: 127.0.0.1)
     * @param  string $port OPTIONAL (Default: 8003)
     * @return void
     */
    public function __construct($host = '127.0.0.1', $port = '8003')
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_formatter = new Zend_Log_Formatter_Simple();
        $this->_connect();
    }
    
    /**
     * Connect to the server using the supplied transport and target.
     *
     * @param  string $remote Remote
     * @throws Zend_Log_Exception
     * @return boolean
     */
    protected function _connect()
    {
        $errorNum = 0;
        $errorStr = '';

        // open connection
        $remote = 'tcp://' . $this->_host . ':'. $this->_port;
        $this->_socket = @stream_socket_client($remote, $errorNum, $errorStr, self::TIMEOUT_CONNECTION);

        if ($this->_socket === false) {
            if ($errorNum == 0) {
                $errorStr = 'Could not open socket';
            }
            /**
             * @see Zend_Log_Exception
             */
            throw new Zend_Log_Exception($errorStr);
        }

        if (($result = stream_set_timeout($this->_socket, self::TIMEOUT_CONNECTION)) === false) {
            /**
             * @see Zend_Log_Exception
             */
            throw new Zend_Log_Exception('Could not set stream timeout');
        }

        return $result;
    }
    
    /**
     * Close the socket connection.
     *
     * @return void
     */
    public function shutdown()
    {
        if (is_resource($this->_socket)) {
            fclose($this->_socket);
        }
    }
    
    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        $line = $this->_formatter->format($event);

        if (false === @fwrite($this->_socket, $line)) {
            require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception('Unable to write to stream');
        }
    }
}