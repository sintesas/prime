<?php


namespace Application\Cambiarusuario;

use Zend\Form\Form;
use Zend\Form\Element;


class CambiarusuarioForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
		 

        $this->add(array(
            'name' => 'usuario',
            'attributes' => array(
            'type'  => 'text',
'size'=>30,
		    'required'=>'required',
		    'placeholder'=>"Ingrese su nuevo usuario",
            ),
            'options' => array(
            	'required'=>'required',
                'label' => 'Nuevo Usuario:',
            ),
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
'class'=>'btn',
		     'required'=>'required',
			 'value'=>'Cambiar usuario',
		  ),
	   ));
	   


    }
}

