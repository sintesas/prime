<?php

namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class AplicarmForm extends Form {
	function __construct() {
		parent::__construct ( $name = null );
		
		parent::setAttribute ( 'method', 'post' );
		parent::setAttribute ( 'action ', 'usuario' );

		$this->add(array(
			'name' => 'fecha',
			'type' => 'date',
			'options' => array (
				'label' => 'Fecha:', 
			) 
		));

		$this->add ( array (
			'name' => 'semestre',
			'type' => 'Zend\Form\Element\Select',
			'options' => array (
				'label' => 'Semestre que cursa:',
				'value_options' => array(
                    '1' => 'Primero',
                    '2' => 'Segundo',
                    '3' => 'Tercero',
                    '4' => 'Cuarto',
                    '5' => 'Quinto',
                    '6' => 'Sexto',
                    '7' => 'Septimo',
                    '8' => 'Octavo',
                    '9' => 'Noveno',
                    '10' => 'Decimo'
                )
			) 
		));
			
		
		$this->add ( array (
				'name' => 'submit',
				'attributes' => array (
						'type' => 'submit',
						'class' => 'btn',
						'value' => 'Continuar' 
				) 
		) );
	}
}

