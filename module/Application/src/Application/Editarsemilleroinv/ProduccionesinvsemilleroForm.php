<?php


namespace Application\Editarsemilleroinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ProduccionesinvsemilleroForm extends Form 
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
	   
	   //create field
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'required'=>'required',
                'type'  => 'text',
                'maxlength' => 2500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Nombre producto:',
            ),
        ));

        $this->add(array(
            'name' => 'ano',
            'attributes' => array(
                 'type'  => 'number',
                 'required'=>'required'
            ),
            'options' => array(
                'label' => 'Año',
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'mes',
            'options' => array(
                    'label' => 'Mes:',
                    'empty_option' => 'Seleccione un mes',
                    'value_options' => array(
                      '1' => 'Enero',
                      '2' => 'Febrero',
                      '3' => 'Marzo',
                      '4' => 'Abril',
                      '5' => 'Mayo',
                      '6' => 'Junio',
                      '7' => 'Julio',
                      '8' => 'Agosto',
                      '9' => 'Septiembre',
                      '10' => 'Octubre',
                      '11' => 'Noviembre',
                      '12' => 'Diciembre'
                    ),
            ),
            'attributes' => array(
                'required'=>'required'
            )
        ));

        $this->add(array(
            'name' => 'pais',
            'attributes' => array(
                 'type'  => 'text',
            ),
            'options' => array(
                'label' => 'País:',
            )
        ));

        $this->add(array(
            'name' => 'ciudad',
            'attributes' => array(
                 'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Ciudad:',
            )
        ));

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'maxlength' => 2500,
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Descripción del producto:',
            )
        ));
        
        $this->add(array(
            'name' => 'tipo',
            'attributes' => array(
                'maxlength' => 2500,
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Tipo de producto:',
            )
        ));

        $this->add(array(
            'name' => 'registro',
            'attributes' => array(
                'maxlength' => 7000,
                'type'  => 'textarea'
            ),
            'options' => array(
                'label' => 'Registro:',
            ),
        ));

        $this->add(array(
            'name' => 'instituciones',
            'attributes' => array(
                'maxlength' => 2500,
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Instituciones que participaron en el proyecto:',
            ),
        ));
	   
        $this->add(array(
            'name' => 'otra_informacion',
            'attributes' => array(
                'maxlength' => 7000,
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Otra información:',
            ),
        ));

        $this->add(array(
            'name' => 'filtro_autor',
            'attributes' => array(
                'id' => 'filtro_autor',
                'type' => 'Text',
                'placeholder' => 'Filtrar'
            ),
            'options' => array(
                'label' => 'Filtro autor: '
            )
        ));

        $this->add(array(
            'name' => 'id_autor',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_autor'
            ),
            'options' => array(
                'label' => 'Autor:'
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

