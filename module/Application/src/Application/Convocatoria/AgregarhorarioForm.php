<?php

namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class AgregarhorarioForm extends Form {
	function __construct() {
		parent::__construct ( $name = null );
		
		parent::setAttribute ( 'method', 'post' );
		parent::setAttribute ( 'action ', 'usuario' );
		
		// create fields
		$this->add ( array (
			'name' => 'dia',
			'type' => 'Zend\Form\Element\Select',
			'attributes' => array (
				'required' => 'required' 
			),
			'options' => array (
				'label' => 'Seleccione el día:',
				'value_options' => array(
	                'Lunes' => 'Lunes',
	                'Martes' => 'Martes',
	                'Miércoles' => 'Miércoles',
	                'Jueves' => 'Jueves',
	                'Viernes' => 'Viernes',
	                'Sábado' => 'Sábado',
	                'Domingo' => 'Domingo'
	            )
			) 
		));

		$this->add ( array (
			'name' => 'hora_inicio',
			'attributes' => array (
				'type' => 'time',
				'placeholder' => 'Hora de inicio',
				'required' => 'required' 
			),
			'options' => array (
					'label' => 'Hora de inicio:' 
			) 
		) );

		$this->add ( array (
			'name' => 'hora_fin',
			'attributes' => array (
				'type' => 'time',
				'placeholder' => 'Hora fin',
				'required' => 'required' 
			),
			'options' => array (
					'label' => 'Hora fin:' 
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

