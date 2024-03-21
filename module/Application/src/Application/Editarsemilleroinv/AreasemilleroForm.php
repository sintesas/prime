<?php


namespace Application\Editarsemilleroinv;

use Zend\Form\Form;
use Zend\Form\Element;

class AreasemilleroForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
       //create field	
	   $this->add(array(
            'name' => 'tematica',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 7000
            ),
            'options' => array(
                'label' => 'Temática - Líneas de investigación:',
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

