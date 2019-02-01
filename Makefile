.PHONY: test
test:
	@echo "Starting Unit Tests"; \
	docker-compose run wordpress /vendor/bin/phpunit /code/tests/ConversationViewerTest.php
