Guide de contribution
======

Nous avons adopté le mode de fonctionnement par Pull Request
adapté à notre gestionnaire de version  [Git](http://git-scm.com/) et favorisé par notre hébergeur
[Github](http://github.com/)

Dans ce guide nous allons détailler pas à pas toutes les étapes pour contribuer.
Nous partons du principe que vous savez utiliser Github, que vous avez fait
un fork du [dépôt](http://github.com/sohoa/framework) concerné et que vous
travaillez dans le clone de votre fork ie `git clone http://github.com/votre_username/framework && cd framework`


Important
=====

Ne jamais travailler sur la branche `master` est votre devoir, travailler sur votre branche est votre droit.

Ajout des remotes
-----

Par défaut le remote `origin` pointe sur votre dépôt Github (http://github.com/votre_username/framework).
Nous allons ajouter un remote vers le dépôt sohoa/framework afin de pouvoir se synchroniser et récupérer les différentes 
modifications apportées sur le dépôt par d'autres contributeurs.

```
git remote add sohoa https://github.com/sohoa/framework
```

Mise à jour de votre dépôt avec les dernières modifications
-----

```
git pull sohoa master
```

Cette commande peut engendrer des conflits qu'il faut bien veiller à résoudre. Ces conflits ne surviennent que lorsque vous avez modifié	 la branche master de votre clone (ce qui est interdit cf §Important).
En toute logique vous **NE DEVEZ PAS** avoir de conflit sur master.

Contributions
=====

Mon premier jet de code
-----

Pour plus de clarté vous devriez utiliser la convention suivante:
*	`f/ma_feature` Pour une propositon de feature.
*	`b/mon_bug_fix` Pour une proposition de bugfix.


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

Dans l'interface de Github quand vous pensez que votre feature est prête, vous la poussez. 
Ainsi le serveur d'intégration continue en sera informé et jouera les tests (unitaires et de conformité a PSR) automatiquement
et le rapport apparaitra sous peu (~ 5-10 minutes suivant la disponibilité du robot) dans votre PR.

Discussion
-----

Avant de merger (= accepter) la PR, nous en discuterons en interne (sur IRC et/ou sur la ML de sohoa et/ou dans les commentaires),
nous serons sûrement amenés à demander des précisions et/ou des compléments de code. Pour cela la marche à suivre est :


```
git checkout f/maNouvelleSuperFeature // Pour revenir sur notre branche en cas qu'on en soit sorti

// Modify your code for your next feature
git commit -a -m "My bugfix from the discussion"
git push origin f/maNouvelleSuperFeature
```

Pas besoin de republier votre PR , elle est automatiquement mise à jour dans l'interface de Github, et les tests sont rejoués automatiquement
à condition de leur laisser le temps de se lancer … pensez à nous en informer par le biais d'un court commentaire histoire que nous regardions	

Acceptation
=====

Dans le cas de la validation de la PR nous serons amenés à effectuer une ultime manipulation sur la PR.
En effet nous nous basons sur cette [page](http://github.com/sohoa/framework/network) et votre PR (branche) doit correspondre à deux critères
*	Avoir comme commit parent le dernier commit
*	Avoir qu'un seul et unique commit

Mettre à jour votre branche
-----

Nous allons mettre à jour votre banche avec les données contenues dans master

```
git checkout master
git pull sohoa master
git log  -n 1 --pretty=oneline
```
On obtient le `sha1` du dernier commit

``` 
git checkout f/maNouvelleSuperFeature
git pull sohoa master
git rebase sha1
```

On peut obtenir des conflits que l'on doit résoudre et faire des commits comme vu dans le  §Mon premier jet de code


Un seul commit
-----

Nous allons effectuer un rebase interactif sur notre branch.
Pour cela nous allons suivre un exemple :

Dans notre exemple nous avons 4 commits en avant de notre commit parent
donc on veut obtenir le commit parent comme on le voit ici:

![Sohoa network](http://imageshack.com/a/img401/1120/dh4k.png)

Le commit parent (le point noir) a le hash : `7c09fca1793b3015f26ebdbbb8b53bb373a233f3`
Nous allons donc rebase à partir de celui-ci.


##### Rebase Interactif

`git log --graph --pretty=format:'%h - %d %s %cr <%an>' --abbrev-commit --date=relative`
On obtient alors le screenshot :

![Sohoa glog view](http://imageshack.com/a/img839/7861/62b1.png)

`git rebase -i 7c09fca1793b3015f26ebdbbb8b53bb373a233f3 ` ou `git rebase -i 7c09fca`

On obtient dans notre éditeur :

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

Suivant votre utilisation vous devrez utiliser différentes options. Dans le cadre de l'exemple nous allons fusionner les commits (sans garder les messages de commit)
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

Le rebase va commencer et s'arrêter pour éditer le premier commit. Nous pourrons ainsi modifier les fichiers , le message de commit
donc j'ai juste à lancé la commande `git commit --amend` et je vais pouvoir éditer le message de commit.
il nous reste juste à continuer le rebase avec un `git rebase --continue`

Après quelques secondes (ne pas arrêter le processus), il se peut que vous ayez des conflits, il suffit de lire les messages de git qui va tout vous expliquer
En l'occurence voici les étapes simplifiées, chaque cas étant très spécifique :

1. Editer le/les fichiers en cause et lever les conflits (ce ne sont que des annotations texte) avec votre éditeur préféré.
2. faite un `git add path/to/File.php`
3. Renouvellez pour tous les fichiers en conflit
4. git commit --amend // Pour mettre toutes les modifications courantes dans le dernier commit
5. git rebase --continue // Pour reprendre le rebase


Allez courage on est à la fin :)

il ne nous reste plus qu'à pusher nos modifications, comme nous avons touché à l'arbre il faut utiliser l'option `--force` sinon l'hébergeur le refusera
donc en toute logique on exécute cette commande `git push --force origin f/maNouvelleSuperFeature`

