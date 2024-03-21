<?php


namespace Application\Gestionsubmodulos;

use Zend\Form\Form;
use Zend\Form\Element;


class GestionsubmodulosForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
       $this->add(array(
           'name' => 'nombre_submodulo',
           'attributes' => array(
				'type'  => 'text',
				'required' => 'required',
				'placeholder'  => 'Ingrese el nombre del nuevo submódulo.'
            ),
            'options' => array(
				'label' => 'Agregar nuevo submódulo:',
            ),
       ));
       
       $this->add(array(
		    'name' => 'id_modulo',
		    'type'  => 'Zend\Form\Element\Select',
		    'attributes' => array(
				'required' => 'required'
		    ),
		    'options' => array(
		        'label' => 'Módulo:'
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

