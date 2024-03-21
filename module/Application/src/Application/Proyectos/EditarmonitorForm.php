<?php


namespace Application\Proyectos;

use Zend\Form\Form;
use Zend\Form\Element;


class EditarmonitorForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields


	    $this->add(array(
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Actualizar',
		  ),
	   ));
 

    }
}

