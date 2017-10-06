# SE Profiler

## About

This is a frontend Magento1 toolbar/profiler for developers who want to have a better understanding 
about what's going during a GET request. It also provide "quick links" for certain actions that normally
consume a few minutes of your time.
 
Features:
* list all executed queries + execute the resultset of each SELECT-query.
* detailed category/product information: sku, id, storeview, link to edit it in the backend
* list what's logged during the request
* show database/ip
* show solr data
* front end links to products on backend product pages
* show cached data (loads, writes and removals)
* buttons to set current product in/out stock, fill/empty cart
* show all observers that were executed
* show cart content with id's

It is only meant to be used in development environments, **not in production**, not in staging. 

**It is absolutely unsafe to use it in any kind of publicly accessible website**. 
It shows plenty of information to potential hackers. (it could in fact be seen as a hacker-tool)

## Install

### Install modman

Install modman if not yet installed:

1) install modman (any folder will do)
bash < <(wget -q --no-check-certificate -O - https://raw.github.com/colinmollenhour/modman/master/modman-installer)
2) copy to PATH:
cp modman /usr/bin/local

modman --help
should work

## Install profiler

Once the module is installed, if changes are made to the modman file you should run:
modman deploy SE_Profiler

### Settings to make it work

You should only apply these settings in dev-environment (such as on your local machine), don't execute
this on production. (eg: enabling symlinks is a security vulnerability)

System > Configuration > Developer > Template Settings > 
* Allow Symlinks: enable.
* Allow ips: leave empty
* Enable profiler: yes

To see accurate product info, **full page cache** must be turned **off**. This is because we really on Mage::registry('current_product')
and that only works without full page cache (or just the first page load). 

If you get this error:

- Warning: include(): Filename cannot be empty  in app/code/core/Mage/Core/Block/Template.php on line 241

It means you must enable symlinks.

in local.xml make sure <profiler> has value 1. Otherwise the sql profiler won't work.

        <resources>
            <default_setup>
                <connection>
                    <profiler>1</profiler

Clear cache after this
                
## What if it doesn't work?

There are certainly situations where the profiler won't work as expected. This is because Magento installations
differ from each other and your installation may have custom changes that this toolbar doesn't know about.

Certainly features like SOLR, auto add to cart and front-end/backend-links could fail, depending on your installation.  

## SE_ProfilerSeSolrsearch install

If the module SE_Solrsearch exists, edit the SE_ProfilerSeSolrSearch.xml file and uncomment the
<SE_Solrsearch /> tag.

## Credits

I stole code (actually, the entire toolbar layout) from the Laravel 3 profiler project.

I took various bits from StackOverflow.