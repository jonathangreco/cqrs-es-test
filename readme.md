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

Les concepts explorés après 6-8 heures de travail :
- Le commandBus avec gestion Command / Handler
- L'architecture générale d'un système CQRS-ES avec une archi hexagonal qui va de l'extérieur vers l'intérieur (infrastructure > application > domain)
- La création d'un eventStore
- Rejouer les événements
- Les EventStreams (liste d'événements dans redis (getEvents))

Les raccourcis pris après 6-8 heures de travail :
- Le QueryBus à été ignoré
- Pas de projection
- Pas de lock (semaphore ou autre système)
- Travail directement en CLI, avec des ID en paramètre
- J'ai utilisé des ValueObject pour faire des assertions / contrôles plutôt que de passer par un système d'assertion
- Pas de projection (encore ! j'aurais aimé en faire)
- Pas de test unitaires

# Y a t'il de la dette technique ?
- Oui je n'ai par exemple pas nettoyé les getters / setter inutile
- Je n'ai pas opéré de relecture complète du code pour vérifier des variables non utilisé ou autre joyeusetés.


# How to test the project

### By following these commands :
Il faut aller dans le container : 
`make rbash api-danim`

Et ensuite executer les commandes (safe)
```
    php bin/console app:coupon:create 1 10
```
Ou `1` = id du coupon et `10` le montant à appliquer (fixe pour l'instant, le % est ignoré par manque de temps mais faisable en rajoutant une variable type au coupon)

Création d'un basket
```
    php bin/console app:basket:create 1 20
```
Ou `1` = id du basket
Ou `20` = prix du basket (qui ne passera pas le test du panier > 50)

```
    php bin/console app:coupon:apply 1 2
```
Ou `1` = id du coupon
Ou `2` = id du basket
Vous ne pouvez appliquer un coupon s'il est revoqué
Vous ne pouvez appliquer un coupon s'il a déjà été consommé 10 fois.
Vous ne pouvez appliquer un coupon sur un panier qui en possède déjà un

```
    php bin/console app:coupon:revoke 1
```
Ou `1` = id du coupon 
Si un coupon est déjà revoqué, il ne sera pas révoqué une seconde fois.
La règle de réactivation est implicite, puisque l'application ferme les actions sur un coupon révoqué.

