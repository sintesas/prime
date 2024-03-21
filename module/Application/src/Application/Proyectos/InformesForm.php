<?php


namespace Application\Proyectos;

use Zend\Form\Form;
use Zend\Form\Element;


class InformesForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'informe',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre del informe y/o Producto',
            ),
            'options' => array(
                'label' => 'Nombre del Informe y/o Producto:',
            ),
        ));

        $this->add(array(
 'name' => 'fecha_limite',
 'attributes' => array(
            'type' => 'Date',
'required'=>'required',
		    'placeholder'=>'YYYY-MM-DD',
           ),
            'options' => array(
            'label' => 'Fecha límite:'
            )
        ));

        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);

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

