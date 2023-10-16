###
### VARIABLES
###
APPLICATION_DOCKER_TAG ?= quotationTool

###
### DOCKER
###
.PHONY: help
.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

up: ## launching dockers
	$(info  )
	$(info  launching ///////////////////////////////////////)
	$(info )
	$(info     [$(APPLICATION_DOCKER_TAG)])
	$(info )
	$(info --------------------------------)
	$(info  )
	docker compose up -d --build || echo "\033[0;31m ^^^ Error | launch ^^^ \033[0m"

up-staging: ## launching dockers
	$(info  )
	$(info  launching ///////////////////////////////////////)
	$(info )
	$(info     [$(APPLICATION_DOCKER_TAG)])
	$(info )
	$(info --------------------------------)
	$(info  )
	docker compose -f docker-compose-staging.yml up -d --build --remove-orphans || echo "\033[0;31m ^^^ Error | launch ^^^ \033[0m"

### REBUILD
build: ## force building docker images again
	$(info  )
	$(info  forcing rebuild ///////////////////////////////////////)
	$(info )
	$(info     [$(APPLICATION_DOCKER_TAG)])
	$(info )
	$(info -------------------------------- )
	docker builder prune || echo "\033[0;31m ^^^ Error | prune ^^^ \033[0m"
	docker compose  build --pull --no-cache || echo "\033[0;31m ^^^ Error | build ^^^ \033[0m"

### STOP
stop: ## stopping dockers
	$(info  )
	$(info  stopping ///////////////////////////////////////)
	$(info )
	$(info     [$(APPLICATION_DOCKER_TAG)])
	$(info  )
	$(info -------------------------------- \033[0m)
	$(info  )
	docker compose stop || echo "\033[0;31m ^^^ Error | stop ^^^ \033[0m"

stop-staging: ## launching dockers
	$(info  )
	$(info  launching ///////////////////////////////////////)
	$(info )
	$(info     [$(APPLICATION_DOCKER_TAG)])
	$(info )
	$(info --------------------------------)
	$(info  )
	docker compose -f docker-compose-staging.yml stop --remove-orphans || echo "\033[0;31m ^^^ Error | stop ^^^ \033[0m"

### BASH
bash: ## access bash in wordpress container
	docker compose exec -it $(APPLICATION_DOCKER_TAG) bash	

### LOGS
logs-wp: ## display 100 last logs lines (WORDPRESS)
	docker compose logs $(APPLICATION_DOCKER_TAG) --tail=100

logs-db: ## display 100 last logs lines (DB)
	docker compose logs db --tail=100

### INSTALL
install:
	cd plugins/alchimycrm && composer install --ignore-platform-reqs
	cp -r -a plugins/alchimycrm/fonts/. plugins/alchimycrm/vendor/tecnickcom/fonts/.

### EXPORT WORDPRESS
wp-export: ## create a wordpress folder with a classic structure
	cp -a ./wp_data/. ./wp_export/.
	cp -a ./plugins/. ./wp_export/wp-content/plugins/.
	cp -a ./themes/. ./wp_export/wp-content/themes/.

