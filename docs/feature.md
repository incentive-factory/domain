# Implémenter une feature

[Retour au sommaire](index.md)

# CQRS

Pour faciliter l'intégration de notre domaine métier, l'utilisation de CQRS est de mise, sous une forme assez pauvre mais pas inutile.

Actuellement, nous n'utilisons pas du tout le package `symfony/messenger`, car nous souhaitons rester agnostique de ce composant. 
Son intégration dans l'application consistera à paramétrer les bus et implémenter les adapteurs des différentes interfaces :
* [EventBus](../src/Shared/Event/EventBus.php)
* [CommandBus](../src/Shared/Command/CommandBus.php)
* [QueryBus](../src/Shared/Query/QueryBus.php)

Seuls 2 types d'action sont directement exécutables :
* Commande (Command)
* Requête (Query)

Les événements quant à eux, sont déclenchés généralement dans le **handler** d'une commande.

Voici quelques exemples ainsi que des tests associés :
* Commande 
  * Inscription :
    * **Dossier** : [Register](../src/Player/Register)
      * **Event** : [NewRegistration](../src/Player/Register/NewRegistration.php)
      * **Handler** : [Register](../src/Player/Register/Register.php)
      * **Command**: [Registration](../src/Player/Register/Registration.php)
    * **EventListener** : [CreateRegistrationToken](../src/Player/CreateRegistrationToken/CreateRegistrationToken.php) 
    * **Test** : [RegisterTest](../tests/Player/RegisterTest.php)
* Query 
  * Récupérer un joueur-euse en fonction de ton token d'oubli de mot de passe :
    * **Dossier** : [GetPlayerByForgottenPasswordToken](../src/Player/GetPlayerByForgottenPasswordToken)
      * **Handler** : [GetPlayerByForgottenPasswordToken](../src/Player/GetPlayerByForgottenPasswordToken/GetPlayerByForgottenPasswordToken.php)
      * **Query** : [ForgottenPasswordToken](../src/Player/GetPlayerByForgottenPasswordToken/ForgottenPasswordToken.php)
    * **Test** : [GetPlayerByForgottenPasswordTokenTest](../tests/Player/GetPlayerByForgottenPasswordTokenTest.php)
