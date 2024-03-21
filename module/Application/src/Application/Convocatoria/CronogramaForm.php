<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class CronogramaForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'nombre_actividad',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre de la actividad',
            ),
            'options' => array(
                'label' => 'Nombre de la Actividad:',
            ),
        ));

        $this->add(array(
            'name' => 'prioridad',
            'attributes' => array(
                'type'  => 'Number',
				'placeholder'=>'Ingrese la prioridad',
            ),
            'options' => array(
                'label' => 'Consecutivo de la actividad:',
            ),
        ));

        $this->add(array(
            'name' => 'fecha_inicio',
            'attributes' => array(
                'type' => 'Date',
                'required'=>'required',
    		    'placeholder'=>'YYYY-MM-DD',
            ),
            'options' => array(
                'label' => 'Fecha Inicio:'
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
            'name' => 'objetivo',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese el objetivo',
				'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Objetivo de la actividad:',
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
                'label' => 'Descripción de la actividad:',
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

