#!/bin/sh
app_dir='/Users/fagathe/workspace/perso/ged'
app_host='dev.ged.agathefrederick.fr'
port='5500'
db_driver='mysql'
# enregistrer le nouveau nom de domaine dans le host de la machine
# echo "127.0.0.1\t${app_host}" | sudo tee -a /etc/hosts

# lance le service mysql
brew services start $db_driver
cd $app_dir
echo 'cd app dir'
echo 'ouvrir le projet sur vscode'
code .
while getopts 'i:b' options; do
    case $options in 
        i) 
            bin/console d:d:d -f 
            bin/console d:d:c -n
            echo "Reset migrations"
            rm migrations/*
            bin/console m:migration 
            bin/console d:m:m -n
            bin/console d:f:l -n 
            bin/console c:c -n
            ;;
        b) 
            echo "open http://${app_host}:${port} in browser"
            open http://$app_host:$port
            ;;
    esac
done
            
# lance le serveur interne de php
php -S $app_host:$port -t public

# stop le service mysql lorsqu'on stop le script
trap "c ${db_driver}" EXIT
