<?php


namespace Application\Autenticar;

use Zend\Form\Form;
use Zend\Form\Element;



class AutenticarForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'autenticar');
	   
	   //create fields
	   
	   $this->add(array(
	      'name'=>'usuario',
		  'attributes'=>array(
		     'type'=>'text',
		     'required'=>'required',
		     'placeholder'=>'ingrese su usuario'
		  ),
		  'options'=>array(
		    'label'=>'Usuario:',
		  ),
	   ));
	   
	   $this->add(array(
	      'name'=>'contrasena',
		  'attributes'=>array(
		     'type'=>'password',
		     'required'=>'required',
		     'placeholder'=>'ingrese su clave'
		  ),
		  'options'=>array(
		    'label'=>'Clave:',
		  ),
	   ));
	   
	   
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'value'=>'Enviar',
		  ),
	   ));
	   


    }
}

