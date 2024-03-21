<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class LibroshvForm extends Form 
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
            'name' => 'titulo_libro',
            'attributes' => array(
                'type'  => 'text',
                 'style' => 'max-width: 100%;width: 100%;'
            ),
            'options' => array(
                'label' => 'Título del libro:',
            ),
        ));
       
        $this->add(array(
            'name' => 'num_paginas',
            'attributes' => array(
                'required'=>'required',
                 'type'  => 'number'
            ),
            'options' => array(
                'label' => 'Número de páginas:',
            )
        ));

        $this->add(array(
            'name' => 'ano',
            'attributes' => array(
                'type' => 'Number',
                'min' => 1980,
                'max' => 3000
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Año:'
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
                'maxlength' => 200,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'País de publicación:'
            )
        ));

        $this->add(array(
            'name' => 'ciudad',
            'attributes' => array(
                'maxlength' => 200,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Ciudad de publicación:'
            )
        ));
       
        $this->add(array(
            'name' => 'serie',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Serie:',
            ),
        ));
       
        $this->add(array(
            'name' => 'editorial',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Editorial:',
            ),
        ));
       
        $this->add(array(
            'name' => 'edicion',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'No. de Edición:',
            ),
        ));
       
        $this->add(array(
            'name' => 'isbn',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'ISBN/e-ISBN:',
            ),
        ));
       
        $this->add(array(
            'name' => 'medio_divulgacion',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Medio de divulgación:',
            ),
        ));
            
        $this->add(array(
            'name' => 'autores',
            'attributes' => array(
                'type'  => 'text'
            ),
            'options' => array(
                'label' => 'Autores:',
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
            'name' => 'tipo_libro',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'tipo_libro'
            ),
            'options' => array(
                'label' => 'Tipo de libro:'
            )
        ));
        
       $this->add(array(
          'name'=>'submit',
          'attributes'=>array(
             'type'=>'submit',
             'required'=>'required',
             'class'=>'btn',
             'value'=>'Agregar',
          )
       ));
       


    }
}

