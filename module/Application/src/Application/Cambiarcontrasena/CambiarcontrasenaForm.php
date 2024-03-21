<?php


namespace Application\Cambiarcontrasena;

use Zend\Form\Form;
use Zend\Form\Element;


class CambiarcontrasenaForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'ContrasenaActual',
            'attributes' => array(
            'type'  => 'password',
		    'required'=>'required',
		    'placeholder'=>'Ingrese su clave actual',
            ),
            'options' => array(
                'label' => 'Contraseña Actual:',
            ),
        ));
         
		 

        $this->add(array(
            'name' => 'ContrasenaNueva',
            'attributes' => array(
            'type'  => 'password',
		    'required'=>'required',
		    'placeholder'=>'Ingrese su nueva contraseña',
            ),
            'options' => array(
                'label' => 'Nueva Contraseña:',
            ),
        ));

        $this->add(array(
            'name' => 'ContrasenaNuevaR',
            'attributes' => array(
            'type'  => 'password',
		    'required'=>'required',
		    'placeholder'=>'Confirme su nueva contraseña',
            ),
            'options' => array(
                'label' => 'Repetir Nueva Contraseña:',
            ),
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
'class'=>'btn',
		     'required'=>'required',
			 'value'=>'Cambiar Contrasena',
		  ),
	   ));
	   


    }
}

