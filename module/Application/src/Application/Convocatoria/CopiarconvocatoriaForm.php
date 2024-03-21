<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class CopiarconvocatoriaForm extends Form 
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
		 'placeholder'  => 'Ingrese el título para copiar ',

            ),
            'options' => array(
                'label' => 'Título:',
            ),
        ));



	    $this->add(array(
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Duplicar',
		  ),
	   ));
 

    }
}

