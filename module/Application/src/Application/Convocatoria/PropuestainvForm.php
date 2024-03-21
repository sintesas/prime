<?php


namespace Application\Convocatoria;

use Zend\Form\Form;
use Zend\Form\Element;

class PropuestainvForm extends Form 
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
            'name' => 'nombre',
            'attributes' => array(
                'required'=>'required',
                'type'  => 'text',
                'style' => 'max-width: 100%; width: 100%;',
                'maxlength' => 2500
            ),
            'options' => array(
                'label' => 'Nombre del archivo:',
            ),
        ));

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'required'=>'required',
                'type'  => 'textarea',
                'style' => 'max-width: 100%; width: 100%;',
                'maxlength' => 2500
            ),
            'options' => array(
                'label' => 'Descripción:',
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
	   


    }
}

