<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class AreashvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'nombre_area',
            'options' => array(
                'label' => 'Nombre área:',
            ),
            'attributes' => array(
                'value' => '',
                'id' => 'nombre_area'
            )
        )); // set selected to '1'
	   
        $this->add(array(
            'name' => 'objeto',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el objeto de actuación',
                'maxlength' => 4000,
            ),
            'options' => array(
                'label' => 'Objeto del área de actuación :',
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