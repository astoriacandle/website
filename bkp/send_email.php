<?php
// enviar_contato.php

// 1. Configurações do E-mail
// ATENÇÃO: SUBSTITUA ESTE ENDEREÇO PELO SEU E-MAIL REAL!
$recipient_email = 'astoriacandle@gmail.com';
$email_subject = 'Mensagem do Site!';

// 2. Validação Básica dos Dados Recebidos (POST)
// Verifica se os campos não estão vazios.
if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
    // Redireciona de volta para a página do formulário com um status de erro
    header('Location: contato.html?status=invalid');
    exit; // Termina a execução do script
}

// Obtém e "limpa" os dados do formulário para evitar injeção básica
// Filter_var ajuda a validar e sanitizar
$name = htmlspecialchars(strip_tags(trim($_POST['name'])));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(strip_tags(trim($_POST['message'])));

// Validação de e-mail mais específica
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: contato.html?status=invalid');
    exit;
}

// 3. Monta o Conteúdo do E-mail
$email_body = "Nome: {$name}\n";
$email_body .= "E-mail: {$email}\n\n";
$email_body .= "Mensagem:\n{$message}\n";

// Cabeçalhos do E-mail
// Define o cabeçalho "From" para que o e-mail pareça vir do remetente
// (mas note que muitos servidores de e-mail podem bloquear isso por questões de spam,
// e o ideal é usar um e-mail do seu próprio domínio no FROM para autenticação)
$headers = "From: {$name} <{$email}>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-type: text/plain; charset=UTF-8\r\n"; // Garante que caracteres especiais funcionem

// 4. Envia o E-mail
// A função mail() depende da configuração do servidor PHP (sendmail, etc.)
// Se ela retornar true, o e-mail foi enviado com sucesso pelo PHP.
// Isso NÃO garante que o e-mail chegou na caixa de entrada do destinatário.
if (mail($recipient_email, $email_subject, $email_body, $headers)) {
    // E-mail enviado com sucesso
    header('Location: contato.html?status=success');
    exit;
} else {
    // Erro ao enviar o e-mail
    header('Location: contato.html?status=error');
    exit;
}

?>