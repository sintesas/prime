<?php


namespace Application\Editarredinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ArchivosredForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create field
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);

        $this->add(array(
            'name' => 'id_tipo_archivo',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Tipo de archivo: '
            )
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
	   


    }
}

