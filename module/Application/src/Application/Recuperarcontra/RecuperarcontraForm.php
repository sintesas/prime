<?php


namespace Application\Recuperarcontra;

use Zend\Form\Form;
use Zend\Form\Element;



class RecuperarcontraForm extends Form 
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
				'placeholder'=>'Ingrese su usuario',
            ),
            'options' => array(
                'label' => 'Usuario:',
            ),
        ));
         
        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type'  => 'number',
				'placeholder'=>'Ingrese su numero de documento',
            ),
            'options' => array(
                'label' => 'Documento de Identidad:',
            ),
        ));
		
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
			 'class'=> 'btn',
		     'required'=>'required',
			 'value'=>'Recuperar Contraseña',
		  ),
	   ));
	   


    }
}

