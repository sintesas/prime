<?php


namespace Application\Consultamonitores;

use Zend\Form\Form;
use Zend\Form\Element;


class ConsultamonitoresForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'id_convocatoria',
            'attributes' => array(
				'type'  => 'text'
            ),
            'options' => array(
				'label' => 'Filtro ID convocatoria:',
            ),
        ));

        $this->add(array(
            'name' => 'codigo_proyecto',
            'attributes' => array(
				'type'  => 'text'
            ),
            'options' => array(
				'label' => 'Filtro código del proyecto:',
            ),
        ));

        $this->add(array(
            'name' => 'nombre_proyecto',
            'attributes' => array(
				'type'  => 'text'
            ),
            'options' => array(
				'label' => 'Filtro nombre del proyecto:',
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

