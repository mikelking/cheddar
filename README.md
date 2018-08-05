# dummy
A dummy repo for experimenting with composer to build a local (or remote) development, staging and production WordPress system.

- So the purpose of this test is to build a working composer driven build of WordPress and related plugins. I hope to define a site by plugins and git libraries in a composer.json manifest and have a site completely assembled via a _composer update_ type command.
  - see [Composer website](https://getcomposer.org) for details.

- At this point I have it building the WordPress tree and have setup the iggie file to ignore ALL of WordPress core.

- The order of requirement is critical to successfully install ing all of the moving parts. The following outlines the order of operations:
  1. The wproot is installed first
  2. The WordPress core
  3. The composer dependencies mu-plugins, plugins and themes
  4. The local mu-plugins, plugins and themes

- This process allows one to clone or fork dummy and then create a branch specific to a particular site you wish to construct. It all affords the opportunity to build a single mu-plugin, plugin or theme in a uniform environment. 

- Assuming that you already have a DB setup and enter the appropriate details in the respective config file this should yield a working shell site. 

##### TODO:
- Updated to install the mu-plugins. DONE

- Updated to installed the WordPress common config system. DONE

- Composerafy development only plugins: DONE
    - Installation of debugbar and other dev only safe plugins via composer. 

- Integrate PHPCS: DONE
  - While this is technically complete I am not yet satisfied with the implementation. It works but there is room for improvement.
  - see [PHPCodeSniffer project](https://github.com/squizlabs/PHP_CodeSniffer/) for details.
  - see [WordPress Coding standards project](https://github.com/WordPress-Coding-Standards) for details.

- Integrate PHPUnit: Installed but not configured
    - see [PHPUnit website](https://phpunit.de/) for details.

- Integrate PHPloc: Incomplete
  - see [PHPloc project](https://github.com/sebastianbergmann/phploc) for details.

- Integrate PHPmd: Installed but not configured
  - This is being installed by composer but I am not yet confident that it is 100% working. It need more testing. 
  - see [PHPmd website](https://phpmd.org/) for details.

- Integreate PHPdocumentor or PHPdox: Incomplete
  - see [PHPdocumentor website](https://www.phpdoc.org/) for details.
  - see [PHPdox website](http://phpdox.de/) for details.
    - Remember that this relies on the [PHP-paser project](https://github.com/nikic/PHP-Parser/) for details.

- Integreate PHPcpd: Installed but not configured
  - see [PHP Copy/Paste Detector project](https://github.com/sebastianbergmann/phpcpd) for details.

- Implement a build system strategy: In progress
  - Still need to hook all of this into a deployment solution like deploybot. However for the time being and testing purposes I have written a simple set of build and deploy scripts that utilize rsync. Honestly the result has been better than I expected and were someone to make this easily configurable then one could use something like Jenkins or even ansible to execute a build.

- Implement wpcliL In Progress
  - https://wp-cli.org in order for this to be truly useful wpcli will need to be added.

- Integrate a vagrant: In progress
  - Need to add a Vagrant conf so that one can eventually run vagrant up to build a working local dev based upon the WordPress tree installed by composer. The Vagrant system would need to setup the db and conf files. PARTIAL

- Setup demos: In progress
  - Add demo to explain how to add plugins and themes from https://wpackagist.org. By adding the following line to the build chain in the required section of the composer.json it will add the appropriate plugin to the installation. You want to ensure that you add this line after the line that directs composer to install wordpress.

```
"wpackagist-plugin/akismet": "dev-trunk",
```

- Implement documentation: In progress
  - Documentation shall take the form of markdown files in a doc directory relative to this repo and mu-plugin, plugin or theme respectively. 
  
- Outline a recommended Plugin structure: In progress 

##### EXPRIMENTAL

- Refactor shell scripts into PHP