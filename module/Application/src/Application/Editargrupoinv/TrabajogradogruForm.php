<?php


namespace Application\Editargrupoinv;

use Zend\Form\Form;
use Zend\Form\Element;

class TrabajogradogruForm extends Form 
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
            'name' => 'nombre_trabajo',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Nombre del trabajo:',
            ),
        ));

        $this->add(array(
            'name' => 'id_tipotrabajo',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_tipotrabajo',
                'required'=>false,
            ),
            'options' => array(
                'label' => 'Tipo de trabajo:'
            )
        ));

        $this->add(array(
            'name' => 'id_estadotipotrabajo',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_estadotipotrabajo',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Estado tipo de trabajo:'
            )
        ));

        $this->add(array(
            'name' => 'id_tipoparticipacion',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_tipoparticipacion',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Tipo de participación trabajo de grado:'
            )
        ));

        $this->add(array(
            'name' => 'filtro_autor',
            'attributes' => array(
                'id' => 'filtro_autor',
                'type' => 'text',
                'placeholder' => 'Filtro',
                'onkeyup' => 'showHint(this.value)'
            ),
            'options' => array(
                'label' => 'Filtro usuario:'
            )
        ));

        $this->add(array(
            'name' => 'id_autor',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_autor'
            ),
            'options' => array(
                'label' => 'Nombres y apellidos del estudiante:'
            )
        ));

        $this->add(array(
            'name' => 'id_institucion',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_institucion',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Institución:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'ciudad_trabajo',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Ciudad donde se realizó el trabajo:',
            ),
        ));

        $this->add(array(
            'name' => 'id_unidad',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_unidad',
                'required'=>false,
                'onchange' => 'myFunction2();'
            ),
            'options' => array(
                
                'label' => 'Unidad académica:'
            )
        ));

        $this->add(array(
            'name' => 'id_dependencia',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_dependencia',
                'onchange' => 'myFunction3();',
                'required'=>false,
            ),
            'options' => array(
                'label' => 'Dependencia académica:'
            )
        ));

        $this->add(array(
            'name' => 'id_programa',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_programa',
                'required'=>false,
            ),
            'options' => array(
                'label' => 'Programa académico:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_inicio',
            'attributes' => array(
                'required'=>false,
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha de inicio:',
                'format' => 'Y-m-d'
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_fin',
            'attributes' => array(
                'required'=>false,
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha fin:',
                'format' => 'Y-m-d'
            ),
        ));

        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Descripción trabajo:',
            )
        ));

        $this->add(array(
            'name' => 'otra_informacion',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Otra información relevante:',
            )
        ));

        $this->add(array(
            'name' => 'id_formacioninvestigador',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_formacioninvestigador',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Formación investigador:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'codigo_proyecto',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Código proyecto:',
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'nombre_proyecto',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Nombre del proyecto:',
            ),
        ));

        $this->add(array(
            'name' => 'filtro_investigador',
            'attributes' => array(
                'id' => 'filtro_investigador',
                'type' => 'text',
                'placeholder' => 'Filtro',
                'onkeyup' => 'showHintInvestigador(this.value)'
            ),
            'options' => array(
                'label' => 'Filtro:'
            )
        ));

        $this->add(array(
            'name' => 'id_investigador',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_investigador'
            ),
            'options' => array(
                'label' => 'Nombre(s) del investigador(a) formado:'
            )
        ));

        $this->add(array(
            'name' => 'id_institucion_proyecto',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_institucion_proyecto',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Institución:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'ciudad_proyecto',
            'attributes' => array(
                'required'=>false,
                'type'  => 'text',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Ciudad:',
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'personas_formadas',
            'attributes' => array(
                'required'=>false,
                'type'  => 'number',
                'maxlength' => 500,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Número de personas formadas:',
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_inicio_proyecto',
            'attributes' => array(
                'required'=>false,
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha de inicio:',
                'format' => 'Y-m-d'
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_fin_proyecto',
            'attributes' => array(
                'required'=>false,
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha fin:',
                'format' => 'Y-m-d'
            ),
        ));

        $this->add(array(
            'name' => 'descripcion_proyecto',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Descripción del proyecto (resumen ejecutivo):',
            )
        ));

        $this->add(array(
            'name' => 'descripcion_formacion',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Descripción de la formación:',
            )
        ));

        $this->add(array(
            'name' => 'otra_informacion_proyecto',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Otra información:',
            )
        ));

        $this->add(array(
            'name' => 'id_semillero',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_semillero',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Código y nombre del semillero:'
            )
        ));

        $this->add(array(
            'name' => 'id_institucion_semillero',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_institucion_semillero',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Institución'
            )
        ));

        $this->add(array(
            'name' => 'id_rolparticipacion',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'id_rolparticipacion',
                'required'=>false,
            ),
            'options' => array(
                
                'label' => 'Rol de participación:'
            )
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_inicio_semillero',
            'attributes' => array(
                'required'=>false,
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha de inicio:',
                'format' => 'Y-m-d'
            ),
        ));

        //create field
        $this->add(array(
            'name' => 'fecha_fin_semillero',
            'attributes' => array(
                'required'=>false,
                'type'  => 'date',
                'maxlength' => 10,
                'style' => 'max-width: 100%;width: 100%;',
            ),
            'options' => array(
                'label' => 'Fecha fin:',
                'format' => 'Y-m-d'
            ),
        ));

        $this->add(array(
            'name' => 'tematica',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Temática:',
            )
        ));

        $this->add(array(
            'name' => 'descripcion_semillero',
            'attributes' => array(
                'maxlength' => 3000,
                'type'  => 'textarea',
                'required'=>false
            ),
            'options' => array(
                'label' => 'Descripción de la formación:',
            )
        ));

       

	    $this->add(array(
	      'name'=>'submit',
		  'attributes'=>array(
            'type'=>'submit',
            'required'=>false,
            'class'=>'btn',
            'value'=>'Agregar',
		  ),
	   ));
    }
}

