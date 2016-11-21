# quiosgrama-controlpanel
Painel de controle do Quiosgrama em PHP para cadastro e gerenciamentos dos itens do aplicativo. Esse projeto também possui os Web Services utilizado para se comunicar com o aplicativo.

Siga as etapas abaixo para configurar o sistema no Ubuntu server:
 
1. Instalar o LAMP no servidor juntamente com o módulo php-curl (sudo apt-get install php-curl).

2. Baixar o projeto em **/var/www/html**

3. Instalar o composer, para gerenciar dependência do php, seguir os comandos da página de referência:
>https://getcomposer.org/download/
  
4. Na pasta onde executou os comandos da página acima, executar o seguinte comando: 
>sudo mv composer.phar /usr/local/bin/composer
  
5. Executar no diretório **/var/www/html/quiosgrama** o seguinte comando:
>sudo composer install

6. Criar o banco de dados, o dump do banco se encontra nesse projeto na pasta Dumps

7. Verifique o horário do servidor se esta correto, isso influencia na sincronização

8. Criar o arquivo **myenvvars.sh** no diretório **/etc/profile.d** com os seguintes valores:
>export QUIOSGRAMA_SYSTEM_PATH=/var/www/html/quiosgrama/  
export QUIOSGRAMA_SOCKET_ADDRESS=127.0.0.1  
export QUIOSGRAMA_SOCKET_PORT=9000

  Depois rodar o mesmo comando de export acima no linux para não precisar reiniciar o server.
  
9. Inserir os seguintes valores no arquivo de variáveis de ambiente do Apache, normalmente o arquivo se encontra no arquivo **/etc/apache2/envvars**
>export QUIOSGRAMA_SYSTEM_PATH=/var/www/html/quiosgrama/  
export QUIOSGRAMA_SOCKET_ADDRESS=127.0.0.1  
export QUIOSGRAMA_SOCKET_PORT=9000
  
10. Habilite o módulo rewrite, executando no console o seguinte comando:
>sudo a2enmod rewrite
  
11. Insira a seguinte linha no arquivo ports.conf (normalmente localizado em **/etc/apache2/ports.conf**):
>Listen 9090
  
12. Insira o seguinte bloco no arquivo sites-enabled/000-default.conf (normalmente localizado em **/etc/apache2/sites-enabled/000-default.conf**):  
>\<VirtualHost *:9090> <br/>
       DocumentRoot  /var/www/html/quiosgrama <br/>
\</VirtualHost><br/>

13. Conferir se o arquivo **/etc/apache2/apache2.conf** tem o seguinte trecho:
>\<Directory /var/www/><br/>
        Options Indexes FollowSymLinks MultiViews<br/>
        AllowOverride All<br/>
        Require all granted<br/>
\</Directory>

14. Reinicie o apache:
>sudo service apache2 restart




