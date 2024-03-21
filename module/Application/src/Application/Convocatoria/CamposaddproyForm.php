<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class CamposaddproyForm extends Form 
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
            ),
            'options' => array(
                'label' => 'Titulo:',
            ),
        ));

        $this->add(array(
            'name' => 'valor',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la descripción',
				'lenght' => 500,
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

