<?php
namespace Topxia\Common;

use Topxia\Service\Common\ConnectionFactory;
use Topxia\Service\Common\ServiceKernel;
use Doctrine\DBAL\DriverManager;

class AppConnectionFactory implements ConnectionFactory
{
    protected $container;
    protected $connection;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getConnection()
    {
        if(empty($this->connection)){


            $connection = $this->container->get('database_connection');

            if(empty($connection)) {
                $connection = DriverManager::getConnection(array(
                    'wrapperClass' => 'Topxia\\Service\\Common\\Connection',
                    'driver'       => ServiceKernel::instance()->getParameter('database_driver'),
                    'charset'      => 'utf8',
                    'host' => ServiceKernel::instance()->getParameter('database_host'),
                    'port' => ServiceKernel::instance()->getParameter('database_port'),
                    'dbname' => ServiceKernel::instance()->getParameter('database_name'),
                    'user' => ServiceKernel::instance()->getParameter('database_user'),
                    'password' => ServiceKernel::instance()->getParameter('database_password'),
                ));
            }

            // $connection = $this->createConnection();
            // $connection->setServerHost('127.0.0.1');
            // $connection->setServerPort('9501');
            
            $connection->exec('SET NAMES UTF8');
            $this->connection = $connection;
        }

        return $this->connection;
    }
}