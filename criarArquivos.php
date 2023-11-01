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


// Capturando mes e ano
[$mes, $ano] = explode("/", $dataEntrada);

// Validando o mês
if ($mes < 01 || $mes > 12) {
  fwrite(STDERR, "Mês inválido. Tente novamente.\n");
  exit();
}

// Mostrando quantidade de dias na tela
$qntDiasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
fwrite(STDOUT, "Quantidade de dias no mês: $qntDiasMes\n");
