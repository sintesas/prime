<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class BibliograficosForm extends Form 
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
            'name' => 'instituciones',
            'attributes' => array(
                'type'  => 'textarea',
				'lenght' => 300,
                'maxlength' => 300,
            ),
            'options' => array(
                'label' => 'Instituciones participantes:',
            ),
        ));

        $this->add(array(
            'name' => 'medio_divulgacion',
            'attributes' => array(
                'type'  => 'textarea',
				'lenght' => 100,
                'maxlength' => 100,
            ),
            'options' => array(
                'label' => 'Medio divulgación:',
            ),
        ));
		
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'textarea',
				'lenght' => 500,
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Descripción:',
            ),
        ));
	   
        $this->add(array(
            'name' => 'autores',
            'attributes' => array(
                'type'  => 'textarea',
				'lenght' => 100,
                'maxlength' => 100,
            ),
            'options' => array(
                'label' => 'Autores:',
            ),
        ));

        $this->add(array(
            'name' => 'numero_paginas',
            'attributes' => array(
                'type'  => 'Number',
                'required'=>'required',
            ),
            'options' => array(
                'label' => 'Número páginas:',
            ),
        ));

        $this->add(array(
            'name' => 'nombre_documento',
            'attributes' => array(
                'type'  => 'text',
                 'maxlength' => 120,
            ),
            'options' => array(
                'label' => 'Nombre:',
            ),
        ));	

        $this->add(array(
            'name' => 'url',
            'attributes' => array(
                'type'  => 'text',
                 'maxlength' => 120
            ),
            'options' => array(
                'label' => 'URL:',
            ),
        ));	

        $this->add(array(
            'name' => 'num_indexacion',
            'attributes' => array(
                'type'  => 'number',
                'required'=>'required'
            ),
            'options' => array(
                'label' => 'Número de indexación:',
            ),
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
            'name' => 'ano',
            'attributes' => array(
                'type'  => 'Number',
                'required'=>'required'
            ),
            'options' => array(
                'label' => 'Año :',
            ),
        ));
	   
        $this->add(array(
            'name' => 'descripcion_producto',
            'attributes' => array(
                'type'  => 'textarea',
				'lenght' => 500,
            ),
            'options' => array(
                'label' => 'Descripción producto:',
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
                'label' => 'Filtro Autor: '
            )
        ));

        $this->add(array(
            'name' => 'pais',
            'attributes' => array(
                'maxlength' => 200,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'País:'
            )
        ));

        $this->add(array(
            'name' => 'ciudad',
            'attributes' => array(
                'maxlength' => 200,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Ciudad:'
            )
        ));

         $this->add(array(
                'name' => 'id_autor',
                'type' => 'Zend\Form\Element\Select',
                'attributes' => array(
                    'id' => 'id_autor'
                ),
                'options' => array(
                    'label' => 'Autor:',
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

