<?php


namespace Application\Grupoinv;

use Zend\Form\Form;
use Zend\Form\Element;


class GrupoinvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'nombre_grupo',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'  => 'Ingrese el nombre del grupo',
            ),
            'options' => array(
				'label' => 'Filtro Nombre Grupo :',
            ),
        ));
		
        $this->add(array(
            'name' => 'evento',
            'attributes' => array(
				'type'  => 'text',
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

