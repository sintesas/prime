<?php


namespace Application\Editarredinv;

use Zend\Form\Form;
use Zend\Form\Element;

class IntegrantesredForm extends Form 
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

	   //create field	
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

