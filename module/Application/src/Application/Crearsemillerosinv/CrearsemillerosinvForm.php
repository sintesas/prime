<?php


namespace Application\Crearsemillerosinv;

use Zend\Form\Form;
use Zend\Form\Element;


class CrearsemillerosinvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	parent::setAttribute('method', 'post');
	parent::setAttribute('action ', 'usuario');
	   
	//Creacion de campos
	
    $this->add(array(
        'name' => 'nombre',
        'attributes' => array(
            'type'  => 'text',
            'placeholder'=>'Ingrese el nombre del semillero',
            'required'=>'required',
            'id' => 'nombre'
        ),
        'options' => array(
            'label' => 'Ingrese el nombre del Semillero / Otros procesos de formación:'
        ),
    ));

    $this->add(array(
        'name' => 'codigo',
        'attributes' => array(
            'type'  => 'text',
            'placeholder'=>'Ingrese el código del semillero',
            'required'=>'required',
            'id' => 'codigo'
        ),
        'options' => array(
            'label' => 'Ingrese el código:'
        ),
    ));

    $this->add(array(
        'name' => 'id_coordinador_1',
        'attributes' => array(
        	'id' => 'id_coordinador_1',
            'type'  => 'text',
            'placeholder'=>'Filtro',
        ),
        'options' => array(
            'label' => 'Coordinador 1:'
        ),
    ));

    $this->add(array(
        'name' => 'id_coordinador_2',
        'attributes' => array(
        	'id' => 'id_coordinador_2',
            'type'  => 'text',
            'placeholder'=>'Filtro',
        ),
        'options' => array(
            'label' => 'Coordinador 2:'
        ),
    ));
    
	$this->add(array(
		'type' => 'Zend\Form\Element\Select',
		'name' => 'estado',
        'required'=>'required',
		'options' => array(
    			'label' => 'Estado:',
    			'value_options' => array(
        		  'A' => 'Activo',
        		  'I' => 'Inactivo'
    			),
		),
		'attributes' => array(
			'value' => '' //set selected to '1'
		)
	));

    $today = date("Y-m-d");

    $this->add(array(
        'name' => 'fecha',
        'attributes' => array(
            'type'  => 'date',
            'max' => $today
        ),
        'options' => array(
            'label' => 'Ingrese la fecha de creación:'
        ),
    ));

	$this->add(array(
		'name'=>'submit',
		'attributes'=>array(
			'type'=>'submit',
			'class'=>'btn',
			'value'=>'Guardar',
		  ),
	));
    }
}