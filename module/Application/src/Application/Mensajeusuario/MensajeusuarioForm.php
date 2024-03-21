<?php
namespace Application\Mensajeusuario;

use Zend\Form\Form;
use Zend\Form\Element;

class MensajeusuarioForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create fields
        $this->add(array(
            'name' => 'asunto',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el asunto de su mensaje'
            ),
            'options' => array(
                'label' => 'Asunto:'
            )
        ));
        
        $file = new Element\File('image-file');
        $file->setLabel('Seleccione un archivo')->setAttribute('id', 'image-file');
        $this->add($file);
        
        $this->add(array(
            'name' => 'mensaje',
            'attributes' => array(
                'type' => 'textarea',
                'placeholder' => 'Ingrese el mensaje'
            ),
            'options' => array(
                'label' => 'Mensaje:'
            )
        ));
        
        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Buscar por Documento:'
            )
        ));
        
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Buscar por Nombre:'
            )
        ));
        
        $this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Buscar por Usuario:'
            )
        ));
        
        $this->add(array(
            'name' => 'apellido',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Buscar por Apellido:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'required' => 'required',
                'value' => 'Enviar',
                'class' => 'btn'
            )
        ));
    }
}

