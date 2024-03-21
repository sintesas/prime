<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class ReporteinvpreForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
$this->add(array(
            'name' => 'id_convocatoria',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el ID de la convocatoria',
            ),
            'options' => array(
                'label' => 'ID convocatoria:',
            ),
        ));

        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre de la convocatoria',
            ),
            'options' => array(
                'label' => 'Nombre de la convocatoria	 :',
            ),
        ));

        $this->add(array(
            'name' => 'duracion',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese la duracion',
            ),
            'options' => array(
                'label' => 'Duracion del Proyecto	 :',
            ),
        ));

        $this->add(array(
            'name' => 'ano',
            'attributes' => array(
                'type'  => 'Number',
				'placeholder'=>'Ingrese el año de publicacion',
				'min'=>1980,
				'max'=>3000,
            ),
            'options' => array(
'required'=>'required',
                'label' => 'Año :',
            ),
        ));



	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
'class'=>'btn',
			 'value'=>'Reporte Excel',
		  ),
	   ));
	   


    }
}

