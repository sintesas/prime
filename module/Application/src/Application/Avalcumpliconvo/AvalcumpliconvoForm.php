<?php


namespace Application\Avalcumpliconvo;

use Zend\Form\Form;
use Zend\Form\Element;


class AvalcumpliconvoForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');

	   $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'estado',
            'options' => array(
                'label' => 'Estado:',
                'value_options' => array(
                    'Activo' => 'Activo',
                    'Inactivo' => 'Inactivo'
                )
            ),
            'attributes' => array(
                'value' => 'Activo',
                'id' => 'tipo_evaluador'
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

