<?php

namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class ProyectosinvForm extends Form {
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
				'name' => 'fecha_lim_soporte',
				'attributes' => array (
						'type' => 'Date',
						'required' => 'required',
						'placeholder' => 'YYYY-MM-DD',
						'required' => 'required' 
				),
				'options' => array (
						'label' => 'Fecha Límite para subir el/los soporte(s) Requerido(s):' 
				) 
		) );
		
		$this->add ( array (
				'name' => 'cantidad_plazas',
				'attributes' => array (
						'type' => 'Number',
						'min' => 0,
						'placeholder' => 'Ingrese la cantidad de plazas',
						'required' => 'required' 
				),
				'options' => array (
						'label' => 'Cantidad de Plazas Disponibles :' 
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

