<?php


namespace Application\Semillerosinv;

use Zend\Form\Form;
use Zend\Form\Element;


class SemillerosinvForm extends Form 
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
				'placeholder'  => 'Ingrese el nombre del Semillero',
            ),
            'options' => array(
				'label' => 'Filtro nombre del Semillero / Otros procesos de formación:',
            ),
        ));

        $this->add(array(
            'name' => 'codigo',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'  => 'Ingrese el código del semillero',
            ),
            'options' => array(
				'label' => 'Filtro código del Semillero / Otros procesos de formación:',
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

