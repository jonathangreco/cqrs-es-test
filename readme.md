# Install
``
    make up
    make rbash api-danim
    composer install
``

update /etc/hosts on your host to add 
``
#danim
127.0.0.1 www.danim.local
``
Visit : http://www.danim.local/

It should show the SF7 welcome page !

# Explique un peu le projet et surtout tes choix

A la différence de mes idées de départ, j'ai décidé après avoir visionné les vidéos de Thomas Brouillet et Clément Helliou de partir from scratch pour mieux comprendre
cette architecture. Ma seule expérience de l'ES et CQRS se basait sur Broadway et Prooph qui sont déjà bien magique.
En partant de zéro, j'allais rencontrer des problématiques à résoudre qui m'aiderait à mieux comprendre comment ça marche sous le capot.

Les concepts explorés après 6heures de travail :
- Le commandBus avec gestion Command / Handler
- L'architecture générale d'un système CQRS-ES avec une archi hexagonal qui va de l'extérieur vers l'intérieur (infrastructure > application > domain)
- La création d'un eventStore
- Rejouer les événements
- Les EventStreams (liste d'événements dans redis (getEvents))

Les raccourcis pris après 6 heures de travail :
- Le QueryBus
- Pas de projection
- Pas de lock (semaphore ou autre systeme)
- Travail directement en CLI, avec des ID en paramètre

# How to test the project

### By following these commands :
Il faut aller dans le container : 
`make rbash api-danim`

Et ensuite executer les commandes (safe)
```
    php bin/console app:coupon:create 1 10
```
Ou `1` = id du coupon et `10` le montant à appliquer (fixe pour l'instant, le % est ignoré par manque de temps mais faisable en rajoutant une variable type au coupon)

```
    php bin/console app:coupon:apply 1
```
Ou `1` = id du coupon
Vous ne pouvez appliquer un coupon s'il est revoqué
Vous ne pouvez appliquer un coupon s'il as déjà été consommé 10 fois.

```
    php bin/console app:coupon:revoke 1
```
Ou `1` = id du coupon 
Si un coupon est déjà revoqué, il ne sera pas révoqué une seconde fois.
La règle de réactivation est implicite, puisque l'application ferme les actions sur un coupon révoqué.

