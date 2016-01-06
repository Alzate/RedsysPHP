
<?php
include 'apiRedsys.php';
require 'TPV.php';
class TPV256 extends TPV{

  private $_shaVersion = 'HMAC_SHA256_V1';
  private $params;

  public function setParameters(){

    $api = new RedsysAPI;

    $id=time();
    $api->setParameter("DS_MERCHANT_AMOUNT",$this->_importe);
    $api->setParameter("DS_MERCHANT_ORDER",strval($id));
    $api->setParameter("DS_MERCHANT_MERCHANTCODE",$this->_fuc);
    $api->setParameter("DS_MERCHANT_CURRENCY",$this->_moneda);
    $api->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$this->_tipoTransaccion);
    $api->setParameter("DS_MERCHANT_TERMINAL",$this->_terminal);
    $api->setParameter("DS_MERCHANT_MERCHANTURL",$this->_urlNotificacion);
    $api->setParameter("DS_MERCHANT_URLOK",$this->_setUrlOk);
    $api->setParameter("DS_MERCHANT_URLKO",$this->_urlKo);

    $this->params = $api->createMerchantParameters();
    $this->_firma  = $api->createMerchantSignature($this->_clave);

  }

  public function createForm()
  {
    $formulario='
    <form action="'.$this->_entorno.'" method="post" id="'.$this->_nombreFormulario.'" name="'.$this->_nombreFormulario.'" >
      <input type="hidden" name="Ds_SignatureVersion" value="'.$this->_shaVersion.'" />
      <input type="hidden" name="Ds_MerchantParameters" value="'.$this->params.'" />
      <input type="hidden" name="Ds_Signature" value="'.$this->_firma.'" />
    </form>
    ';
    return $formulario;
  }

  public function checkResponse($postData='')
  {

    $api = new RedsysAPI;
    if ($this->_clave === null) {
      return false;
    }
    if (isset($postData))
    {

      $version = $postData["Ds_SignatureVersion"];
      $datos = $postData["Ds_MerchantParameters"];
      $firmaBanco = $postData["Ds_Signature"];
      if(isset($postData['Ds_Response'])){
        $Ds_Response = $postData['Ds_Response'];
        $Ds_Response =(int) $Ds_Response;
      }

      $decodec = $api->decodeMerchantParameters($datos);
      $firma = $api->createMerchantSignatureNotif($this->_clave,$datos);


      if ($firma == $firmaBanco) {
        if (isset($Ds_Response)){
          if ($Ds_Response < 100) {
            return true;
          }
          else{
            throw new Exception("Error en la transacción, código ".$Ds_Response);
            echo "Error en la transacción, código ".$Ds_Response;
            return false;
          }
        }else{
          return true;
        }
      } else {
        throw new Exception("Las firmas no coinciden");
        echo "Las firmas no coinciden";
        return false;
      }
    } else {
      throw new Exception("Debes pasar la variable POST devuelta por el banco");
      echo "Debes pasar la variable POST devuelta por el banco";
      return false;
    }
  }

}

?>
