<?php


namespace Application\Editarsemilleroinv;

use Zend\Form\Form;
use Zend\Form\Element;

class IdentificadoressemiForm extends Form 
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
            'name' => 'id_tipoidentificador',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_tipoidentificador',
                'required'=>'required',
            ),
            'options' => array(
                'label' => 'Tipo de identificador plataforma:'
            )
        ));

        $this->add(array(
            'name' => 'id_tipocategoria',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_tipocategoria',
                'required'=>'required',
            ),
            'options' => array(
                
                'label' => 'Tipo de categoría plataforma:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'id_field',
            'attributes' => array(
                'required'=>'required',
                'type'  => 'text',
                'maxlength' => 50,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'ID:',
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_registro',
            'attributes' => array(
                'required'=>'required',
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha registro:',
                'format' => 'Y-m-d'
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'required'=>'required',
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Nombre como se registró:',
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'web',
            'attributes' => array(
                'required'=>'required',
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Dirección web del identificador:',
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'ciudad',
            'attributes' => array(
                'required'=>'required',
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Ciudad donde se realizó el trabajo:',
            ),
        ));

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>'required'
            ),
            'options' => array(
                'label' => 'Descripción del identificador:',
            )
        ));

        $this->add(array(
            'name' => 'otra_informacion',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>'required'
            ),
            'options' => array(
                'label' => 'Otra información relevante:',
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

