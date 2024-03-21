<?php
namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class AsociacionesForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create field
        $this->add(array(
            'name' => 'nombre_asociacion',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el nombre de la asociación'
            ),
            'options' => array(
                'label' => 'Nombre de la asociación:'
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

