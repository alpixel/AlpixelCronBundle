CRONBundle
===========

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/014b2cfe-5f5d-42c2-b2e6-2c6f59342222/mini.png)](https://insight.sensiolabs.com/projects/014b2cfe-5f5d-42c2-b2e6-2c6f59342222)
[![Build Status](https://travis-ci.org/alpixel/AlpixelCronBundle.svg?branch=master)](https://travis-ci.org/alpixel/AlpixelCronBundle)
[![StyleCI](https://styleci.io/repos/50050483/shield)](https://styleci.io/repos/50050483)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alpixel/AlpixelCronBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alpixel/AlpixelCronBundle/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alpixel/cronbundle/v/stable)](https://packagist.org/packages/alpixel/cronbundle)


The AlpixelCronBundle is a fork of predakanga/CronBundle which isn't maintained anymore. It provides a Symfony Bundle capable of saving cronjob and running them at given intervals.



## Installation

* Install the package

```
composer require 'alpixel/cronbundle:~1.0'
```

* Update AppKernel.php


```

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new Alpixel\Bundle\CronBundle\CronBundle(),
            );

            // ...
        }

        // ...
    }
```

* Update DB Schema

```
php app/console doctrine:schema:update
```

* Start using the bundle

```
//analyze all the cron task available and register them
php app/console cron:scan 

//Run the cron analyzer
php app/console cron:run
```

* CRON setup

In order to run the symfony cron:run task, you should setup a real cronjob on the server just as follows. This example check the cron scrip every 5 minutes.

```
*/5 * * * * /usr/bin/php /path/to/symfony/install/app/console cron:run --env="prod"
```

## Creating a new task

Creating your own tasks with CronBundle couldn't be easier - all you have to do is create a normal Symfony2 Command (or ContainerAwareCommand) and tag it with the @CronJob annotation, as demonstrated below:

```php
use Alpixel\Bundle\CronBundle\Annotation\CronJob;

/**
 * @CronJob(value="P1D", startTime="today 12:00")
 */
class DemoCommand extends Command
{
    public function configure()
    {
        // Must have a name configured
        // ...
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // Your code here
    }
}
```

The interval spec ("PT1H" in the above example) is documented on the [DateInterval](http://php.net/dateinterval) documentation page, and can be modified. For your CronJob to be scanned and included in future runs, you must first run app/console cron:scan - it will be scheduled to run the next time you run app/console cron:run
