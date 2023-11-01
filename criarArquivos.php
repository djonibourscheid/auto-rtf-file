<?php

// Variáveis gerais
$diasDaSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
$mesesDoAno = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
$regex = "/^[0-9]{2}[\/][0-9]{4}$/";


// Entrada de data
fwrite(STDOUT, "Olá! Para gerar a agenda, insira o mês e ano no formato: 01/2024\n");
$dataEntrada = trim(fgets(STDIN));


// Validando se a data está correta
$dataValida = preg_match($regex, $dataEntrada);
if (!$dataValida) {
  fwrite(STDERR, "Data inválida. Tente novamente com utilizando o padrão 01/2024\n");
  exit();
}

echo "Data válida";