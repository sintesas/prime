<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class CamposaddForm extends Form 
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
		'placeholder'  => 'Ingrese el título del campo ',
            ),
            'options' => array(
                'label' => 'Título:',
            ),
        ));

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la descripción',
                'maxlength' => 500
            ),
            'options' => array(
                'label' => 'Descripción:',
            ),
        ));

        $this->add(array(
            'name' => 'objetivo',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la observación',
				'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Observación:',
            ),
        ));
		

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'obligatorio',
			'required'=>'required',
            'options' => array(
                'label' => 'Obligatorio',
                'value_options' => array(
                    '' => 'Seleccione',
                    'S' => 'Si',
                    'N' => 'No'
                ),
            ),
            'attributes' => array(
                'value' => '1' //set selected to '1'
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

