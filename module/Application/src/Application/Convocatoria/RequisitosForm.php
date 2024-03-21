<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class RequisitosForm extends Form 
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
                'label' => 'Descripción:',
            ),
        ));

        $this->add(array(
            'name' => 'observaciones',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese las observaciones',
				'maxlength' => 5000,
            ),
            'options' => array(
                'label' => 'Observaciones:',
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

