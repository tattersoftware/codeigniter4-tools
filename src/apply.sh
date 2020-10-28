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

TOOLS=`dirname "$0"`
TARGET="$1"

echo "Applying developer tools to $TARGET"

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
if [ "`git branch | grep devtools`" ]; then
	git switch devtools
	RESULT=$?
	if [ $RESULT -ne 0 ]; then
		echo "Unable to switch to the devtools branch."
		exit $RESULT
	fi
else
	git switch -c devtools
	RESULT=$?
	if [ $RESULT -ne 0 ]; then
		echo "Unable to create the devtools branch."
		exit $RESULT
	fi
fi

# Apply changes to Composer first so we have all the tools
php "$TOOLS/composer.php" "$TARGET"
RESULT=$?
if [ $RESULT -ne 0 ]; then
	echo "Unable to apply Composer toolkit."
	exit $RESULT
fi

# Normalize the resulting composer.json
composer normalize "$TARGET"/composer.json

exit 0
