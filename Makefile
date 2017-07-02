###############################################################################
# Puzzle Query Builder
###############################################################################

HOST_SOURCE_PATH=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)

export USER_ID
export GROUP_ID

#------------------------------------------------------------------------------

include makefiles/composer.mk
include makefiles/phpunit.mk
include makefiles/whalephant.mk

#------------------------------------------------------------------------------

.DEFAULT_GOAL := help

init: composer-install

help:
	@echo "========================================"
	@echo "PUZZLE Query Builder"
	@echo "========================================"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
	@echo "========================================"

#------------------------------------------------------------------------------

clean: clean-composer clean-phpunit clean-whalephant

#------------------------------------------------------------------------------

.PHONY: init help clean
