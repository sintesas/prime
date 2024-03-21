<?php
namespace Application\Usuario;

use Zend\Form\Form;

class UsuarioForm extends Form
{

    function __construct()
    {
        parent::__construct($name = null);
        
        parent::setAttribute('method', 'post');
        parent::setAttribute('action ', 'usuario');
        
        // create fields
        $this->add(array(
            'name' => 'PrimerNombre',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el primer nombre'
            ),
            'options' => array(
                'label' => 'Primer Nombre:'
            )
        ));
        
        $this->add(array(
            'name' => 'id_usu',
            'attributes' => array(
                'type' => 'Number',
                'placeholder' => 'Ingrese el ID del usuario'
            ),
            'options' => array(
                'label' => 'ID Usuario:'
            )
        ));
        
        $this->add(array(
            'name' => 'nombre_grupo',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el grupo de investigación'
            ),
            'options' => array(
                'label' => 'Grupo de Investigación:'
            )
        ));
        
        $this->add(array(
            'name' => 'SegundoNombre',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el segundo nombre'
            ),
            'options' => array(
                'label' => 'Segundo Nombre:'
            )
        ));
        
        $this->add(array(
            'name' => 'PrimerApellido',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el primer apellido'
            ),
            'options' => array(
                'label' => 'Primer Apellido:'
            )
        ));
        
        $this->add(array(
            'name' => 'SegundoApellido',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el segundo nombre'
            ),
            'options' => array(
                'label' => 'Segundo Nombre:'
            )
        ));
        
        $this->add(array(
            'name' => 'Direccion',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese la dirección'
            ),
            'options' => array(
                'label' => 'Dirección:'
            )
        ));
        
        $this->add(array(
            'name' => 'Ciudad',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Ciudad:'
            )
        ));
        
        $this->add(array(
            'name' => 'Telefono',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el teléfono'
            ),
            'options' => array(
                'label' => 'Teléfono:'
            )
        ));
        
        $this->add(array(
            'name' => 'Celular',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Celular:'
            )
        ));
        
        $this->add(array(
            'name' => 'Email',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Email:'
            )
        ));
        
        $this->add(array(
            'type' => 'Date',
            'name' => 'fechaNacimiento',
            'options' => array(
                'label' => 'Fecha Nacimiento'
            )
        ));
        
        $this->add(array(
            'name' => 'lugarNacimiento',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'lugarNacimiento:'
            )
        ));
        
        $this->add(array(
            'name' => 'Sexo',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Sexo:'
            )
        ));
        
        $this->add(array(
            'name' => 'nacionalidad',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'nacionalidad:'
            )
        ));
        
        $this->add(array(
            'name' => 'usuario',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el usuario'
            ),
            'options' => array(
                'label' => 'Filtro Usuario:'
            )
        ));
        
        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el nombre'
            ),
            'options' => array(
                'label' => 'Filtro Nombre:'
            )
        ));
        
        $this->add(array(
            'name' => 'apellido',
            'attributes' => array(
                'type' => 'text',
                'placeholder' => 'Ingrese el apellido'
            ),
            'options' => array(
                'label' => 'Filtro Apellido:'
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'required' => 'required',
                'value' => 'Buscar',
                'class' => 'btn'
            )
        ));
        
        $this->add(array(
            'name' => 'documento',
            'attributes' => array(
                'type' => 'Number',
                'placeholder' => 'Ingrese el documento'
            ),
            'options' => array(
                'label' => 'Filtro Documento:'
            )
        ));
        
        $this->add(array(
            'name' => 'filtrar',
            'attributes' => array(
                'type' => 'submit',
                'required' => 'required',
                'value' => 'Filtrar'
            )
        ));        
    }
}

