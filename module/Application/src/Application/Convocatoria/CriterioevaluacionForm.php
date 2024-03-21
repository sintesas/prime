<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class CriterioevaluacionForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   $this->add(array(
            'name' => 'criterio',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese el criterio de evaluación',
				'maxlength' => 5000,
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Criterio de evaluación:',
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

