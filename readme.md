## Sistema de Gerenciamento de Totens - 4UPLAY - Laravel v5.8

Colocando em modo de manutenção:
 php artisan down --allow=127.0.0.1 --allow=meu_ip_externo --message="Em Manutenção"
Subir novamente:
 php artisan up

Rodar o Seeder personalizado para subir os usuários e permissões:
php artisan db:seed --class=IntegradorSeeder

