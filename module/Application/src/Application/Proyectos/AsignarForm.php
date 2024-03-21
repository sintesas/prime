<?php


namespace Application\Proyectos;

use Zend\Form\Form;
use Zend\Form\Element;

class AsignarForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
		$this->add(array(
            'name' => 'codigo',
            'attributes' => array(
                'type'  => 'text',
		'Placeholder' => 'Ingrese el código del proyecto',
            ),
            'options' => array(
                'label' => 'Código del Proyecto:',
            ),
        ));

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

