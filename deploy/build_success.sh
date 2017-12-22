#!/bin/bash
PROJECT="helioconfig"
TARGET="staging"

echo "Travis_Branch: $TRAVIS_BRANCH"
echo "TRAVIS_SECURE_ENV_VARS: $TRAVIS_SECURE_ENV_VARS"
echo "TRAVIS_PULL_REQUEST: $TRAVIS_PULL_REQUEST"

if ! $TRAVIS_SECURE_ENV_VARS ; then
    exit 0
elif [ $TRAVIS_BRANCH == "staging" ]; then
    exit 0
elif [ $TRAVIS_BRANCH == "master" ]; then
    if ! $TRAVIS_PULL_REQUEST ; then
        exit 0
    fi
    TARGET="master"
fi

echo "updating git config: "
git config remote.origin.fetch "+refs/heads/*:refs/remotes/origin/*"
git config --global user.email "developers@completesolar.com"
git config --global user.name "completesolardevelopers"
echo "Changing remote url: $PROJECT"
git remote set-url origin https://github.com/completesolar/$PROJECT.git
echo "Fetching $TARGET:"
git fetch origin $TARGET
echo "Calling git show-ref"
git show-ref
echo "***************"
echo "Displaying list of branches:"
git branch -a
echo "Checking out remotes/origin/$TARGET:"
git checkout remotes/origin/$TARGET
echo "Creating new branch $TARGET:"
git checkout -b $TARGET
echo "Displaying list of branches:"
git branch -a
echo "Displaying commit log:"
git log -5
echo "Merging from $TRAVIS_BRANCH:"
git merge $TRAVIS_BRANCH -m "Merging to $TARGET from $TRAVIS_BRANCH [ci skip]"
echo "Pushing:"
git push origin $TARGET