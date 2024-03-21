<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class AspectoevalForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'ponderacion1',
            'attributes' => array(
                'type'  => 'Number',
				'placeholder'=>'Ingrese el valor de la ponderación',
            ),
            'options' => array(
                'label' => 'Valor Ponderación:',
            ),
        ));

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la descripción',
				'lenght' => 5000,
            ),
            'options' => array(
                'label' => 'Descripción :',
            ),
        ));

        $this->add(array(
            'name' => 'objetivo',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese el objetivo',
				'lenght' => 5000,
            ),
            'options' => array(
                'label' => 'Objetivo :',
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

