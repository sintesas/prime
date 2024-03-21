<?php


namespace Application\Eventos;

use Zend\Form\Form;
use Zend\Form\Element;


class EventosForm extends Form 
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
				'placeholder'=>'Ingrese el título para filtrar',
            ),
            'options' => array(
				'label' => 'Filtro Título :',
            ),
        ));
		
        $this->add(array(
            'name' => 'evento',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'=>'Ingrese el evento para filtrar',
            ),
            'options' => array(
				'label' => 'Filtro Evento :',
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

