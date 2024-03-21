<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class RequisitosdocForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la descripción',
				'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Descripción :',
            ),
        ));

        $this->add(array(
            'name' => 'observaciones',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese el objetivo',
				'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Objetivo :',
            ),
        ));

			$this->add(array(
            			'name' => 'hora_limite',
		            	'attributes' => array(
                		'type'  => 'time',    
                        'required' => 'required'
            			),
            			'options' => array(
                			'label' => 'Hora Límite:',
            			),
			));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'responsable',
			'required'=>'required',
            'options' => array(
                'label' => 'Responsable :',
                'value_options' => array(
                    '' => 'Seleccione',
                    'E' => 'Aplicante',
                    'A' => 'Administrador'
                ),
            ),
            'attributes' => array(
                'value' => '1' //set selected to '1'
            )
        ));

        $this->add(array(
 'name' => 'fecha_limite',
 'attributes' => array(
            'type' => 'Date',
		    'placeholder'=>'YYYY-MM-DD',
            'required' => 'required'
           ),
            'options' => array(
            'label' => 'Fecha Límite:'
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

