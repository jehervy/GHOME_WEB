<?php

namespace GHOME\CoreBundle\Socket;

use GHOME\CoreBundle\Entity\Action;

class SocketClient
{
    private $fd;
    private $host;
    private $port;
    
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }
    
    public function sendAction(Action $action)
    {
        $this->connect();
        
		$str = array();
		$str[] = '2'; //'2' is an order
		$str[] = strlen($action->getMetric());
		$str[] = $action->getMetric();
		$str[] = strlen($action->getRoom());
		$str[] = $action->getRoom();
		$str[] = strlen($action->getValue());
		$str[] = $action->getValue();
		
		fwrite($this->fd, implode($str));
    }
    
    private function connect()
    {
        if (isset($this->fd))
        {
            return;
        }
        
        $this->fd = @pfsockopen($this->host, $this->port, $errno, $errstr,30);
        
        if ($this->fd === false)
        {
            throw new \RuntimeException(sprintf('Could not open socket: %s.', $errstr));
        }
    }
}