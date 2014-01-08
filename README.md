sohoa
=====

PHP framework based on Hoa


Convention de codage / PSR1-2 / Git hook:
------

Sohoa/Framework respecte PSR1 et PSR2 comme convention de codage.
Pour faciliter le travail nous utilisons l'outil [PHP-CS-Fixer](https://github.com/fabpot/PHP-CS-Fixer) permet de vérifier et corriger automatiquement le code qui ne respecte pas les standards PSR1 et 2.
Avec la ligne de commande suivante :
```
php /path/to/php-cs-fixer.php fix /path/to/the/framework
```

On peut également ajouter cette vérification en hook de pre-commit pour Git. Il faut pour cela ajouter dans le dossier .git du dépôt local dans le fichier `pre-commit` une grande partie du code a été trouvé chez [LilaConcepts](https://github.com/LilaConcepts/LilaConceptsBestPracticeBundle)

##### Simple vérification thanks to [lilaconcepts](https://raw.github.com/LilaConcepts/LilaConceptsBestPracticeBundle/master/hooks/pre-commit-cs-fixer)
```
#!/bin/sh

PROJECTROOT=`echo $(cd ${0%/*}/../../ && pwd -P)`/
FIXER=php-cs-fixer.phar

if [ ! -e ${PROJECTROOT}${FIXER} ]; then
	echo "PHP-CS-Fixer not available, downloading to ${PROJECTROOT}${FIXER}..."
	curl -s http://cs.sensiolabs.org/get/$FIXER > ${PROJECTROOT}${FIXER}
	echo "Done. First time to check the Coding Standards."
	echo ""
fi

RES=`php ${PROJECTROOT}${FIXER} fix $PROJECTROOT --verbose --dry-run`
if [ "$RES" != "" ]; then
	echo "Coding standards are not correct, cancelling your commit."
	echo ""
	echo $RES
	echo ""
        echo "If you want to fix them run:"
	echo ""
	echo "    php ${PROJECTROOT}${FIXER} fix ${PROJECTROOT} --verbose"
	echo ""
	exit 1
fi

```

##### Tip

Pour commiter sans passer par le hook on peut utiliser la ligne de commande suivante :
```
git commit -m "message" --no-verify
```