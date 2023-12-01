# Teste API em Laravel
## Como acessar a API pela primeira vez

 1. Crie um usuário no sistema, utilizando a interface do Laravel
 2. Envie um POST para sua URL + "/api/generate_token", finalizando com seu e-mail e senha. Ex: `127.0.0.1:8000/api/generate_token?email=userteste@gmail.com&password=12345678`
 3. Será retornado um Token. Ex: `{"token": "1|tLpWmmTvwx0ljGpvmvCLZI6Om78wW66WrINCEijo1fa49073"}`
 4. Inclua esse Token como um Bearer Auth nas próximas requisições.
 
 ## Como criar registros
 Basta enviar um POST para sua URL + "api/service_orders", com os parâmetros da Ordem de Serviço. Exemplo: 
> {
"vehiclePlate": "ABC1234",
"entryDateTime": "2023-10-31 12:34:06",
"exitDateTime": "2023-11-01 15:48:18",
"priceType": "Padrão",
"price": 124.65
}

## Como listar os registros
Envie um GET para sua URL + /api/service_orders?page= + página. 
### Como filtrar os valores
Insira o campo "filters" como uma lista no body da requisição. Dentro dele, coloque cada filtro em forma de uma lista com 3 valores, sendo esses:

 1. Campo que deseja filtrar
 2. Operador de comparação
 3. Valor que deseja comparar

Os filtros deverão ficar mais ou menos dessa forma: 

> {
"filters": 
[
["entryDateTime", "<", "2023-01-01 00:00:00"],
["price", ">", 50]
]
}
