<?php


namespace Application\Gestionformularios;

use Zend\Form\Form;
use Zend\Form\Element;


class GestionformulariosForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
       $this->add(array(
           'name' => 'nombre_formulario',
           'attributes' => array(
				'type'  => 'text',
				'required' => 'required',
				'placeholder'  => 'Ingrese el nombre del nuevo formulario.'
            ),
            'options' => array(
				'label' => 'Agregar nuevo formulario:',
            ),
       ));
       
       $this->add(array(
		    'name' => 'id_submodulo',
		    'type'  => 'Zend\Form\Element\Select',
		    'attributes' => array(
				'required' => 'required'
		    ),
		    'options' => array(
		        'label' => 'Submódulo:'
		    ),
	   ));

	   $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'textarea',
                'placeholder' => 'Ingrese la descripción del formulario.'
            ),
            'options' => array(
                'label' => 'Descripción:'
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

