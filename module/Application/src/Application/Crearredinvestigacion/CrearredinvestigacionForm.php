<?php


namespace Application\Crearredinvestigacion;

use Zend\Form\Form;
use Zend\Form\Element;


class CrearredinvestigacionForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	parent::setAttribute('method', 'post');
	parent::setAttribute('action ', 'usuario');
	   
	//Creacion de campos
	
        	$this->add(array(
            		'name' => 'nombre_red',
            		'attributes' => array(
			'type'  => 'text',
			'placeholder'=>'Ingrese el nombre de la red de investigación',
			'required'=>'required',
            		),
            		'options' => array(
		'label' => 'Nombre de la red:',
            		),
        	));

        	$this->add(array(
            		'name' => 'codigo_red',
            		'attributes' => array(
			'type'  => 'text',
			'placeholder'=>'Ingrese el código de la red de investigación',
			'required'=>'required',
            		),
            		'options' => array(
		'label' => 'Código de la red:',
            		),
        	));

                $this->add(array(
        'name' => 'id_coordinador_1',
        'attributes' => array(
            'type'  => 'text',
            'onkeyup' => 'showHint1(this.value)',
            'placeholder'=>'Filtro'
        ),
        'options' => array(
            'label' => 'Coordinador 1 UPN:'
        ),
    ));

    $this->add(array(
        'name' => 'id_coordinador_2',
        'attributes' => array(
            'type'  => 'text',
            'onkeyup' => 'showHint2(this.value)',
            'placeholder'=>'Filtro'
        ),
        'options' => array(
            'label' => 'Coordinador 2 UPN:'
        ),
    ));

        	$this->add(array(
            		'type' => 'Zend\Form\Element\Select',
            		'name' => 'estado_red',
		            'required'=>'required',
            		'options' => array(
                			'label' => 'Estado :',
                			'value_options' => array(
                   			'' => 'Seleccione estado',
                    			'A' => 'Activo',
                    			'I' => 'Inactivo'
                			),
            		),
            		'attributes' => array(
            			'value' => '' //set selected to '1'
            		)
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