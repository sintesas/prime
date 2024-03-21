<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class GruposrelForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   

	   
	   //create field	
        $this->add(array(
            'name' => 'nombre_grupo',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'nombre grupo',
            ),
            'options' => array(
                'label' => 'Nombre grupo:',
            ),
        ));

			
	   $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
'class'=>'btn',
			 'value'=>'Filtrar',
		  ),
	   ));

	    //Creacion de campos
	    //create field
	    $file = new Element\File('image-file');
	    $file->setLabel('Seleccione un archivo')->setAttribute('id', 'image-file');
	    $this->add($file);

	    $this->add(array(
	      'name'=>'cargar',
		  'attributes'=>array(
		     'type'=>'submit',
		     'required'=>'required',
			 'class'=>'btn',
			 'value'=>'Cargar',
		  ),
	    ));
    }
}

