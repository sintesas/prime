<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */


return array(
  'service_manager'=>array(
     'abstract_factories'=>array(
	    'Zend\Db\Adapter\AdapterAbstractServiceFactory',
	    ),
     'factories'=>array(
	    'Zend\Db\Adapter'=>'Zend\Db\Adapter\AdapterServiceFactory',
	    ),
        ),
'db' => array(
            'adapters' => array(
            'db1' => array(
                    'driver'=>'Pdo_Pgsql',
            	    'dsn'=>'pgsql:host=172.16.21.130;port=5432;dbname=ciup_prime1',
            	    'driver_option'=>array(
            	     PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES \'utf8\''),),
                         
            'db2' => array(
            	    'driver' => 'OCI8',
                    'connection_string' => '(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.115.111.43)(PORT = 1521))) (CONNECT_DATA =(SID = query7) (SERVER = DEDICATED)))',
                    'character_set' => 'AL32UTF8', ),
            
            'db3' => array(
            	    'driver' => 'OCI8',
                    'connection_string' => '(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = uni4)(PORT = 1521))) (CONNECT_DATA =(SID = baseupn) (SERVER = DEDICATED)))',
                    'character_set' => 'AL32UTF8',),
            
            'db4' => array(
            	    'driver' => 'OCI8',
                    'connection_string' => '(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.115.111.43)(PORT = 1521))) (CONNECT_DATA =(SERVICE_NAME = baseupn) (SERVER = DEDICATED)))',
                    'character_set' => 'AL32UTF8',),
                    ),
                ),
);

