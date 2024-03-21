<?php


namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class ArticuloshvForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        //create field
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')
             ->setAttribute('id', 'image-file');
        $this->add($file);
        
        // create field
        $this->add(array(
            'name' => 'coautor',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'coautor:'
            )
        ));
        
        $this->add(array(
            'name' => 'serie',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Número de serie:'
            )
        ));
        
        $this->add(array(
            'name' => 'volumen',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Volúmen/Número:'
            )
        ));
        
        $this->add(array(
            'name' => 'num_paginas',
            'attributes' => array(
                'type' => 'Number'
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Número de páginas:'
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
            'name' => 'pagina_inicio',
            'attributes' => array(
                'type' => 'Number'
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Página inicio:'
            )
        ));
        
        $this->add(array(
            'name' => 'pagina_fin',
            'attributes' => array(
                'type' => 'Number'
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Página fin:'
            )
        ));
        
        $this->add(array(
            'name' => 'fasciculo',
            'attributes' => array(
                'type' => 'number'
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Fascículo:'
            )
        ));
        
        $this->add(array(
            'name' => 'paginas',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Páginas donde se encuentra el Artículo:'
            )
        ));
        
        $this->add(array(
            'name' => 'issn',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'ISSN/eISSN:'
            )
        ));
        
        $this->add(array(
            'type' => 'Date',
            'name' => 'fecha',
            'options' => array(
                'label' => 'Fecha:'
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));
        
        $this->add(array(
            'name' => 'nombre_revista',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Nombre de la revista:'
            )
        ));
        
        $this->add(array(
            'name' => 'nombre_articulo',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Nombre del artículo:'
            )
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
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'required' => 'required',
                'class' => 'btn',
                'value' => 'Agregar'
            )
        ));
    }
}

