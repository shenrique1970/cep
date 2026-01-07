import 'dart:convert';
import 'dart:io';

Future<void> main() async {
  stdout.write('Digite o CEP (ex: 01001-000): ');
  final cepInput = stdin.readLineSync();

  if (cepInput == null || cepInput.isEmpty) {
    print('CEP inválido.');
    return;
  }

  final cep = cepInput.replaceAll(RegExp(r'[^0-9]'), '');
  final uri = Uri.parse('https://viacep.com.br/ws/$cep/json/');

  final client = HttpClient();
  try {
    final request = await client.getUrl(uri);
    final response = await request.close();

    if (response.statusCode == 200) {
      final body = await response.transform(utf8.decoder).join();
      final data = json.decode(body);

      if (data['erro'] == true) {
        print('CEP não encontrado.');
        return;
      }

      print('\nInformações do CEP:');
      print('Logradouro: ${data['logradouro']}');
      print('Bairro: ${data['bairro']}');
      print('Cidade: ${data['localidade']}');
      print('Estado: ${data['uf']}');

      stdout.write('\nDigite o número: ');
      final numero = stdin.readLineSync();

      stdout.write('Digite o complemento: ');
      final complemento = stdin.readLineSync();

      print('\nEndereço completo:');
      print('${data['logradouro']}, Nº ${numero ?? ''}');
      if (complemento != null && complemento.isNotEmpty) {
        print('Complemento: $complemento');
      }
      print('${data['bairro']} - ${data['localidade']}/${data['uf']}');
      print('CEP: $cep');
    } else {
      print('Erro ao consultar o CEP: ${response.statusCode}');
    }
  } catch (e) {
    print('Erro: $e');
  } finally {
    client.close();
  }
}
