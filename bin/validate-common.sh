#WPCS_REVISION=2e27757829cde21bca916b11cfcfa867c42b255e # version 0.6.0
WPCS_REVISION=539c6d74e6207daa22b7ea754d6f103e9abb2755 # version 1.0.0

# Validate only the following paths by default:
FILES=(
	src/themes/
	src/plugins/
	src/mu-plugins/
)

# And ignore these files (Not used when files are specified
# as arguments):
IGNORE=(
	web/app/mu-plugins/blackbird-*
	web/app/plugins/DUMMY-PLUGIN/PhpAmqpLib
	web/app/plugins/**/vendor # Ignore third party directories in custom plugins
)

# Override FILES and reset IGNORE when CLI arguments are present
if [[ ! -z "$*" ]]; then
	FILES=($*)
	IGNORE=
fi

#####
# WTF!!!
# The following is a composer asset

# Ensure dependencies are installed
if [[ ! -x vendor/bin/phpcs ]]; then
  echo "PHPCS required. Please run install.sh"
  exit
fi

#####
# WTF!!!
# The following should be a composer asset
if [[ ! -d etc/phpcs/wpcs ]]; then
  pushd etc/phpcs;
  echo "Checking out WP coding standards..."
  git clone -b master https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards.git wpcs
  pushd wpcs;
  git checkout $WPCS_REVISION
  popd
  popd
  # Configure PHPCS
  vendor/bin/phpcs --config-set installed_paths etc/phpcs/wpcs,etc/phpcs/base
fi

#pushd etc/phpcs/wpcs;
#git checkout $WPCS_REVISION
#popd


PHPCS_ARGS=(
	--standard=BASE
	--extensions=php
)

for i in ${IGNORE[@]}; do
	PHPCS_ARGS+=("--ignore=$i")
done

PHPCS_ARGS+=("-n")
PHPCS_ARGS+=("-s")
PHPCS_ARGS+=(${FILES[@]})
