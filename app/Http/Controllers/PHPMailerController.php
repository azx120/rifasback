<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;
use App\Models\Productos;


class PHPMailerController extends Controller {
 
    // =============== [ Email ] ===================
    public function email() {
        return view("email");
    }
 
 
    // ========== [ Compose Email ] ================
    public static  function composeEmail($data) {
 
       
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions
        $Subject = '';
        $Body = '';
        $Address= '';
        $Acc = '';

        //$seller = User::where('id', '=', $data['id_seller'])->first();
   
        try {
 
            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'mail.accumed.cloud';             //  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = 'test@accumed.cloud';   //  sender username
            $mail->Password = 'AlucinamkT/*10';       // sender password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                  // encryption - ssl/tls
            $mail->Port = 465;
            

            $mail->setFrom('test@accumed.cloud', 'Rifas M&M');


          
            if($data['type'] == 'recibo'){

                $mail->addAddress($data['email']);
                //$mail->addAddress($user->email);
                $mail->addCC('test456@gmail.com', 'vendedor');
                //$mail->addCC('ventas@fastworldmarket.com', 'admin');
                $html = '<html>
                                <head>
                                    <style>
                                        body {
                                            font-family: Arial, sans-serif;
                                            max-width: 600px;
                                            margin: 0 auto;
                                            padding: 20px;
                                            color: #333;
                                        }
                                        h1 {
                                            color: #2c3e50;
                                            font-size: 24px;
                                            margin-bottom: 20px;
                                        }
                                        .order-info {
                                            margin-bottom: 30px;
                                        }
                                        .info-row {
                                            display: flex;
                                            margin-bottom: 10px;
                                        }
                                        .info-label {
                                            font-weight: bold;
                                            width: 150px;
                                        }
                                        .info-value {
                                            flex-grow: 1;
                                        }
                                        .order-number {
                                            color: red;
                                            font-weight: bold;
                                        }
                                        hr {
                                            border: 0;
                                            height: 1px;
                                            background-color: #ddd;
                                            margin: 20px 0;
                                        }
                                        .payment-instructions {
                                            margin-top: 20px;
                                        }
                                        .payment-instructions ol {
                                            padding-left: 20px;
                                        }
                                        .payment-instructions li {
                                            margin-bottom: 10px;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <h1>Hola! '.$data['names'].' te enviamos un correo con la siguienete información.</h1>
                                    
                                    <div class="order-info">
                                        <div class="info-row">
                                            <div class="info-label">Número de pedido:</div>
                                            <div class="info-value order-number">'.$data['pedido'].'</div>
                                        </div>
                                        <hr/>
                                        <div class="info-row">
                                            <div class="info-label">Fecha:</div>
                                            <div class="info-value">'.$data['fecha'].'</div>
                                        </div>
                                        <hr/>
                                        <div class="info-row">
                                            <div class="info-label">Total:</div>
                                            <div class="info-value">$'.$data['qty'].'</div>
                                        </div>
                                        <hr/>
                                        <div class="info-row">
                                            <div class="info-label">Tus Numeros son los siguientes:</div>
                                            <div class="info-value">'.$data['selecionados'].'</div>
                                        </div>
                                        <hr/>
                                        <div class="info-row">
                                            <div class="info-label">Método de pago:</div>
                                            <div class="info-value">'.$data['metodo'].'</div>
                                        </div>
                                    </div>
                                </body>
                                </html>';

                //$pdf= PDF::loadHTML($html);
                //$pdfString = $pdf->output();
                $mail->isHTML(true);                // Set email content format to HTML
                $mail->Subject = 'Recibo de mumeros';
                //$mail->Body    = 'Se ah registrado una nueva compra con ls sigueinete informacion:';
                $mail->Body    = $html;
                //$mail->addStringAttachment($pdfString, 'factura.pdf');
        
            }else if($data['type']  == 'compra'){
                $mail->addAddress($data['email']);
                $mail->Subject = 'Nuevo Compra';
                $html  = '<html>
                                <head>
                                    <style>
                                        body {
                                            font-family: Arial, sans-serif;
                                            max-width: 600px;
                                            margin: 0 auto;
                                            padding: 20px;
                                            color: #333;
                                        }
                                        h1 {
                                            color: #2c3e50;
                                            font-size: 24px;
                                            margin-bottom: 20px;
                                        }
                                        .order-info {
                                            margin-bottom: 30px;
                                        }
                                        .info-row {
                                            display: flex;
                                            margin-bottom: 10px;
                                        }
                                        .info-label {
                                            font-weight: bold;
                                            width: 150px;
                                        }
                                        .info-value {
                                            flex-grow: 1;
                                        }
                                        .order-number {
                                            color: red;
                                            font-weight: bold;
                                        }
                                        hr {
                                            border: 0;
                                            height: 1px;
                                            background-color: #ddd;
                                            margin: 20px 0;
                                        }
                                        .payment-instructions {
                                            margin-top: 20px;
                                        }
                                        .payment-instructions ol {
                                            padding-left: 20px;
                                        }
                                        .payment-instructions li {
                                            margin-bottom: 10px;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <h1>¡Gracias! Tu compra ha sido recibida '.$data['names'].'.</h1>
                                    
                                    <div class="order-info">
                                        <div class="info-row">
                                            <div class="info-label">Número de pedido:</div>
                                            <div class="info-value order-number">'.$data['pedido'].'</div>
                                        </div>
                                        <hr/>
                                        <div class="info-row">
                                            <div class="info-label">Fecha:</div>
                                            <div class="info-value">'.$data['fecha'].'</div>
                                        </div>
                                        <hr/>
                                        <div class="info-row">
                                            <div class="info-label">Total:</div>
                                            <div class="info-value">$'.$data['qty'].'</div>
                                        </div>
                                        <hr/>
                                        <div class="info-row">
                                            <div class="info-label">Método de pago:</div>
                                            <div class="info-value">'.$data['metodo'].'</div>
                                        </div>
                                    </div>
                                </body>
                                </html>';

                                $mail->isHTML(true);                // Set email content format to HTML
                $mail->Subject = 'Gracias por su compra';
                //$mail->Body    = 'Se ah registrado una nueva compra con ls sigueinete informacion:';
                $mail->Body    = $html;

            }
            //$mail->addBCC('lololol');
 
            /*$mail->addAddress($request->emailRecipient);
            $mail->addCC($request->emailCc);
            $mail->addBCC($request->emailBcc);
            $mail->addReplyTo('sender@example.com', 'SenderReplyName');
            */
            //$mail->Subject = $request->emailSubject;
            //$mail->Body    = $request->emailBody;
 
            // $mail->AltBody = plain text version of email body;
            
            if( !$mail->send() ) {
                return false;
            }else {
                return true;
            }
 
        } catch (Exception $e) {
             return false;
        }
    }


    public static function sendPost($numberPhone, $msj)
    {
    

            $mensaje = array( 
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => '584124119019',
                "type" => "text",
                "text" => array(
                    "preview_url" => false,
                    "body" => $msj,
                )
            );

            $mensaje = json_encode($mensaje); 

            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v22.0/184815844707599/messages"); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Bearer EAAVfF7edYckBO4wfU2KyKtfsUZBxF2w7dZCXbPPZCB7KnA5YucqHth4bSJHdw5lPMAriqN0ebidvg7nFImYZA6EGZCKZBbviU3jU5mdJZB1gHaeSzQcZCr7TfesZBwGdvvBfehkKYNMBcxnWLix1dxz3ZBvKmWg4BXF4igyX9lbaPw83rAMqofUe930BRKYtATgs6o2AZDZD' // one siynal keys_and_ids
            )); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            curl_setopt($ch, CURLOPT_HEADER, FALSE); 
            curl_setopt($ch, CURLOPT_POST, TRUE); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $mensaje); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
            
            $response = curl_exec($ch); 
            curl_close($ch); 
            return $r = json_decode($response,true);
            /*if($r !== null){
                if($r['success'] == true){
                    $content      = array(
                        "en" => 'tu codigo es: '.$code,
                    );
                            
                    $fields = array(
                        'app_id' => "f4e0f05a-e5de-4fac-b340-eba2b6c77b35", // one siynal keys_and_ids
                
                        "sms_from" => "+19592511582",
                        "name"=> "Identifier for SMS Message",
                        "include_phone_numbers" => ["+".$numberPhone],
                        'data' => array(
                            "foo" => "bar"
                        ),
                        'contents' => $content,
                    );
                    
                    $fields = json_encode($fields);
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json; charset=utf-8',
                        'Authorization: Basic NThmYmIxNWItY2FkOC00NDBjLTg3ZTQtMmZjMjMxMmFkYTgw' // one siynal keys_and_ids
                    ));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    
                    $response = curl_exec($ch);
                    curl_close($ch);
                    
                    return true;         
                

                }else{
                    return false;
                }
            }else{
                return false;
            }*/    


        /*$response = sendMessage();
        $return["allresponses"] = $response;
        $return = json_encode($return);
        
        $data = json_decode($response, true);
        print_r($data);
        $id = $data['id'];
        print_r($id);
        */
    }
}