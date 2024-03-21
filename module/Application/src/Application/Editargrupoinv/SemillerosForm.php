<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class SemillerosForm extends Form 
{
    function __construct()
    {
		parent::__construct( $name = null);

		parent::setAttribute('method', 'post');
		parent::setAttribute('action ', 'usuario');

		$this->add(array(
	  		'name' => 'id_semillero',
			'type' => 'Zend\Form\Element\Select',
			'attributes' => array(
				'id' => 'id_semillero',
			),
			'required'=>'required',
			'size' => 25,
			'options' => array(
				'label' => 'Semillero/Otros Procesos de Formación:',
				'empty_option' => 'Seleccione el Semillero'
			),
		));
				
		$this->add(array(
		  'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'class'=>'btn',
			 'value'=>'Agregar',
		  ),
		));

		 //Creacion de campos
	     //create field
	     $file = new Element\File('image-file');
	     $file->setLabel('Seleccione un archivo')->setAttribute('id', 'image-file');
	     $this->add($file);
    }
}

