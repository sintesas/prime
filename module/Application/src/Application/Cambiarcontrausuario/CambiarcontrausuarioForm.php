<?php


namespace Application\Cambiarcontrausuario;

use Zend\Form\Form;
use Zend\Form\Element;


class CambiarcontrausuarioForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
		 

        $this->add(array(
            'name' => 'ContrasenaNueva',
            'attributes' => array(
            'type'  => 'text',
'size'=>30,
		    'required'=>'required',
		    'placeholder'=>"Ingrese su nueva contraseña",
            ),
            'options' => array(
                'label' => 'Nueva contraseña:',
            ),
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
'class'=>'btn',
		     'required'=>'required',
			 'value'=>'Cambiar contraseña',
		  ),
	   ));
	   


    }
}

