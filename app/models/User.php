<?php

class User extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'users';

    /**
     * Type de champs dans les formulaires
     * @var Array
     */
    public $attributes = [
        'email' => 'email',
        'password' => 'password',
        'pseudo' => 'text',
        'photo' => 'file'
    ];

    /**
     * Restriction lors de l'insertion dans la base de donnée
     * @var Array
     */
    public $validation = [
        'email' => ['required','min:3'],
        'password' => ['required','min:6'],
        'pseudo' => ['required','match:/^[a-zA-Z0-9]+$/'],
        'photo' => ['isImage','maxImageSize:50000000000','fileType:jpg,png,jpeg,gif']
    ];

    /**
     * Ajoute un fichier
     * @param  String $field Nom du fichier dans la requete
     */
    public function moveFile ($field) {
        $path = 'imgs' . DS . 'users' . DS . time() . rand(0,100) . '.' . pathinfo(App::$request->post[$field]['name'], PATHINFO_EXTENSION);
	move_uploaded_file(App::$request->post[$field]['tmp_name'], PUBLIC_DIR . $path);
	App::$request->post[$field] = '/' . $path;
    }

    public function sendNotification ($subject, $content) {
        $mail = new PHPMailer;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Host = 'ssl://smtp.gmail.com:465';
        $mail->Port = 465;                                    // TCP port to connect to
        $mail->Username = 'maxime.flin@example.com';                 // SMTP username
        $mail->Password = 'Wd+24Sr7';                           // SMTP password

        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress('maxime.flin@maxime.flin', 'Maxime Flin');     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }

}
