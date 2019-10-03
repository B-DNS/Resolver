## Развертывание

Пример для проекта с именем resolver:

```
git clone https://github.com/Sagleft/MFDNS-Resolver.git resolver
cd resolver
mkdir view/cache
chmod 777 view/cache
cp .env.example .env
cp composer.json.example composer.json
composer update
cd controller/public_html
cp example.htaccess .htaccess
```

Далее внесите изменения в .env

rpc_* параметры - на json-rpc подключение к *coind, например, MFCoin. 

Directory: ``` controller\public_html ```.
