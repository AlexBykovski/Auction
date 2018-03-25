<?php

namespace App\Socket;

use Ratchet\WebSocket\WsServer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SocketHelper
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * LoginHelper constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getWS()
    {
        return new WsServer(new Notification($this->container));
    }

}