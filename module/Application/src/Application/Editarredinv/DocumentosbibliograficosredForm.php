<?php
namespace Application\Editarredinv;

use Zend\Form\Form;
use Zend\Form\Element;

class DocumentosbibliograficosredForm extends Form
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
        
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Ingrese el nombre del documento',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Nombre:',
            ),
        ));

        $this->add(array(
            'name' => 'numero_paginas',
            'attributes' => array(
                'type'  => 'number',
                'placeholder' => 'Ingrese el número de páginas',
            ),
            'options' => array(
                'label' => 'Número de páginas:',
            ),
        ));

        $this->add(array(
            'name' => 'ano',
            'attributes' => array(
                'type' => 'number',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Año:',
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
            'name' => 'pais',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'País',
            ),
        ));

        $this->add(array(
            'name' => 'ciudad',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Ciudad:',
            ),
        ));

        $this->add(array(
            'name' => 'numero_indexacion',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Número indexación:',
            ),
        ));

        $this->add(array(
            'name' => 'url',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'URL:',
            ),
        ));

        $this->add(array(
            'name' => 'instituciones',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Instituciones participantes:',
            ),
        ));

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Descripción:',
            ),
        ));

        $this->add(array(
            'name' => 'medio_divulgacion',
            'attributes' => array(
                'type'  => 'text',
                 'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Medio de divulgación:',
            ),
        ));

        $this->add(array(
            'name' => 'filtro_autor',
            'attributes' => array(
                'id' => 'filtro_autor',
                'type' => 'text',
                'placeholder' => 'Filtrar',
                'onkeyup' => 'showHint(this.value)'
            ),
            'options' => array(
                'label' => 'Filtro Autor: '
            )
        ));

        $this->add(array(
            'name' => 'id_autor',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_autor',
                'required' => 'required',
            ),
            'options' => array(
                
                'label' => 'Autor:'
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

