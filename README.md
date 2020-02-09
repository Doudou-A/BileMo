# BileMo

BileMo est une plateforme voulant vendre des téléphones indectement à l'aide d'utilisateur. Cette entreprise a mis en place une plateforme permettant de suivre le catalogue des téléphones. De plus, elle permet de gérer des clients pour les utilisateurs souhaitant vendrent les téléphones de BileMo.

# Installation 

Afin d'utiliser le projet, copier le git à l'aide de la commande "git clone https://github.com/Doudou-A/BileMo.git" ainsi que composer à l'aide de "composer install".

# Fixture 

Vous pouvez avoir une fausse base de donné de plusieurs centaines de téléphones, clients et quelques utilisateurs à l'aide de la commande "php bin/console doctrine:fixtures:load".

# Utilisation

Pour pouvoir se connecter en tant qu'administrateur il suffit d'envoyer une requête [POST] à l'URL :login
"
{
  username = 'admin',
  password = 'password'
}
"

# Documentation

Pour accéder à la documentation en JSON il suffit de se rendre sur le lien : 
"https://localhost:8000/api/doc.json"
ou en html à l'aide d'un navigateur : 
"https://localhost:8000/api/doc"
