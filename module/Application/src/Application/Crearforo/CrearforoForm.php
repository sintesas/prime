<?php
namespace Application\Crearforo;

use Zend\Form\Form;
use Zend\Form\Element;


class CrearforoForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
	   //create fields
        $this->add(array(
            'name' => 'titulo',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'=>'Ingrese el título del foro',
				'required'=>'required'
            ),
            'options' => array(
				'label' => 'Título:',
            ),
        ));
	
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'estado',
			'required'=>'required',
            'options' => array(
                'label' => 'Estado:',
                'value_options' => array(
                    '' => 'Seleccione estado',
                    'A' => 'Activo',
                    'I' => 'Inactivo'
                ),
            ),
            'attributes' => array(
            'value' => '' //set selected to '1'
            )
        ));
		
        $this->add(array(
            'name' => 'mensaje',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese el mensaje.',
				'maxlength' => 10000,
            ),
            'options' => array(
                'label' => 'Mensaje:',
            ),
        ));
		
        $this->add(array(
            'type' => 'Date',
            'name' => 'fecha',
            'options' => array(
				'label' => 'Fecha:'
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
	   
	    $this->add(array(
			'name'=>'submit2',
			'attributes'=>array(
				'type'=>'submit',
				'class'=>'btn',
				'value'=>'Responder',
		  ),
	   ));

    }
}