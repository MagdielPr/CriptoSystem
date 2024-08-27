<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $mensagem = $_POST['mensagem'];

    $to = 'seu_email_oficial@dominio.com';
    $subject = 'Formulário de Contato';
    $message = "Nome: $nome\nEmail: $email\n\nMensagem:\n$mensagem";

    // Gerando um boundary
    $boundary = md5(time());

    // Headers
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    // Corpo da mensagem
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $message . "\r\n";

    // Anexo
    if(isset($_FILES['anexo']) && $_FILES['anexo']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['anexo']['name'];
        $file_size = $_FILES['anexo']['size'];
        $file_tmp = $_FILES['anexo']['tmp_name'];
        $file_type = $_FILES['anexo']['type'];
        
        $handle = fopen($file_tmp, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $encoded_content = chunk_split(base64_encode($content));

        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
        $body .= $encoded_content;
    }

    $body .= "--$boundary--";

    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Mensagem enviada com sucesso!'); window.location.href='../contato.php';</script>";
    } else {
        echo "<script>alert('Erro ao enviar a mensagem.'); window.location.href='../contato.php';</script>";
    }
} else {
    header('Location: ../contato.php');
}
?>