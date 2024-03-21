<?php


namespace Application\Crearnoticias;

use Zend\Form\Form;
use Zend\Form\Element;


class CrearnoticiasForm extends Form 
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
				'placeholder'=>'Ingrese el titulo de la noticia',
				'required'=>'required',
            ),
            'options' => array(
				'label' => 'Título :',
            ),
        ));

        $this->add(array(
            'name' => 'url',
            'attributes' => array(
				'type'  => 'text',
				'placeholder'=>'Ingrese la url',
            ),
            'options' => array(
				'label' => 'Url :',
            ),
        ));
		
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'estado',
			'required'=>'required',
            'options' => array(
                'label' => 'Estado :',
                'value_options' => array(
                    '0' => 'Seleccione estado',
                    'A' => 'Activo',
                    'I' => 'Inactivo'
                ),
            ),
            'attributes' => array(
            'value' => '0' //set selected to '1'
            )
        ));
		
        $this->add(array(
            'name' => 'noticia',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese la Noticia:',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Noticia :',
            ),
        ));
		
        $this->add(array(
            'type' => 'Date',
            'name' => 'fecha_inicio',
            'options' => array(
				'label' => 'Fecha Inicio :'
            )
        ));
		
        $this->add(array(
            'type' => 'Date',
            'name' => 'fecha_fin',
            'options' => array(
				'label' => 'Fecha Fin :'
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

