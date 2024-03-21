<?php


namespace Application\Creareventos;

use Zend\Form\Form;
use Zend\Form\Element;


class CreareventosForm extends Form 
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
				'placeholder'=>'Ingrese el título del evento',
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
				'placeholder'=>'Ingrese la url'
            ),
            'options' => array(
				'label' => 'Url :',
            ),
        ));
	
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'estado',
			'required'=>'required',
            'options' => array(
                'label' => 'Estado :',
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
            'name' => 'evento',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese el Evento:',
				'lenght' => 10000,
            ),
            'options' => array(
                'label' => 'Evento :',
            ),
        ));
		
        $this->add(array(
            'type' => 'Date',
            'name' => 'fecha_inicio',
            'options' => array(
				'label' => 'Fecha Inicio :'
            )
        ));
	
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);

	
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

