# dummy
Welcome to the dummy project. It is called _dummy_ because the base is just a dummy framework that pulls together a lot of other parts into a single functioning entity. It is a foundational system initially intended as a learning tool that grew into a full blown system tool. To use you simply clone or fork and then start adding your site specific changes. Most og these changes are entered in the composer.json manifest and that file in essence becomes the documentation source for your site.

Dummy out of the box will let you build a local development site. However you will need to understand some of the basics around vagrant and WordPress configurations to achieve the full potential. Dummy can hep you build a resilient tiered development system complete with dev, staging, preprod and production WordPress environments.

##### NOTE:
In order to get the most out of dummy you will need to have some familiarity with Composer, Vagrant, WordPress configurations and Apache Server configurations. 

Purpose of this test is to use composer to define all of the external assets necessary to build a working WordPress and related plugins. The hope is to define a site's plugins, themes and git libraries in a composer.json manifest. Then have a site completely assembled via a _composer update_ type command.
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
- Updated to install the mu-plugins. **DONE**

- Updated to centralize the source tree. **DONE**

- Updated to installed the WordPress common configuration system. DONE
  - see the [WordPress Abstract Configuration System project](https://github.com/mikelking/wpcfg) for details.

- Composerafy development only plugins: **DONE**
    - Installation of debugbar and other dev only safe plugins via composer. 

- Integrate PHPCS: **DONE**
  - While this is technically complete I am not yet satisfied with the implementation. It works but there is room for improvement.
  - see the validate script
  - see [PHPCodeSniffer project](https://github.com/squizlabs/PHP_CodeSniffer/) for details.
  - see [WordPress Coding standards project](https://github.com/WordPress-Coding-Standards) for details.

- Integrate PHPUnit: _Installed but fully not configured_
  - Currently some dummy tests are functional and composer is bringing in 10Up's WP_Mock to facilitate proper unit testing. The official WordPress unit testing is actually integration testing and that is NOT the purpose of this stage.   
  - see [PHPUnit website](https://phpunit.de/) for details.

- Integrate PHPloc: **DONE**
  - see the analyze script
  - see [PHPloc project](https://github.com/sebastianbergmann/phploc) for details.

- Integrate PHPmd: _Installed but not configured_
  - This is being installed by composer but I am not yet confident that it is 100% working. It need more testing. 
  - see [PHPmd website](https://phpmd.org/) for details.

- Integreate PHPdocumentor or PHPdox: _Incomplete_
  - see [PHPdocumentor website](https://www.phpdoc.org/) for details.
  - see [PHPdox website](http://phpdox.de/) for details.
    - Remember that this relies on the [PHP-paser project](https://github.com/nikic/PHP-Parser/) for details.

- Integreate PHPcpd: **DONE**
  - see the analyze script
  - see [PHP Copy/Paste Detector project](https://github.com/sebastianbergmann/phpcpd) for details.

- Implement a build system strategy: _In progress_
  - Still need to hook all of this into a deployment solution like deploybot. However for the time being and testing purposes I have written a simple set of build and deploy scripts that utilize rsync. Honestly the result has been better than I expected and were someone to make this easily configurable then one could use something like Jenkins or even ansible to execute a build.

- Implement wpcli _In Progress_
  - https://wp-cli.org in order for this to be truly useful wpcli will need to be added.

- Integrate a vagrant: _In progress_
  - Need to add a Vagrant conf so that one can eventually run vagrant up to build a working local dev based upon the WordPress tree installed by composer. The Vagrant system would need to setup the db and conf files. PARTIAL
  - see the [Pryamid project](https://github.com/mikelking/pyramid) for details.

- Setup demos: _In progress_
  - Add demo to explain how to add plugins and themes from https://wpackagist.org. By adding the following line to the build chain in the required section of the composer.json it will add the appropriate plugin to the installation. You want to ensure that you add this line after the line that directs composer to install wordpress.

```
"wpackagist-plugin/akismet": "dev-trunk",
```

- Implement documentation: _In progress_
  - Documentation shall take the form of markdown files in a doc directory relative to this repo and mu-plugin, plugin or theme respectively. 
  
- Outline a recommended Plugin structure: In progress 

##### EXPRIMENTAL

- Refactor shell scripts into PHP (_PIPE DREAM_)