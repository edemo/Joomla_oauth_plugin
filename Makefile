all:
	tools/script

testenv:
	docker run --rm -p 5901:5901 -v $$(pwd):/joomla_oauth_plugin -it magwas/wp_oauth_plugin /bin/bash

check:
	phpunit --stderr tests

e2e:	recording
	PYTHONPAT=end2endtest python3 -m unittest discover -v -f -s end2endtest -p "*.py"

cleanup: stoprecording
	mv /tmp/joomlalog/* shippable
	rm -rf tmp/

recording:
	start-stop-daemon --start --background --oknodo --name flvrec --make-pidfile --pidfile /tmp/flvrec.pid --startas /usr/bin/python -- /usr/local/bin/flvrec.py -o /tmp/joomlalog/record.flv :1

stoprecording:
	-start-stop-daemon --stop --pidfile /tmp/flvrec.pid

