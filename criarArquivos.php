<?php

// Variáveis gerais
$diasDaSemana = ['DOMINGO', 'SEGUNDA', 'TERÇA', 'QUARTA', 'QUINTA', 'SEXTA', 'SABADO'];
$mesesDoAno = ['JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO'];
$regex = "/^[0-9]{2}[\/][0-9]{4}$/";
$diasSemanais = 0;


// Entrada de data
fwrite(STDOUT, "\n--------------------------------\n");
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
$mesEscrito = $mesesDoAno[str_pad($mes, 1, '') - 1];
$nomePasta = str_pad($mes, 2, '0', STR_PAD_LEFT) . ' - ' . $mesEscrito;

// ALTERAR AQUI CASO MUDE OS ARQUIVOS DE LUGAR
$caminhoDesktop = dir('../../Desktop');
$caminhoCompletoPasta = "{$caminhoDesktop->path}/$nomePasta";

// Verificar se o diretório não existe
if (!is_dir($caminhoCompletoPasta)) {
  mkdir($caminhoCompletoPasta);
}


// Criando o arquivo de cada dia
for ($contadorDia = 01; $contadorDia <= $qntDiasMes; $contadorDia++) {
  $numeroSemana = date('w', mktime(0, 0, 0, $mes, $contadorDia, $ano)); // 0 à 6

  // Se for domingo ou sábado
  if ($numeroSemana == 0 || $numeroSemana == 6) {
    continue;
  }

  // Transformando o número da semana para texto
  $semana = str_replace($numeroSemana, $diasDaSemana[$numeroSemana], $numeroSemana);
  $dia = str_pad($contadorDia, 2, '0', STR_PAD_LEFT); // de 1 para 01

  // Criando o arquivo
  $arquivoNome = "{$dia}-{$mes}-{$ano} {$semana}.rtf"; // 01-11-2023 QUARTA.rtf
  $arquivo = fopen("$caminhoCompletoPasta/$arquivoNome", "w+"); // Cria o arquivo na pasta que foi criada anteriormente

  $linhasTemplate = file('template.rtf');

  foreach ($linhasTemplate as $linha) {
    if (str_contains($linha, "<<[dia]>>")) {
      $linha = str_replace("<<[dia]>>", $dia, $linha);
    }
    if (str_contains($linha, "<<[mes]>>")) {
      $linha = str_replace("<<[mes]>>", $mes, $linha);
    }
    if (str_contains($linha, "<<[ano]>>")) {
      $linha = str_replace("<<[ano]>>", $ano, $linha);
    }
    if (str_contains($linha, "<<[diaSemana]>>")) {
      $linha = str_replace("<<[diaSemana]>>", utf8_decode($semana), $linha);
    }

    if ($numeroSemana == 4 && str_contains($linha, "<<[sindicancia]>>")) {
      $linha = str_replace("<<[sindicancia]>>", utf8_decode("SINDICÂNCIA"), $linha);
    } else {
      $linha = str_replace("<<[sindicancia]>>", "", $linha);
    }

    if ($numeroSemana == 4 && str_contains($linha, "<<[mensagemSindicancia]>>")) {
      $linha = str_replace("<<[mensagemSindicancia]>>", utf8_decode('* marcar de tarde próximo do dia para ver os horários que pode ou não'), $linha);
    } else {
      $linha = str_replace("<<[mensagemSindicancia]>>", "", $linha);
    }

    fwrite($arquivo, $linha);
  }


  fclose($arquivo);
  $diasSemanais++;
}

fwrite(STDOUT, "Quantidade de dias criados: $diasSemanais\n");
fwrite(STDOUT, "Pasta criada na área de trabalho\n");
fwrite(STDOUT, "--------------------------------\n");
