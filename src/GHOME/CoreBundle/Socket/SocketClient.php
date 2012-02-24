<?php

namespace GHOME\CoreBundle\Socket;

use GHOME\CoreBundle\Entity\Action;

/**
 * Basic class to commnicate with the server.
 */
class SocketClient
{
    private $fd;
    private $host;
    private $port;
    
    /**
     * Constructor.
     *
     * @param string $host An host to connect to
     * @param integer $port A port to connect to
     */
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }
    
    /**
     * Sends an action to execute to the server.
     *
     * @param Action $action The action to send
     */
    public function sendAction(Action $action)
    {
        $this->connect();
        
		$str = array();
		$str[] = '2'; //'2' represents an order
		$str[] = strlen($action->getMetric());
		$str[] = $action->getMetric();
		$str[] = strlen($action->getRoom());
		$str[] = $action->getRoom();
		$str[] = strlen($action->getValue());
		$str[] = $action->getValue();
		
		fwrite($this->fd, implode($str));
    }
    
    /**
     * Establishes a connection with the server through sockets.
     */
    private function connect()
    {
        if (isset($this->fd))
        {
            return;
        }
        
        $this->fd = @pfsockopen($this->host, $this->port, $errno, $errstr, 30);
        
        if ($this->fd === false)
        {
            throw new \RuntimeException(sprintf('Could not open socket: %s.', $errstr));
        }
    }
}