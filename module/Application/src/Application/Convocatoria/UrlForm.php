<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;


class UrlForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
	$this->add(array(
            'name' => 'nom_url',
            'attributes' => array(
                'type'  => 'text',
		      'placeholder'  => 'Ingrese el nombre de la url',
              'maxlenght' => 500,

            ),
            'options' => array(
                'label' => 'Nombre de la Url:',
            ),
        ));

	$this->add(array(
            'name' => 'url',
            'attributes' => array(
                'type'  => 'text',
		'placeholder'  => 'Ingrese la url',
            ),
            'options' => array(
                'label' => 'Url:',
            ),
        ));
		
        $this->add(array(
            'name' => 'descripcion',
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

