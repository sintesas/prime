<?php


namespace Application\Editarsemilleroinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ReconocimientossemilleroForm extends Form 
{
    function __construct()
    {
       parent::__construct( $name = null);
	   
	   parent::setAttribute('method', 'post');
	   parent::setAttribute('action ', 'usuario');
	   
       //create field	
	   $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
                'required'  => 'required'
            ),
            'options' => array(
                'label' => 'Nombre del reconocimiento:',
            ),
       ));

       $this->add(array(
            'name' => 'numero_acto',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Número del acto administrativo:',
            ),
       ));

       $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'textarea',
                'maxlength' => 7000
            ),
            'options' => array(
                'label' => 'Descripción:',
            ),
       ));

	   $this->add(array(
            'name' => 'fecha',
            'attributes' => array(
                'type'  => 'date',
                'required'  => 'required'
            ),
            'options' => array(
                'label' => 'Fecha:',
            ),
       ));

       $this->add(array(
            'name' => 'valor',
            'attributes' => array(
                'type'  => 'number'
            ),
            'options' => array(
                'label' => 'Valor económico reconocido:',
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

