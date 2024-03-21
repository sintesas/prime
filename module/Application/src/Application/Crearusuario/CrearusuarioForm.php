<?php
namespace Application\Crearusuario;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Form\Form;
use Zend\Form\Element;
use Application\Modelo\Entity\Usuarios;

class CrearusuarioForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create fields
        $this->add(array(
            'name' => 'primer_nombre',
            
            'attributes' => array(
                'type' => 'text',
                'size' => 25,
                'required' => 'required',
                'placeholder' => 'Ingrese el primer nombre'
            ),
            'options' => array(
                'label' => 'Primer Nombre:'
            )
        ));
        
        $this->add(array(
            'name' => 'segundo_nombre',
            'attributes' => array(
                'type' => 'text',
                'size' => 25,
                'placeholder' => 'Ingrese el segundo nombre'
            ),
            'options' => array(
                'label' => 'Segundo Nombre:'
            )
        ));
        
        $this->add(array(
            'name' => 'primer_apellido',
            'attributes' => array(
                'type' => 'text',
                'size' => 25,
                'required' => 'required',
                'placeholder' => 'Ingrese el primer apellido'
            ),
            'options' => array(
                'label' => 'Primer Apellido:'
            )
        ));
        
        $this->add(array(
            'name' => 'segundo_apellido',
            'attributes' => array(
                'type' => 'text',
                'size' => 25,
                'placeholder' => 'Ingrese el segundo apellido'
            ),
            'options' => array(
                'label' => 'Segundo Apellido:'
            )
        ));
        
        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type' => 'Number',
                'size' => 15,
                'required' => 'required',
                'placeholder' => 'Ingrese documento'
            ),
            'options' => array(
                'label' => 'Documento:'
            )
        ));
        
        $this->add(array(
            'name' => 'direccion',
            'attributes' => array(
                'type' => 'text',
                'size' => 35,
                'required' => 'required',
                'placeholder' => 'Ingrese la dirección de residencia'
            ),
            'options' => array(
                'label' => 'Dirección:'
            )
        ));
        
        $this->add(array(
            'name' => 'telefono',
            'attributes' => array(
                'type' => 'Number',
                'size' => 15,
                'required' => 'required',
                'placeholder' => 'Ingrese el número de teléfono'
            ),
            'options' => array(
                'label' => 'Teléfono:'
            )
        ));
        
        $this->add(array(
            'name' => 'celular',
            'attributes' => array(
                'type' => 'Number',
                'size' => 15,
                'placeholder' => 'Ingrese el número de celular'
            ),
            'options' => array(
                'label' => 'Celular:'
            )
        ));
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
                'size' => 25,
                'required' => 'required',
                'placeholder' => 'Ingrese el correo'
            ),
            'options' => array(
                'label' => 'Email:'
            )
        ));
        
        $this->add(array(
            'name' => 'fecha_nacimiento',
            'attributes' => array(
                'type' => 'Date',
                'required' => 'required',
                'placeholder' => 'YYYY-MM-DD'
            ),
            'options' => array(
                'label' => 'Fecha Nacimiento:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'size' => 5,
                'type' => 'submit',
                'required' => 'required',
                'value' => 'Guardar',
                'class' => 'btn'
            )
        ));
        
        $this->add(array(
            'name' => 'cancelar',
            'attributes' => array(
                'size' => 5,
                'type' => 'submit',
                'required' => 'required',
                'value' => 'Cancelar',
                'class' => 'postfix button expand'
            )
        ));
    }
}