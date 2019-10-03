# New install
# svn checkout http://svn.mbnd.eu/svn/dev2/projects/internalwebtools/symfony4_user_skeleton user_sk
# cd user_sk
# run ./install.sh
#key
read -n1 -r -p "Edit .env - set database parameters... (Press any button when you're done)
" key
./composer.phar install
echo "Loading fixures - for example first, administrative user"
php bin/console doctrine:fixtures:load
yarn