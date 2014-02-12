Guide de contribution
======

Nous avons adopté le mode de fonctionnement par Pull Request
adapté a notre hébergeur [Github](http://github.com/)

Dans ce guide nous allons détaillé pas à pas toutes les étapes pour contribuer.
Nous partons du principe que vous savez utiliser Github, et que vous avez fait
un fork du [dépot](http://github.com/sohoa/framework) concerné et que vous
travaillé dans le clone de votre fork ie `git clone http://github.com/votre_username/framework && cd framework`


Important
=====

Ne jamais travailler sur la branche `master` est votre devoir, travailler sur votre branche est votre droit.

Ajout des remotes
-----

Par défaut le remote `origin` pointe sur votre dépot github (http://github.com/votre_username/framework).
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

Cette commande peut engendrer des conflits, il faut bien veillez à les résoudres , mais en toutes logiques vous DEVEZ PAS avoir de conflit sur `master`

Contributions
=====

Mon premier jet de code
-----

Pour des raisons de pratiques j'ai adopté la nomination des branches suivantes `f/ma_feature` dans le cadre
d'une nouvelle feature et `b/mon_bugfix` pour la résolution d'un bug, mais ce n'est qu'une pratique et non pas une obligation

```
git checkout -b f/maNouvelleSuperFeature

// Il est conseillé de faire git pull sohoa master de temps en temps notamment avant de soumettre la PR
// (sur la branche master **et** sur votre branche f/maNouvelleSuperFeature)
// Modify your code for your next feature
git commit -a -m "My first feature"
//Modify
git commit -a -m "fix an little bug"
//Modify
git commit -a -m "Be compatible with PSR"
//...

git push origin f/maNouvelleSuperFeature
```

Dans l'interface de github quand vous pensez votre feature prête vous la poussez, ainsi le serveur 
d'intégration continue en sera informé et jouera les tests (unitaires et de conformité a PSR) automatiquement
et le rapport apparaitra sous peu (~ 5-10 minutes suivant la disponibilité du robot) dans votre PR

Discussion
-----

Avant de merger la PR probablement nous en discuterons en interne (sur IRC et/ou sur la ML de sohoa et/ou dans les commentaires), des précisions
peuvent être demandées, et/ou des compléments de code pour cela la marche à suivre est :


```
git checkout f/maNouvelleSuperFeature // Pour revenir sur notre branche en cas qu'on en soit sortit

// Modify your code for your next feature
git commit -a -m "My bugfix from the discussion"
git push origin <f/maNouvelleSuperFeature>
```

Pas besoin de republier votre PR , elle est automatiquement mise à jour dans l'interface de Github, et les tests sont rejoués automatiquement
(à la condition de leur laissé le temps de se lancé :D), pensez à nous le notifié par le biais d'un petit commentaire histoire que l'on regarde

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
git log  -n 1 --pretty=oneline
```
On obtient le `<sha1>` du dernier commit

``` 
git checkout <f/maNouvelleSuperFeature>
git pull sohoa master
git rebase <sha1>
```

On peut obtenir des conflits que l'on doit résoudre et faire des commits comme vu dans le  §Mon premier jet de code


Un seul commit
-----

Nous allons effectuer un rebase intéractif sur notre branch.
Pour cela nous allons suivre un exemple :

Dans notre exemple nous avons 4 commits au dessus de notre commit parent
donc on veut obtenir le commit parent comme on le vois ![Sohoa network](http://imageshack.com/a/img401/1120/dh4k.png)

Le commit parent (le point noir) à le hash : `7c09fca1793b3015f26ebdbbb8b53bb373a233f3`
Nous allons donc rebase à partir de celui là.


##### Rebase Interactif

`git log --graph --pretty=format:'%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset' --abbrev-commit --date=relative`
On obtiens alors le screenshot

![Sohoa glog view](http://imageshack.com/a/img839/7861/62b1.png)

`git rebase -i 7c09fca1793b3015f26ebdbbb8b53bb373a233f3 ` ou `git rebase -i 7c09fca`

On obtiens dans notre éditeur (Vim , Nano , …)

![Sohoa rebase in VIM](http://imageshack.com/a/img833/9886/si18.png)

 
 ```
pick f559e50 Enable real DI and not DIC
pick b9e7e85 Continue
pick f373c6f Finish TU fix
pick 1963fb4 Remove & change the private properties

# Rebase 7c09fca..1963fb4 onto 7c09fca
#
# Commands:
#  p, pick = use commit
#  r, reword = use commit, but edit the commit message
#  e, edit = use commit, but stop for amending
#  s, squash = use commit, but meld into previous commit
#  f, fixup = like "squash", but discard this commit's log message
#  x, exec = run command (the rest of the line) using shell
#
# These lines can be re-ordered; they are executed from top to bottom.
#
# If you remove a line here THAT COMMIT WILL BE LOST.
# However, if you remove everything, the rebase will be aborted.
#
# Note that empty commits are commented out
```

Suivant votre utilisation vous devrez utiliser différente options dans le cadre de l'exemple nous allons fusionner les commits (sans garder les messages de commit)
b9e7e85, f373c6f, 1963fb4 dans le commit f559e50 et éditer le message du commit

Nous aurons alors 


```
e f559e50 Enable real DI and not DIC
f b9e7e85 Continue
f f373c6f Finish TU fix
f 1963fb4 Remove & change the private properties
```
or

```
edit f559e50 Enable real DI and not DIC
fixup b9e7e85 Continue
fixup f373c6f Finish TU fix
fixup 1963fb4 Remove & change the private properties
```

Le rebase va commencer et s'arreter pour editer le premier commit , nous pourrons ainsi modifier les fichiers , le message de commit
donc j'ai juste à lancé la commande `git commit --amend` et je vais pouvoir éditer le commit message et à fermer le logiciel d'éditions
il nous reste juste à continuer le rebase avec un `git rebase --continue`

Après quelques secondes (ne pas arreter le processus), il se peut que vous ayez des conflict , il suffit de lire la sortie de git qui va tous vous exliquer
En l'occurence voici les étapes simplifiée car vraiment spécifique à chaque cas.

1. Editer le/les fichiers en cause et lever les conflits (ce ne sont que des annotations text) avec votre éditeur préféré.
2. faite un `git add path/to/File.php`
3. Renouvellez pour tous les fichiers en conflits
4. Faites un commit pour sauvegarder toutes les résolutions de conflit

Et recommencer l'étape "Un seul commit"


Allez courage on est à la fin :)

il nous reste plus qu'a pusher nos modifications, comme nous avons toucher à l'arbre il faut utiliser l'option `--force` sinon l'hébergeur le refusera
donc en toute logique on execute cette commande `git push origin <f/maNouvelleSuperFeature>`

