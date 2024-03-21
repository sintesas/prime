<?php
namespace Application\Editarsemilleroinv;

use Zend\Form\Form;
use Zend\Form\Element;

class ArticulossemilleroForm extends Form
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
            'name' => 'nombre_revista',
            'attributes' => array(
                'required' => 'required',
                'maxlength' => 500,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Nombre de la revista:'
            )
        ));
        
        $this->add(array(
            'name' => 'nombre_articulo',
            'attributes' => array(
                'required' => 'required',
                'maxlength' => 20,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Nombre del artículo:'
            )
        ));
        
        $this->add(array(
            'name' => 'ano',
            'attributes' => array(
                'required' => 'required',
                'maxlength' => 20,
                'type' => 'number'
            ),
            'options' => array(
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
            'name' => 'issn',
            'attributes' => array(
                'maxlength' => 50,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'ISSN/eISSN:'
            )
        ));

        $this->add(array(
            'name' => 'paginas',
            'attributes' => array(
                'maxlength' => 500,
                'required' => 'required',
                'type' => 'number'
            ),
            'options' => array(
                'label' => 'No. de páginas:'
            )
        ));

        $this->add(array(
            'name' => 'pagina_inicio',
            'attributes' => array(
                'maxlength' => 20,
                'required' => 'required',
                'type' => 'number'
            ),
            'options' => array(
                'label' => 'Página inicio:'
            )
        ));
        
        $this->add(array(
            'name' => 'pagina_fin',
            'attributes' => array(
                'maxlength' => 20,
                'required' => 'required',
                'type' => 'number'
            ),
            'options' => array(
                'label' => 'Página fin:'
            )
        ));

        $this->add(array(
            'name' => 'fasciculo',
            'attributes' => array(
                'maxlength' => 500,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Fascículo:'
            )
        ));

        $this->add(array(
            'name' => 'volumen',
            'attributes' => array(
                'maxlength' => 500,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Volúmen/Número:'
            )
        ));

        $this->add(array(
            'name' => 'serie',
            'attributes' => array(
                'maxlength' => 500,
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Número de serie:'
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
            'name' => 'categoria',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Categorización: '
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

