<?php

namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class AgregarproyectosinvForm extends Form {
	function __construct() {
		parent::__construct ( $name = null );
		
		parent::setAttribute ( 'method', 'post' );
		parent::setAttribute ( 'action ', 'usuario' );
		
		// create fields
		$this->add ( array (
				'name' => 'nombre_proyecto',
				'attributes' => array (
						'type' => 'text',
						'placeholder' => 'Ingrese el nombre del Proyecto',
						'required' => 'required' 
				),
				'options' => array (
						'label' => 'Nombre del Proyecto de Investigación :' 
				) 
		) );
		
		$this->add ( array (
				'name' => 'submit',
				'attributes' => array (
						'type' => 'submit',
						'class' => 'btn',
						'value' => 'Guardar' 
				) 
		) );
	}
}

