<?php


namespace Application\Banner;

use Zend\Form\Form;
use Zend\Form\Element;


class BannerForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	parent::setAttribute('method', 'post');
	parent::setAttribute('action ', 'usuario');
	   
	//Creacion de campos
    //create field
    $file = new Element\File('image-file');
    $file->setLabel('Seleccione un archivo')->setAttribute('id', 'image-file');
    $this->add($file);

     $this->add(array(
        'type' => 'Zend\Form\Element\Select',
        'name' => 'imagen',
        'required'=>'required',
        'options' => array(
            'label' => '¿Qué imagen desea reemplazar?',
            'value_options' => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5'
            ),
        ),
        'attributes' => array(
            'value' => '1' //set selected to '1'
        )
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