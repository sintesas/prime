<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class ConvocatoriamForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'titulo',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el título de la convocatoria',
            ),
            'options' => array(
                'label' => 'Título Convocatoria :',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'cronograma',
			'required'=>'required',
            'options' => array(
                'label' => 'Cronograma:',
                'value_options' => array(
                    '' => 'Seleccione',
                    'S' => 'Si',
                    'N' => 'No',
                ),
            ),
            'attributes' => array(
                'value' => '1' //set selected to '1'
            )
        ));


        $this->add(array(
            'name' => 'numero_monitores',
            'attributes' => array(
                'type'  => 'Number',
				'placeholder'=>'Ingrese el numero de plazas',
            ),
            'options' => array(
                'label' => 'Cantidad de Plazas de Monitores:',
            ),
        ));

        $this->add(array(
 'name' => 'fecha_limite',
 'attributes' => array(
            'type' => 'Date',
'required'=>'required',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha Limite para subir el/los soporte(s) Requerido(s):'
            )
        ));

        $this->add(array(
 'name' => 'fecha_apertura',
 'attributes' => array(
            'type' => 'Date',
'required'=>'required',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha Apertura:'
            )
        ));

        $this->add(array(
 'name' => 'fecha_cierre',
 'attributes' => array(
            'type' => 'Date',
'required'=>'required',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha Fin:'
            )
        ));

        $this->add(array(
            'name' => 'observaciones',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese las observaciones',
				'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Observaciones :',
            ),
        ));


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
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Guardar',
		  ),
	   ));
 

    }
}
