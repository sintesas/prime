<?php
namespace Application\Editarusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class IdiomashvForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create field
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type' => 'text',
                'maxlength' => 500,
            ),
            'options' => array(
                'label' => 'Nombre idioma:'
            )
        ));

        $this->add(array(
            'name' => 'oir',
            'attributes' => array(
                'type' => 'number',
                'min' => 1,
                'max' => 100
            )
            ,
            'options' => array(
                'required' => 'required',
                'label' => 'Porcentaje oír:'
            )
        ));
        
        $this->add(array(
            'name' => 'leer',
            'attributes' => array(
                'type' => 'number',
                'min' => 1,
                'max' => 100
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Porcentaje leer:'
            )
        ));
        
        $this->add(array(
            'name' => 'escribir',
            'attributes' => array(
                'type' => 'number',
                'min' => 1,
                'max' => 100
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Porcentaje escribir:'
            )
        ));
        
        $this->add(array(
            'name' => 'hablar',
            'attributes' => array(
                'type' => 'number',
                'min' => 1,
                'max' => 100
            ),
            'options' => array(
                'required' => 'required',
                'label' => 'Porcentaje hablar:'
            )
        ));

        //Creacion de campos
        //create field
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')->setAttribute('id', 'image-file');
        $this->add($file);
        
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