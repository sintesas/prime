<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class EditarevaluarForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields


        $this->add(array(
            'name' => 'observaciones',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la observación',
				'lenght' => 5000,
            ),
            'options' => array(
                'label' => 'Observaciones :',
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

