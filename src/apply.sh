#!/bin/sh

##
# CodeIgniter4 Developer Tools
# https://github.com/tattersoftware/codeigniter4-tools
#
# apply.sh
# Applies all developer tools to the target repository.
##

if [ $# -lt 1 ]; then
    echo "Usage: apply.sh {path/to/repository}"
    exit 1
fi

# Determine the absolute target
cd "$1"
TARGET=`pwd`
echo "Applying developer tools to $TARGET"

# Determine the absolute directory for this script
cd `dirname "$0"`
TOOLS=`pwd`

# Change to the repo directory
cd "$TARGET"

# Make sure the repo is healthy
git status
RESULT=$?
if [ $RESULT -ne 0 ]; then
	echo "Unable to determine repository status."
	exit $RESULT
fi

# Check for an existing branch
if [ "`git branch | grep tools`" ]; then
	git switch tools
	RESULT=$?
	if [ $RESULT -ne 0 ]; then
		echo "Unable to switch to the tools branch."
		exit $RESULT
	fi
else
	git switch -c tools
	RESULT=$?
	if [ $RESULT -ne 0 ]; then
		echo "Unable to create the tools branch."
		exit $RESULT
	fi
fi

# Apply changes to Composer first so we have all the tools
php "$TOOLS/composer.php" "$TARGET"/composer.json
RESULT=$?
if [ $RESULT -ne 0 ]; then
	echo "Unable to apply Composer toolkit."
	exit $RESULT
fi

# Make sure this package is included
composer require --dev tatter/tools
RESULT=$?
if [ $RESULT -ne 0 ]; then
	echo "Unable to add Tools."
	exit $RESULT
fi

# Update to make sure we have the rest of the tools
composer update
RESULT=$?
if [ $RESULT -ne 0 ]; then
	echo "Unable to update dependencies."
	exit $RESULT
fi

# Normalize composer.json
composer normalize "$TARGET"/composer.json

# Determine project versus library
if [ -d "$TARGET"/app ]; then
	SOURCE="$TOOLS"/Project
else
	SOURCE="$TOOLS"/Library
fi

# Copy missing files
cp -R -n "$TOOLS"/Common/. "$TARGET"
cp -R -n "$SOURCE"/. "$TARGET"

exit 0
