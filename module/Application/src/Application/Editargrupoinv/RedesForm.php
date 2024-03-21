<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class RedesForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
			
		$this->add(array(
	  		'name' => 'nombre_red',
			'type' => 'Zend\Form\Element\Select',
			'attributes' => array(
				'id' => 'nombre_red',
			),
			'required'=>'required',
			'size' => 25,
			'options' => array(
				'label' => 'Nombre institución:',
				'empty_option' => 'Seleccione la institucion'
			),
		));

		//Creacion de campos
     //create field
     $file = new Element\File('image-file');
     $file->setLabel('Seleccione un archivo')->setAttribute('id', 'image-file');
     $this->add($file);

	   $this->add(array(
	    'name'=>'submit',
		'attributes'=>array(
			'type'=>'submit',
		    'required'=>'required',
			'class'=>'btn',
			'value'=>'Agregar',
		  ),
	   ));
    }
}

