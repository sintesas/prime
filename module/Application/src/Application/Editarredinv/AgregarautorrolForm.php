<?php


namespace Application\Editarredinv;

use Zend\Form\Form;
use Zend\Form\Element;

class AgregarautorrolForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   	
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		    'type'=>'submit',
		    'required'=>'required',
			'class'=>'btn',
			'value'=>'Agregar',
		  ),
	   ));
	   


    }
}

