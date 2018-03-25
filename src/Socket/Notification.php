<?php

namespace App\Socket;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Notification implements MessageComponentInterface
{
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $conn->send("From server message!");

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
//    protected $connections = array();
//
//    protected $container;
//
//    public function __construct(ContainerInterface $container)
//    {
//        $this->container = $container;
//    }
//
//    /**
//     * A new websocket connection
//     *
//     * @param ConnectionInterface $conn
//     */
//    public function onOpen(ConnectionInterface $conn)
//    {
//        $this->connections[] = $conn;
//        $conn->send('..:: Hello from the Notification Center ::..');
//        echo "New connection \n";
//    }
//
//    /**
//     * Handle message sending
//     *
//     * @param ConnectionInterface $from
//     * @param string $msg
//     */
//    public function onMessage(ConnectionInterface $from, $msg)
//    {
//        $messageData = json_decode(trim($msg));
//        if(isset($messageData->userData)){
//            //1st app message with connected user
//            $token_user = $messageData->userData;
//
//            //a user auth, else, app sending message auth
//            echo "Check user credentials\n";
//            //get credentials
//            $jwt_manager = $this->container->get('lexik_jwt_authentication.jwt_manager');
//            $token = new JWTUserToken();
//            $token->setRawToken($token_user);
//            $payload = $jwt_manager->decode($token);
//
//            //getUser by email
//            if(!$user = $this->getUserByEmail($payload['username'])){
//                $from->close();
//            }
//            echo "User found : ".$user->getFirstname() . "\n";
//            $index_connection = $payload['username'];
//
//            $all_connections = $this->connections;
//            foreach($all_connections as $key => $conn){
//                if($conn === $from){
//                    $this->connections[$index_connection] = $from;
//                    $from->send('..:: Connected as '.$index_connection.'  ::..');
//                    unset($this->connections[$key]);
//                    break;
//                } else {
//                    continue;
//                }
//
//            }
//        } else {
//            //error
//            $from->send("You're not able to do that!");
//        }
//
//    }
//
//    /**
//     * A connection is closed
//     * @param ConnectionInterface $conn
//     */
//    public function onClose(ConnectionInterface $conn)
//    {
//        foreach($this->connections as $key => $conn_element){
//            if($conn === $conn_element){
//                unset($this->connections[$key]);
//                break;
//            }
//        }
//    }
//
//    /**
//     * Error handling
//     *
//     * @param ConnectionInterface $conn
//     * @param \Exception $e
//     */
//    public function onError(ConnectionInterface $conn, \Exception $e)
//    {
//        $conn->send("Error : " . $e->getMessage());
//        $conn->close();
//    }
//
//
//    /**
//     * Get user from email credential
//     *
//     * @param $email
//     * @return false|User
//     */
//    protected function getUserByEmail($email)
//    {
//        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
//            return false;
//        }
//
//        $em = $this->container->get('doctrine')->getManager();
//        $repo = $em->getRepository(User::class);
//
//        $user = $repo->findOneBy(array('email' => $email));
//
//        if($user && $user instanceof User){
//            return $user;
//        } else {
//            return false;
//        }
//
//    }
}