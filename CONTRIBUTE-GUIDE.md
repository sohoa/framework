Guide de contribution
======

Nous avons adopté le mode de fonctionnement par Pull Request
adapté a notre hébergeur [Github](http://github.com/)

Dans ce guide nous allons détaillé pas à pas toutes les étapes pour contribuer.
Nous partons du principe que vous savez utiliser Github, et que vous avez fait
un fork du [dépot](http://github.com/sohoa/framework) concerné et que vous
travaillé dans le clone de votre fork ie git clone http://github.com/<votre_username>/framework && cd framework`


Important
=====

Ne jamais travailler sur la branche `master` est votre devoir travaillé sur votre branche est votre droit.

Ajout des remotes
-----

Par défaut le remote `origin` pointe sur votre dépot github (http://github.com/<votre_username>/framework).
Nous allons ajouter un remote vers le dépot sohoa/sohoa afin de pouvoir se synchroniser et récuperer les différentes 
modifications apportées sur le dépot durant votre developpement.

```
git remote add sohoa https://github.com/sohoa/framework
```

Mise à jour de votre dépot avec les dernières modification
-----

```
git pull sohoa master
```

Cette commande peut engendrer des conflits si jamais vous n'avez pas suivi ce guide

Contributions
=====

Mon premier jet de code
-----

Pour des raisons de pratiques j'ai adopté la nomination des branches suivantes `f/<ma_feature>` dans le cadre
d'une nouvelle feature et `b/<mon_bugfix>` pour la résolution d'un bug, mais ce n'est qu'une pratique et non pas une obligation

```
git checkout -b <f/maNouvelleSuperFeature>
// Modify your code for your next feature
git commit -a -m "My first feature"
//Modify
git commit -a -m "fix an little bug"
//Modify
git commit -a -m "Be compatible with PSR"
//...

git push origin <f/maNouvelleSuperFeature>
```

Dans l'interface de github quand vous pensez votre feature prête vous la poussez, ainsi le serveur 
d'intégration continue en sera informé et jouera les tests (unitaires et de conformité a PSR) automatiquement
et le rapport apparaitra sous peu (~ 5-10 minutes suivant la disponibilité du robot) dans votre PSR

Discussion
-----

Avant de merger la PR probablement nous en discuterons en interne (sur IRC et/ou sur la ML de sohoa), des précisions
peuvent être demandées, et / ou des compléments de code pour cela la marche à suivre est celle du paragraphe §Mon premier jet de code.


Acceptation
=====

Dans le cas de la validation de la PR nous serons amenez a effectuer une ultime manipulation sur la PR.
En effet nous nous basons sur cette [page](http://github.com/sohoa/framework/network) et votre PR (Branche) doit correspondre à deux critères
*	Avoir comme commit parent le dernier commit
*	Avoir qu'un seul et unique commit

Mettre à jour notre branche
-----

Nous allons mettre à jour notre banch avec les données contenues dans master

```
git checkout master
git pull sohoa master
git log  --abbrev-commit -n 1
```
On obtient le <sha1> du dernier commit

``` 
git checkout <f/maNouvelleSuperFeature>
git rebase <sha1>
```

On peut obtenir des conflits que l'on doit résoudre et faire des commits comme vu dans le  §Mon premier jet de code


Un seul commit
-----

Nous allons effectuer un rebase intéractif sur notre branch.
Pour cela nous allons suivre un exemple :

```
git log --abbrev-commit
git rebase -i <sha1>
```

##### Rebase Interactif


Edit , Fixup ...





