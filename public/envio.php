<?php
require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/OAuth.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";
require "./bibliotecas/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mensagem
{
	private $para = null;
	private $assunto = null;
	private $mensagem = null;
	public $status = ['code' => null, 'message' => ''];

	public function __get($atributo)
	{
		return $this->$atributo;
	}

	public function __set($atributo, $valor)
	{
		$this->$atributo = $valor;
	}

	public function mensagemValida()
	{
		if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
			return false;
		}

		return true;
	}
}

$mensagem = new Mensagem();

$mensagem->__set('para', $_POST['para']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('mensagem', $_POST['mensagem']);

//print_r($mensagem);

if (!$mensagem->mensagemValida()) {

	header('Location: index.php');
}

$mail = new PHPMailer(true);
try {
	//Server settings
	$mail->SMTPDebug = 2;                      //Enable verbose debug output
	$mail->isSMTP();                                            //Send using SMTP
	$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
	$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	$mail->Username   = 'seu_email';                     //SMTP username
	$mail->Password   = 'sua_senha';                               //SMTP password
	$mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

	//Recipients
	$mail->setFrom(''); // adicione o remetente .
	$mail->addAddress($mensagem->__get('para'));     //Add a recipient

	// $mail->addReplyTo('yanpenabr@gmail.com', 'Information');
	// $mail->addCC('cc@example.com');
	// $mail->addBCC('bcc@example.com');

	//Attachments
	// $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
	$mensagem->status['code'] = 1;
	$mensagem->status['message'] = 'E-mail enviado com sucesso!';
	//Content
	$mail->isHTML(true);                                  //Set email format to HTML
	$mail->Subject = $mensagem->__get('assunto');
	$mail->Body    = $mensagem->__get('mensagem');
	// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	$mail->send();
	echo 'Message has been sent';
} catch (Exception $e) {

	$mensagem->status['code'] = 2;
	$mensagem->status['message'] = 'E-mail não enviado com sucesso!';
	// echo 'Detalhes do erro: ' . $mail->ErrorInfo;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>App Mail Send</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
	<div class="container">
		<div class="py-3 text-center">
			<img class="d-block mx-auto mb-2" src="./assets/images/logo.png" alt="" width="72" height="72">
			<h2>Send Mail</h2>
			<p class="lead">Seu app de envio de e-mails particular!</p>
		</div>

		<div class="row">
			<div class="col-md-12 text-center">
				<?php if ($mensagem->status['code'] == 1) { ?>
					<div class="container">
						<h1 class="display-4 text-success">Sucesso!</h1>
						<p><?= $mensagem->status['message'] ?></p>
						<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Retornar</a>
					</div>
				<?php } ?>

				<?php if ($mensagem->status['code'] == 2) { ?>
					<div class="container">
						<h1 class="display-4 text-danger">Ops!</h1>
						<p><?= $mensagem->status['message'] ?></p>
						<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Retornar</a>
					</div>
				<?php } ?>




			</div>
		</div>
	</div>
</body>

</html>