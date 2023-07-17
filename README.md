# Symfoy ORM
Un projet symfony utilisant l'ORM Doctrine pour gérer la couche data (SQL+repositories)

## How To Use
1. Cloner le projet
2. Faire un `composer install`
3. Créer un fichier `.env.local` et dedans mettre la variable de connexion. Par exemple : `DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name?serverVersion=8.0.32&charset=utf8mb4"`
4. Exécuter un `php bin/console do:da:cr` pour créer la base de données
5. Exécuter un `php bin/console do:mi:mi` pour exécuter les fichiers de migrations et créer les tables

________________________________________________________
## Projet Symfony avec ORM

1. Créer un nouveau projet symfony --webapp qu'on va appeler symfony-orm
	
2. A la racine du projet, créer un fichier .env.local et mettre dedans DATABASE_URL="mysql://root:1234@127.0.0.1:3306/symfony_orm?serverVersion=8.0.32&charset=utf8mb4" en modifiant le root, le 1234 et éventullement le 3306 par les informations de votre mysql
	
3. Dans un terminal, exécuter un php bin/console doctrine:database:create   ou bien do:da:cr pour créer la database (si votre variable d'environnement n'est pas bonne, vous aurez une erreur ici déjà)
	

4. Une fois la base de données créer, on va créer la première entité Student en utilisant également la console de symfony avec un php bin/console make:entity Student   ou bien ma:en Student
	
	
5. A partir de là, la console va vous demander ce que vous souhaitez comme propriété dans cette entité et leur type, on va faire que le student ait un name en string, une birthdate en date et une address en string. Une fois la dernière column et son type spécifié, vous pouvez faire entrée pour valider la création et aller voir dans le dossier Entity à quoi ressemble l'entité générée

6. Exécuter un php bin/console make:migration  ou bien ma:mi suivi d'un php bin/console doctrine:migrations:migrate ou do:mi:mi . Ces deux commandes devraient vous générer la table student dans votre bdd

________________________________________________
### Pour les personnes sur mariadb, la variable d'environnement doit plutôt ressembler à ça :

DATABASE_URL="mysql://root:1234@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
_________________________________________________

### Le contrôleur

1. Une fois qu'on a notre entity, un StudentRepository a été créé automatiquement avec, on peut donc directement passer au controller qu'on génère avec un ma:con Student
	
2. Dans ce contrôleur, on injecte le StudentRepository via le construct comme on faisait avec nos propres repositories, et essayer de faire une route /api/student en GET pour récupérer tous les user et un /api/student en POST pour en ajouter un, en reprenant presqu'exactement le code qu'on avait déjà fait pour n'importe quel autre contrôleur
	
3. Pour le persist, rajouter dans les argument un EntityManagerInterface $em et faire un $em->persist($student) suivi d'un $em->flush()
_______________________________________________

### Faire l'update et le remove du student

1. Créer une route /api/student/{id} en DELETE. Dans les paramètres de la route, mettre un argument de type Student. Utiliser le remove() de l'entity manager et un flush, pour supprimer le student
	
2. Créer une route /api/student/{id} en PATCH. Mettre un Student en paramètre de la méthode puis faire la même chose que ce qu'on faisait pour nos autres PATCH, à la différence que pour faire persister les modification on aura juste à exécuter un flush (il 'y a de toute façon pas de méthode update)


Bonus: Utiliser le findBy pour rajouter de la pagination

### L'entité Note

1. Créer une nouvelle entité Note qui aura un createdAt en DateTime, une review en text, un attachmentLink en string et une relation avec l'entité Student pour faire qu'un Student ait plusieurs Notes et une Note soit liée à un Student
	
2. Faire les migrations
	
3. Créer un NoteController avec juste un findAll et un persist

_________________________________________
## Créer le POST pour la note comme on le ferait habituellement : 

1. Une manière de gérer la relation peut être de rajouter un /{id} à la route POST et un paramètre Student dans la fonction, qu'on assignera à la note avant de faire le persist
	
2. Faire également en sorte d'assigner la date de maintenant au createdAt de la note avant de la faire persister

### commandes à exécuter pour repartir d'un truc propre si jamais vous vous êtes plantés sur les migrations (par exemple quand on fait un do:mi:mi et qu'il nous dit "table already exists") : 

Supprimer tous les fichiers de migrations dans le dossier migrations (les trucs qui commencent par Version...) puis exécuter dans cet ordre

php bin/console do:da:dr --force

php bin/console do:da:cr

php bin/console ma:mi

php bin/console do:mi:mi
____________________________________________
### Un peu de API Platform

1. Faire un composer req api cors
	
2. Rajouter un #[ApiResource] sur nos 2 entités et supprimer nos 2 contrôleurs pour qu'il n'y ait pas de conflits avec ceux d'api-platform. On peut aussi retirer le #[Ignore] sur le OneToMany du Student
	
3. Aller sur http://localhost:8000/api pour voir la liste des routes générées, et tenter de faire un GET all et un POST pour voir si ça marche toujours, pour le student et ensuite pour la note
	
4. Créer une nouvelle entité Promo qui aura un name, un cursus, un startDate et un endDate (les deux en date), et une relation ManyToMany avec Student (faire en sorte que ça soit une ApiResource si c'est pas fait par défaut)