## Sistema de Gerenciamento de Prospostas - 4UPLAY - Laravel v5.8
Usado com base no https://itsolutionstuff.com/post/laravel-58-user-roles-and-permissions-tutorialexample.html

Colocando em modo de manutenção:
 php artisan down --allow=127.0.0.1 --allow=meu_ip_externo --message="Em Manutenção"
Subir novamente:
 php artisan up

php artisan db:seed --class=IntegradorSeeder
<!-- php artisan db:seed --class=PermissionTableSeeder
php artisan db:seed --class=CreateAdminUserSeeder -->
