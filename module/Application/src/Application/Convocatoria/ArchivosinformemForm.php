<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class ArchivosinformemForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
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

