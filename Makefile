# This file is licensed under the Affero General Public License version 3 or
# later. See the COPYING file.
VERSION?=$(shell sed -ne 's/^\s*<version>\(.*\)<\/version>/\1/p' appinfo/info.xml)
composer=$(shell which composer 2> /dev/null)

# Internal variables
APP_NAME:=$(notdir $(CURDIR))
PROJECT_DIR:=$(CURDIR)/../$(APP_NAME)
BIN_DIR:=$(CURDIR)/bin
BUILD_DIR:=$(CURDIR)/build
BUILD_TOOLS_DIR:=$(BUILD_DIR)/tools
RELEASE_DIR:=$(BUILD_DIR)/release
CERT_DIR:=$(HOME)/.keys
OCC?=php ../../occ


# Installs and updates the composer dependencies. If composer is not installed
# a copy is fetched from the web
composer:
ifeq (, $(composer))
	@echo "No composer command available, downloading a copy from the web"
	mkdir -p $(BUILD_TOOLS_DIR)
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar $(BUILD_TOOLS_DIR)
	php $(BUILD_TOOLS_DIR)/composer.phar install --prefer-dist --ignore-platform-reqs --no-dev
else
	composer install --prefer-dist --ignore-platform-reqs --no-dev
endif

# Linting
lint:
	npm run lint

lint-fix:
	npm run lint:fix

# Cleaning
clean:
	rm -rf js/*

prepare-build:
	mkdir -p $(RELEASE_DIR)
	rsync -a --delete --delete-excluded --verbose\
		--exclude=".[a-z]*" \
		--exclude="Makefile" \
		--exclude="Dockerfile" \
		--exclude /$(APP_NAME)/build \
		--exclude /$(APP_NAME)/docs \
		--exclude /$(APP_NAME)/screenshots \
		--exclude /$(APP_NAME)/bin \
		--exclude /$(APP_NAME)/src \
		--exclude /$(APP_NAME)/node_modules \
		--exclude /$(APP_NAME)/tests \
		--exclude="composer.*" \
		--exclude="package*.json" \
		--exclude="*config.js" \
		--exclude="*config.json" \
	$(PROJECT_DIR) $(RELEASE_DIR)/ \
	&& chmod -R 777 $(RELEASE_DIR)/


# Packages the build into a release tarball, then signs the tarball.
package-build:
	tar -czf $(RELEASE_DIR)/$(APP_NAME)-$(VERSION).tar.gz \
		-C $(RELEASE_DIR) $(APP_NAME)

# Deletes any unnecessary files after the build is completed
clean-up-build:
	rm -rf $(RELEASE_DIR)/$(APP_NAME)

# Build a release package
build: composer prepare-build package-build clean-up-build
