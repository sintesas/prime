<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class RolesconvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field


			
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
'class'=>'btn',
			 'value'=>'Guardar',
		  ),
	   ));
	   


    }
}

