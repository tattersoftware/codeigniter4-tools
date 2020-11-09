# Tatter\Tools
Developer tools for CodeIgniter 4

## Spark Autocomplete

**Tools** includes a directive for Bash's
[Programmable Completion](http://www.gnu.org/software/bash/manual/bash.html#Programmable-Completion)
to allow tab-completing `spark` commands from the command-line.
Simply copy **src/spark_completion** to you Bash completions directory as "spark" and
re-source your environment (i.e. log out & in):

	sudo cp src/spark_completion /usr/share/bash-completion/completions/spark
	exit

Now when accessing commands for in CodeIgniter 4 you can autocomplete against
the list of available commands for your instance:

	> ./spark mi
	[tab]
	> ./spark migrate
	migrate           migrate:create    migrate:refresh   migrate:rollback  migrate:status
