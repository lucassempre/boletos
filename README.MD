Este teste teve como objetivo criar uma aplicação que executa a importação de dados a partir de arquivos CSV.

Foi adotada a arquitetura hexagonal para garantir legibilidade e modularização do código. A aplicação processa o CSV em pequenos pedaços, importando-os para serem salvos no banco de dados de forma fragmentada.

Há também um fluxo de jobs que realiza a validação do arquivo e, posteriormente, a inserção de todos os boletos. A geração simbólica dos arquivos e seu envio são realizados em uma etapa posterior. A utilização de jobs permite consistência e confiabilidade no processo de importação de CSV, sendo também ideal para enviar dados para o provedor de e-mail e para a geração de arquivos.



## Como Executar o Projeto

1. Clone o repositório:
   ```bash
   git clone https://github.com/lucassempre/boletos
   ```

2. Edite o env.template de acordo com suas necessidades e renomeie-o para .env:
    ```bash
    cp env.template .env
    ```

3. Execute o Docker em modo de escala para que a importação atenda o tempo esperado:
    ```bash
    docker compose up -scale app-queue 20
   ```

4. Execute o Docker:
    ```bash
    docker compose -it app php artisan migrate
    ```

5Aguarde a inicialização. Sua aplicação estará pronta para uso. Caso queira realizar os testes, execute o seguinte comando após a aplicação estar funcionando:
    ```bash
    docker exec -it app php artistan test -v 2
    ```

O fluxo da aplicação ocorre da seguinte forma:
1. Upload 
```
curl --location 'http://143.110.151.5/api/api/files/upload' \
--form 'file=@"/path/input.csv"'
```
2. Executar o processamento
```
curl --location --request POST 'http://143.110.151.5/api/processar/8601f337-9b7d-44c3-9dec-8a3dc6379c64
```


A aplicação também se encontra em execução no seguinte link:
```
http://143.110.151.5/api/
```
Sua documentação interativa:
```
http://143.110.151.5/docs
```