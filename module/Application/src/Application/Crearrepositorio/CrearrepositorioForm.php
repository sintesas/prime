<?php


namespace Application\Crearrepositorio;

use Zend\Form\Form;
use Zend\Form\Element;


class CrearrepositorioForm extends Form 
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
	
    $this->add(array(
        'name' => 'nombre',
        'attributes' => array(
            'type'  => 'text',
            'placeholder'=>'Ingrese el nombre del documento',
            'required'=>'required'
        ),
        'options' => array(
            'label' => 'Nombre del documento:'
        ),
    ));

    $this->add(array(
        'name' => 'url',
        'attributes' => array(
            'type'  => 'text'
        ),
        'options' => array(
            'label' => 'URL:'
        ),
    ));

    $this->add(array(
        'name' => 'id_autor',
        'attributes' => array(
            'type'  => 'text',
            'onkeyup' => 'showHint1(this.value)',
            'placeholder'=>'Filtro',
        ),
        'options' => array(
            'label' => 'Autor del documento:'
        ),
    ));

    $this->add(array(
        'name' => 'descripcion',
        'attributes' => array(
            'type'  => 'textarea'
        ),
        'options' => array(
            'label' => 'Descripción:'
        ),
    ));

    $this->add(array(
        'name' => 'otra_informacion',
        'attributes' => array(
            'type'  => 'textarea'
        ),
        'options' => array(
            'label' => 'Otra información relevante:'
        ),
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