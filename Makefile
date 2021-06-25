$(shell (if [ ! -e .env ]; then cp default.env .env; fi))
include .env
export

RUN_ARGS = $(filter-out $@,$(MAKECMDGOALS))


.PHONY: build
build: ## build environment and initialize composer and project dependencies
	docker build .docker/php$(PHP_VER)-dev/ -t docker.local/$(CI_PROJECT_PATH)/php$(PHP_VER)-dev:master ${DOCKER_BUILD_ARGS} --build-arg CI_COMMIT_REF_SLUG=$(CI_COMMIT_REF_SLUG) --build-arg CI_SERVER_HOST=$(CI_SERVER_HOST) --build-arg CI_PROJECT_PATH=$(CI_PROJECT_PATH) --build-arg PHP_VER=$(PHP_VER)
	docker-compose build --pull
	make composer-install

.PHONY: php-shell
php-shell: ## PHP shell
	docker-compose run --rm php sh -l

.PHONY: composer-install
composer-install: ## Install project dependencies
	docker-compose run --rm --no-deps php sh -lc 'composer install --ignore-platform-reqs'

.PHONY: composer-update
composer-update: ## Update project dependencies
	docker-compose run --rm --no-deps php sh -lc 'composer update --no-cache --ignore-platform-reqs'

.PHONY: composer-outdated
composer-outdated: ## Show outdated project dependencies
	docker-compose run --rm --no-deps php sh -lc 'composer outdated'

.PHONY: composer-validate
composer-validate: ## Validate composer config
		docker-compose run --rm --no-deps php sh -lc 'composer validate --no-check-publish'

.PHONY: composer
composer: ## Execute composer command
	docker-compose run --rm --no-deps php sh -lc "composer $(RUN_ARGS)"

.PHONY: test static-analysis coding-standards tests-integration composer-validate
test: build static-analysis coding-standards tests-integration composer-validate stop ## Run all test suites

.PHONY: lint
lint: ## checks syntax of PHP files
	docker-compose run --rm --no-deps php sh -lc './vendor/bin/parallel-lint ./ --exclude vendor --exclude bin/.phpunit'
	docker-compose run --rm --no-deps php sh -lc './bin/console lint:yaml config'

.PHONY: coding-standards
coding-standards: ## run check and validate code standards tests
	docker-compose run --rm --no-deps php sh -lc './vendor/bin/ecs check src test'
#	docker-compose run --rm --no-deps php sh -lc './vendor/bin/phpmd src/ text phpmd.xml' #todo: uncomment when phpmd supports php8.0

.PHONY: coding-standards-fixer
coding-standards-fixer: ## run code standards fixer
	docker-compose run --rm --no-deps php sh -lc './vendor/bin/ecs check src test --fix'

tests-unit: ## Run unit-tests suite
	docker-compose run --rm --no-deps php sh -lc 'vendor/bin/phpunit --configuration /app/phpunit.xml.dist'

tests-integration: ## Run integration-tests suite
	docker-compose run --rm --no-deps php sh -lc 'vendor/bin/phpunit --configuration /app/phpunit.xml'

.PHONY: infection
infection: ## executes mutation framework infection
	docker-compose run --rm --no-deps php-fpm sh -lc './vendor/bin/infection --min-msi=70 --min-covered-msi=80 --threads=$(JOBS) --coverage=var/report'

.PHONY: phpstan
phpstan: ## phpstan - PHP Static Analysis Tool
	docker-compose run --rm --no-deps php sh -lc './vendor/bin/phpstan analyse -l 6 -c phpstan.neon src tests'

.PHONY: psalm
psalm: ## psalm is a static analysis tool for finding errors in PHP applications
	docker-compose run --rm --no-deps php sh -lc './vendor/bin/psalm --config=psalm.xml'

.PHONY: phan
phan: ## phan is a static analyzer for PHP that prefers to minimize false-positives.
	docker-compose run --rm --no-deps php sh -lc 'PHAN_DISABLE_XDEBUG_WARN=1 PHAN_ALLOW_XDEBUG=0 php -d zend.enable_gc=0 ./vendor/bin/phan --config-file .phan/config.php --require-config-exists'

.PHONY: security-tests
security-tests: ## The SensioLabs Security Checker
	docker-compose run --rm --no-deps php sh -lc './vendor/bin/security-checker security:check --end-point=https://security.symfony.com/check_lock'


.PHONY: code-coverage
code-coverage: ## Pcov code coverage
	docker-compose run --rm --no-deps php sh -lc 'php -dpcov.enabled=1 -dpcov.directory=. -dpcov.exclude="~vendor~ " ./vendor/bin/phpunit --coverage-text'

