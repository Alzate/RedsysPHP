# RedsysPHP
Objects for RedSys payment in php

##Usage

```php
  $tpv = new TPV256(); // or TPV() Dependes of encriptation needed
  $tpv->setParameters();
  $tpv->setNombreComercio('name');
  //...set all parameters needed
  $tpv->setParameters();
```
---
####Parameters name

```php
    protected $_entorno;
    protected $_importe;
    protected $_moneda;
    protected $_pedido;
    protected $_merchantData;
    protected $_descripcionProducto;
    protected $_titular;
    protected $_fuc;
    protected $_terminal;
    protected $_tipoTransaccion;
    protected $_urlNotificacion;
    protected $_clave;
    protected $_setUrlOk;
    protected $_urlKo;
    protected $_firma;
    protected $_nombreComercio;
    protected $_idioma;
    protected $_methods;
    protected $_nombreFormulario;
    protected $_submit;
```
