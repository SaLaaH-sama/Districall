# Task Manager

## Instructions pour lancer l'application

1. Clonez le dépôt :
    ```sh
    git clone https://github.com/SaLaaH-sama/Districall
    cd task-manager
    ```

2. Installez les dépendances :
    ```sh
    composer install
    ```

3. Configurez les variables d'environnement :
    ```sh
    cp .env .env.local
    ```

4. Démarrez le serveur :
    ```sh
    symfony server:start
    ```

5. Accédez à l'application dans votre navigateur :
    ```
    http://localhost:8000
    ```

## Instructions pour exécuter les tests

1. Assurez-vous que les dépendances de développement sont installées :
    ```sh
    composer install --dev
    ```

2. Exécutez les tests :
    ```sh
    ./vendor/bin/phpunit
    ```

## Explication rapide des choix techniques

- **Symfony Framework** : Utilisé pour sa robustesse et sa flexibilité dans le développement d'applications web.
- **Doctrine ORM** : Utilisé pour la gestion des bases de données, permettant une interaction facile avec les entités.
- **API Platform** : Utilisé pour créer des API RESTful et GraphQL rapidement et efficacement.
- **Twig** : Utilisé comme moteur de templates pour séparer la logique de présentation de la logique métier.
