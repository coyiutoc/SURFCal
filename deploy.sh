#!/bin/bash

# Call: deploy.sh [prod, prev] [version]
# Eg:   deploy.sh prev 0.02

# Variables
repo="$HOME/dev/surfcal"
source="$repo/web"
branch="origin/feature/query-contact"
public="/var/www/html/surfcal"
version="0.01"

# Adjustments from parameters.
if [ ! -z $2 ]; then version=$2; fi
if [ ! -z $1 ] && [ $1 == "prev" ]; then version=$version"_prev"; fi

# Fetch
#cd $repo
#git checkout $branch
#git pull origin $branch

printf "\nDeploying in 5 seconds..."
sleep 5

# Update
sudo rm -rf $public
sudo cp -r $source $public
sudo chmod 755 -R $public

# Done
printf " Done.\n";
