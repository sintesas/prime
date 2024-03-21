<?php


namespace Application\Redesinv;

use Zend\Form\Form;
use Zend\Form\Element;


class RedesinvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'  => 'Ingrese el nombre de la red',
            ),
            'options' => array(
				'label' => 'Filtro nombre de la red:',
            ),
        ));
		
        $this->add(array(
            'name' => 'codigo',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'  => 'Ingrese el código de la red',
            ),
            'options' => array(
				'label' => 'Filtro código de la red:',
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

