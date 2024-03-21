<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class AsignarevalForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields

	$this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type'  => 'text',
		'placeholder' => 'Ingrese el usuario para filtrar:',
            ),
            'options' => array(
                'label' => 'Filtro Usuario:',
            ),
        ));
		
		$this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
		'placeholder' => 'Ingrese el nombre del usuario para filtrar:',
            ),
            'options' => array(
                'label' => 'Filtro Nombre:',
            ),
        ));
		
		$this->add(array(
            'name' => 'apellido',
            'attributes' => array(
                'type'  => 'text',
		'placeholder' => 'Ingrese el apellido para filtrar',
            ),
            'options' => array(
                'label' => 'Filtro Apellido:',
            ),
        ));

        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type'  => 'Number',
		'placeholder' => 'Ingrese el documento para filtrar',
            ),
            'options' => array(
                'label' => 'Filtro Documento:',
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

