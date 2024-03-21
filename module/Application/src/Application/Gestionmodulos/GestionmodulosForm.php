<?php


namespace Application\Gestionmodulos;

use Zend\Form\Form;
use Zend\Form\Element;


class GestionmodulosForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'nombre_modulo',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'  => 'Ingrese el nombre del nuevo módulo.',
				'required' => 'required'
            ),
            'options' => array(
				'label' => 'Agregar nuevo módulo:',
            ),
        ));
		
	    $this->add(array(
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Agregar',
		  ),
	   ));
    }
}

