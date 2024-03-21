<?php


namespace Application\Foro;

use Zend\Form\Form;
use Zend\Form\Element;


class ForoForm extends Form 
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
				'placeholder'=>'Ingrese el título del foro para filtrar',
            ),
            'options' => array(
				'label' => 'Filtro Título :',
            ),
        ));
		
        $this->add(array(
            'name' => 'mensaje',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'=>'Ingrese el titulo del mensaje del foro',
            ),
            'options' => array(
				'label' => 'Filtro Mensaje :',
            ),
        ));
				
	    $this->add(array(
			'name'=>'submit',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Buscar',
		  ),
	   ));
	   


    }
}

