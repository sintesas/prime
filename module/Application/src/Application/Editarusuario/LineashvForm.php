<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class LineashvForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   

	   
	   //create field	
        $this->add(array(
            'name' => 'nombre_linea',
            'attributes' => array(
                'type'  => 'text',
				'placeholder'=>'Ingrese el nombre de la línea',
                'maxlength' => 200,
            ),
            'options' => array(
                'label' => 'Nombre Línea :',
            ),
        ));

       	$this->add(array(
            		'type' => 'Zend\Form\Element\Select',
            		'name' => 'id_estado',
		'required'=>'required',
            		'options' => array(
                			'label' => 'Estado :',
                			'value_options' => array(
                    			'1' => 'Activa',
                    			'0' => 'Inactiva'
                			),
            		),
            		'attributes' => array(
            			'value' => '' //set selected to '1'
            		)
        	));

        $this->add(array(
            'name' => 'objetivo',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese el objetivo',
				'maxlength' => 4000,
            ),
            'options' => array(
                'label' => 'Objetivo :',
            ),
        ));

        $this->add(array(
            'name' => 'efectos',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese los efectos',
				'maxlength' => 4000,
            ),
            'options' => array(
                'label' => 'Efectos :',
            ),
        ));

        $this->add(array(
            'name' => 'logros',
            'attributes' => array(
                'type'  => 'textarea',
				'placeholder'=>'Ingrese los logros',
				'maxlength' => 4000,
            ),
            'options' => array(
                'label' => 'Logros :',
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

