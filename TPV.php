<?php
/**
 * Class Sermepa https://github.com/ssheduardo/sermepa
 */
class TPV{
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


    //Real = https://sis-t.redsys.es/sis/realizarPago
    public function __construct($entorno='https://sis-t.redsys.es:25443/sis/realizarPago', $moneda ='978', $terminal='1', $merchantData='', $tipoTransaccion=0, $idioma ='001', $method='T', $nombreFormulario='formX', $botonSubmit='')
    {
        $this->_entorno=$entorno;
        $this->_moneda =$moneda;
        $this->_terminal =$terminal;
        $this->_merchantData = $merchantData;
        $this->_tipoTransaccion=$tipoTransaccion;
        $this->_idioma = $idioma;
        $this->_methods=$method;
        $this->_nombreFormulario = $nombreFormulario;
        $this->_submit = $botonSubmit;
    }

    public function setIdioma($codeidioma)
    {
        $this->_idioma = $codeidioma;
    }

    public function setEntorno($entorno='pruebas')
    {
        if(trim($entorno) == 'real'){
            $this->_entorno='https://sis.redsys.es/sis/realizarPago';
        }
        else{
            $this->_entorno ='https://sis-t.redsys.es:25443/sis/realizarPago';
        }
    }

    public function geturlentorno()
    {
        return $this->_entorno;
    }

    public function getClave()
    {
        return $this->_clave;
    }

    public function setImporte($importe=0)
    {
        $importe = $this->parseFloat($importe);
        $importe = intval(strval($importe*100));
        $this->_importe=$importe;
    }

    public function getImporte(){
        return $this->_importe;
    }

    public function setMoneda($moneda='978')
    {
        if($moneda == '978' || $moneda =='840' || $moneda =='826' || $moneda =='392' ){
            $this->_moneda = $moneda;
        }
        else{
            throw new Exception('Moneda no valida');
        }
    }

    public function setNumeroPedido($pedido='')
    {
        if(hasValue($pedido)){
            $this->_pedido = $pedido;
        }
        else{
            throw new Exception('Falta agregar el número de pedido');
        }
    }


    public function setDatoscomercio($datoscomercio)
    {
        $this->_merchantData = trim($datoscomercio);
    }

    public function setDescripcionProducto($producto='')
    {
        if(hasValue($producto)){
            $this->_descripcionProducto = $producto;
        }
        else{
            throw new Exception('Falta agregar la descripción del producto');
        }
    }

    public function setTitular($titular='')
    {
        if(hasValue($titular)){
            $this->_titular = $titular;
        }
        else{
            throw new Exception('Falta agregar el titular que realiza la compra');
        }
    }

    public function setFuc($fuc='')
    {
        if(hasValue($fuc)){
            $this->_fuc = $fuc;
        }
        else{
            throw new Exception('Falta agregar el código FUC');
        }
    }

    public function setTerminal($terminal=1)
    {
        if(intval($terminal) != 0){
            $this->_terminal = $terminal;
        }
        else{
            throw new Exception('El terminal no es valido');
        }
    }

    public function setTipoTransaccion($transactiontype=0)
    {
        if(hasValue($transactiontype)){
            $this->_tipoTransaccion= $transactiontype;
        }
        else{
            throw new Exception('Falta agregar el tipo de transacción');
        }
    }

    public function setNombreComercio($nombrecomercio='')
    {
        $nombrecomercio = trim($nombrecomercio);
        $this->_nombreComercio = $nombrecomercio;
    }

    public function setUrlNotificacion($url_notificacion='')
    {
        if(hasValue($url_notificacion)){
            $this->_urlNotificacion = $url_notificacion;
        }
        else{
            throw new Exception('Falta agregar url de notificacion');
        }
    }

    public function setUrlOk($url='')
    {
        $this->_setUrlOk = $url;
    }

    public function setUrlKo($url='')
    {
        $this->_urlKo = $url;
    }

    public function setClave($clave='')
    {
        if(hasValue($clave)){
            $this->_clave = $clave;
        }
        else{
            throw new Exception('Falta agregar la clave');
        }
    }
    //T = Pago con Tarjeta, R = Pago por Transferencia, D = Domiciliacion] por defecto es T
    public function setMethod($metodo='T')
    {
        $this->_methods= $metodo;
    }

    public function setFirma()
    {
        $mensaje = $this->_importe . $this->_pedido . $this->_fuc . $this->_moneda . $this->_tipoTransaccion . $this->_urlNotificacion . $this->_clave;
        if(hasValue($mensaje)){
            $this->_firma = sha1($mensaje);
            // $this->sendEmailOrder($mensaje);
        }
        else{
            throw new Exception('Falta agregar la firma, Obligatorio');
        }
    }

    protected function sendEmailOrder($mensaje){
        $txt="Importe: ".$this->_importe."\n";
        $txt.="Pedido: ".$this->_pedido."\n";
        $txt.="Comercio: ".$this->_fuc."\n";
        $txt.="Moneda: ".$this->_moneda."\n";
        $txt.="Transaccion: ".$this->_tipoTransaccion."\n";
        $txt.="Url: ".$this->_urlNotificacion."\n";
        $txt.="Clave: ".$this->_clave."\n";
        $txt.= "Cadena: ".$mensaje."\n";
        $txt.= "Hora:".date('H:i:s Y-m-d')."\n";
        $txt.= "Firma: ".$this->_firma;
        mail('j.eslem03@gmail.com', 'Datos orden '.$this->_pedido, $txt);
    }

    public function setNombreFormulario($nombre = 'form_name')
    {
        $this->_nombreFormulario = $nombre;
    }

    public function createSubmitButton($nombre = 'submitPayment',$texto='Enviar')
    {
        if(!hasValue($nombre))
            throw new Exception('Asigne nombre al boton submit');
        $btnsubmit = '<input type="submit" name="'.$nombre.'" id="'.$nombre.'" value="'.$texto.'" />';
        $this->_submit = $btnsubmit;
    }

    public function submitForm()
    {
        echo $this->createForm();
        echo '<script>document.forms["'.$this->_nombreFormulario.'"].submit();</script>';
    }

    public function checkResponse($postData='')
    {
        // if ($this->_clave === null) {
        //     throw new Exception('Falta agregar la clave proporcionada por sermepa');
        // }
        // try
        // {
        if (isset($postData))
        {
                $Ds_Response = $postData['Ds_Response']; //codigo de respuesta
                $Ds_Amount = $postData['Ds_Amount']; //monto de la orden
                $Ds_Order = $postData['Ds_Order']; //numero de orden
                $Ds_MerchantCode = $postData['Ds_MerchantCode']; //codigo de comercio
                $Ds_Currency = $postData['Ds_Currency']; //moneda
                $firmaBanco = $postData['Ds_Signature']; //firma hecha por el banco
                $Ds_Date = $postData['Ds_Date']; //fecha
                // creamos la firma para comparar
                $toHash = $Ds_Amount . $Ds_Order . $Ds_MerchantCode . $Ds_Currency . $Ds_Response . $this->_clave;
                $firma = strtoupper(sha1($toHash));
                $Ds_Response =(int) $Ds_Response; //convertimos la respuesta en un numero concreto.
                //Comprueba la firma y respuesta
                //Nota: solo en el caso de las preautenticaciones (preautorizaciones separadas), se devuelve un 0 si está autorizada y el titular se autentica y, un 1 si está autorizada y el titular no se autentica.
                if ($firma == $firmaBanco) {
                    if ($Ds_Response < 100) {
                        return true;
                    }
                    else{
                        // throw new Exception("Error en la transacción, código ".$Ds_Response);
                        echo "Error en la transacción, código ".$Ds_Response;
                        return false;
                    }
                } else {
                    // throw new Exception("Las firmas no coinciden");
                    echo "Las firmas no coinciden";
                    return false;
                }
            } else {
                // throw new Exception("Debes pasar la variable POST devuelta por el banco");
                // echo "Debes pasar la variable POST devuelta por el banco";
                return false;
            }
        }

        public function createForm()
        {
            $formulario='
            <form action="'.$this->_entorno.'" method="post" id="'.$this->_nombreFormulario.'" name="'.$this->_nombreFormulario.'" >
                <input type="hidden" name="Ds_Merchant_Amount" value="'.$this->_importe.'" />
                <input type="hidden" name="Ds_Merchant_Currency" value="'.$this->_moneda.'" />
                <input type="hidden" name="Ds_Merchant_Order" value="'.$this->_pedido.'" />
                <input type="hidden" name="Ds_Merchant_ProductDescription" value="'.$this->_descripcionProducto.'" />
                <input type="hidden" name="Ds_Merchant_Titular" value="'.$this->_titular.'" />
                <input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$this->_fuc.'" />
                <input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$this->_urlNotificacion.'" />
                <input type="hidden" name="Ds_Merchant_UrlOK" value="'.$this->_setUrlOk.'" />
                <input type="hidden" name="Ds_Merchant_UrlKO" value="'.$this->_urlKo.'" />
                <input type="hidden" name="Ds_Merchant_MerchantName" value="'.$this->_nombreComercio.'" />
                <input type="hidden" name="Ds_Merchant_ConsumerLanguage " value="'.$this->_idioma.'" />
                <input type="hidden" name="Ds_Merchant_Terminal" value="'.$this->_terminal.'" />
                <input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$this->_firma.'" />
                <input type="hidden" name="Ds_Merchant_TransactionType" value="'.$this->_tipoTransaccion.'" />
                <input type="hidden" name="Ds_Merchant_MerchantData" value="'.$this->_merchantData.'" />
                <input type="hidden" name="Ds_Merchant_PayMethods" value="'.$this->_methods.'" />
                ';
                $formulario.=$this->_submit;
                $formulario.='
            </form>
            ';
            return $formulario;
        }


        protected function parseFloat($ptString)
        {
            if (strlen($ptString) == 0) {
                return false;
            }
            $pString = str_replace(" ", "", $ptString);
            if (substr_count($pString, ",") > 1)
                $pString = str_replace(",", "", $pString);
            if (substr_count($pString, ".") > 1)
                $pString = str_replace(".", "", $pString);
            $pregResult = array();
            $commaset = strpos($pString,',');
            if ($commaset === false) {
                $commaset = -1;
            }
            $pointset = strpos($pString,'.');
            if ($pointset === false) {
                $pointset = -1;
            }
            $pregResultA = array();
            $pregResultB = array();
            if ($pointset < $commaset) {
                preg_match('#(([-]?[0-9]+(\.[0-9])?)+(,[0-9]+)?)#', $pString, $pregResultA);
            }
            preg_match('#(([-]?[0-9]+(,[0-9])?)+(\.[0-9]+)?)#', $pString, $pregResultB);
            if ((isset($pregResultA[0]) && (!isset($pregResultB[0])
                || strstr($pregResultA[0],$pregResultB[0]) == 0
                || !$pointset))) {
                $numberString = $pregResultA[0];
            $numberString = str_replace('.','',$numberString);
            $numberString = str_replace(',','.',$numberString);
        }
        elseif (isset($pregResultB[0]) && (!isset($pregResultA[0])
            || strstr($pregResultB[0],$pregResultA[0]) == 0
            || !$commaset)) {
            $numberString = $pregResultB[0];
        $numberString = str_replace(',','',$numberString);
    }
    else {
        return false;
    }
    $result = (float)$numberString;
    return $result;
}
}

function hasValue($str){
    return strlen(trim($str)) > 0;
}
?>
