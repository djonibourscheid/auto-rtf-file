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


// Criando pasta de saída
$mesEscrito = mb_strtoupper($mesesDoAno[$mes - 1]);
mkdir($mes - $mesEscrito);

// Criando o arquivo de cada dia
for ($contadorDia = 01; $contadorDia <= $qntDiasMes; $contadorDia++) {
  $numeroSemana = date('w', mktime(0, 0, 0, $mes, $contadorDia, $ano)); // 0 à 6

  // Se for domingo ou sábado
  if ($numeroSemana == 0 || $numeroSemana == 6) {
    continue;
  }

  // Transformando o número da semana para texto
  $semana = mb_strtoupper(str_replace($numeroSemana, $diasDaSemana[$numeroSemana], $numeroSemana));
  $dia = str_pad($contadorDia, 2, '0', STR_PAD_LEFT); // de 1 para 01

  // Criando o arquivo
  $arquivoNome = "{$dia}-{$mes}-{$ano} {$semana}.rtf"; // 01-11-2023 QUARTA.rtf
  $arquivo = fopen("$mesEscrito/$arquivoNome", "w+"); // Cria o arquivo na pasta que foi criada anteriormente

  fwrite($arquivo, $semana);

  fclose($arquivo);
}