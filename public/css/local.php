<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */
return array(
	'static_salt' => 'aFGQ475SDsdfsaf2342', // was moved from module.config.php here to allow all modules to use it

    'db' => array(
        'adapters' => array(
            'db1' => array(
              //'username'=>'primeuser',
              //'password'=>'m4BxSeWloBa',
                'username'=>'pgil',
                'password'=>'z3oZvFC3kDzB',
            ),
            'db2' => array(
        'username' => 'INTERFAZ_INVESTIG',
        'password' => 'INV3ST1G4C10N',
            ),
            'db3' => array(
        'username' => 'userciup',
        'password' => 'ciup_user',
            ),
            'db4' => array(
        'username' => 'PR_PROYECTOS_INVESTIGACION',
        'password' => 'Inv35t164Pr15',
            ),
        ),
    ),

);
