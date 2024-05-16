# Change Log

All notable changes to this project will be documented in this file.
See [Conventional Commits](https://conventionalcommits.org) for commit guidelines.

## 4.7.8 (2024-05-10)


### Bug Fixes

* compatibility with AI Assistant with ChatGPT by AYS (CU-8694f00cj)
* compatibility with pixfort modal dialog and unblocked content
* compatibility with SuperFly Menu in combination with Autoptimize (CU-8694ge2gj)
* fatal error in admin dashboard about urlencode when using multidimensional cookies for REST API check (CU-86949561p)
* increase timeout for testing the REST API consent save mechanism (CU-86949561p)


<details><summary>Dependency updates @devowl-wp/fast-html-tag 0.10.3</summary>


**_Purpose of dependency:_** _Find tags within HTML content and modify it in speed of light_
##### Bug Fixes

* allow to rerun the HTML processor multiple times through registerRerun method (CU-8694ge2gj)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.16.9</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* apply inline script plugins also to scripts with base64-encoded data URL as src (CU-8694ge2gj)
* compatibility with AI Assistant with ChatGPT by AYS (CU-8694f00cj)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.19.19</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Bug Fixes

* delete checkboxes for privacy policy and only print an information for this (CU-861mrzwar)</details>

<details><summary>Dependency updates @devowl-wp/real-utils 1.13.3</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Bug Fixes

* delete checkboxes for privacy policy and only print an information for this (CU-861mrzwar)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.19.3</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* do not show admin notice about REST API issues in update admin screen (CU-8694hc398)
* too many requests to license.devowl.io announcements endpoint (CU-86939q6ce)</details>

<details><summary>Development dependency update @devowl-wp/web-cookie-banner 0.6.2</summary>


**_Purpose of dependency:_** _Provide a scoped stylesheet, types and util functionality for a web cookie banner._
##### Bug Fixes

* sticky legal link icon is not aligned correctly when bootstrap is in use (CU-8694dcmep)</details>





## 4.7.7 (2024-04-25) (not released)

**Note:** This version of the package has not (yet) been released publicly. This happens if changes have been made in dependencies that do not affect this package (e.g. changes for the development of the package). The changes will be rolled out with the next official update.

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/api-real-cookie-banner 0.13.3</summary>


**_Purpose of dependency:_** _Shared typings for all Real Cookie Banner backend._
##### Bug Fixes

* added moe supported languages (CU-86936my3v)
* disabled default lang edit, hide fields depending on translatable and extended merge strategy plus other fixes (CU-86936my3v)
* enabled statistics with allowedLanguages for translators, changed media permissions (CU-86936my3v)
* filter language select by role parameters (CU-86936my3v)
* introduce lastEditedBy field for templates (CU-86936my3v)
* introduce role parameters to users (CU-86936my3v)
* introducing form field wrapper with role based functionalities and used it for template forms (CU-86936my3v)
* introducing password login in rcb (CU-86936my3v)
* purpose translation variable validation and last editors (CU-86936my3v)
* rename route to user/password (CU-86936my3v)


##### Refactoring

* introduce user base interfaces (CU-86936my3v)
* move password route (CU-86936my3v)</details>

<details><summary>Development dependency update @devowl-wp/api 0.5.21</summary>


**_Purpose of dependency:_** _Shared typings for all Node.js backends and frontends._
##### Bug Fixes

* introducing password login in rcb (CU-86936my3v)


##### Refactoring

* introduce user base interfaces (CU-86936my3v)</details>





## 4.7.6 (2024-04-23)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/cookie-consent-management 0.1.11</summary>


**_Purpose of dependency:_** _Provide cookie consent management with adapters to your environment_
##### Bug Fixes

* consent types were recommended for Google Tag Manager service in notice (CU-8694art6m)</details>

<details><summary>Development dependency update @devowl-wp/api-real-product-manager 0.3.9</summary>


**_Purpose of dependency:_** _Shared typings for all Real Commerce backend._
##### Maintenance

* write NulledVersionUsage deferred (CU-8694939q9)</details>





## 4.7.5 (2024-04-20)


### Bug Fixes

* fatal error in Notices.php and urlencode when using array cookies e.g. my-cookie[] (CU-86949561p)







## 4.7.4 (2024-04-19)


### Bug Fixes

* notice about REST API is showing a fatal error as forwarded cookies for the REST API test are not encoded (CU-86949561p)
* notice about saving consents is shown when request takes longer than 2 seconds (timeout, CU-86949561p)


<details><summary>Dependency updates @devowl-wp/cookie-consent-management 0.1.10</summary>


**_Purpose of dependency:_** _Provide cookie consent management with adapters to your environment_
##### Bug Fixes

* do not show notice about Google Consent Mode and Tag Manager when identifier is gtm-1 (e.g. WPML/PolyLang, CU-8694art6m)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.12.2</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* old safari browser shows blurry background and no cookie banner (CU-8694at817)</details>

<details><summary>Development dependency update @devowl-wp/web-cookie-banner 0.6.1</summary>


**_Purpose of dependency:_** _Provide a scoped stylesheet, types and util functionality for a web cookie banner._
##### Bug Fixes

* old safari browser shows blurry background and no cookie banner (CU-8694at817)</details>





## 4.7.3 (2024-04-12)


### Bug Fixes

* the newly added notice about REST API shows errors about 403 Forbidden, we added another tip for the cause (CU-86949561p)
* the newly added notice about REST API shows errors about cURL timeouts (CU-86949561p)


<details><summary>Dependency updates @devowl-wp/cookie-consent-management 0.1.9</summary>


**_Purpose of dependency:_** _Provide cookie consent management with adapters to your environment_
##### Bug Fixes

* deprecation warning in PHP log about Requests_Response class (CU-86949561p)
* the newly added notice about REST API shows errors about 403 Forbidden, we added another tip for the cause (CU-86949561p)</details>





## 4.7.2 (2024-04-10)


### Bug Fixes

* cURL error 60: SSL: no alternative certificate subject name matches target host name (CU-86949561p)
* show another tip about REST API issues when cURL throws an error (CU-86949561p)


<details><summary>Dependency updates @devowl-wp/cookie-consent-management 0.1.8</summary>


**_Purpose of dependency:_** _Provide cookie consent management with adapters to your environment_
##### Bug Fixes

* show another tip about REST API issues when cURL throws an error (CU-86949561p)</details>

<details><summary>Dependency updates @devowl-wp/fast-html-tag 0.10.2</summary>


**_Purpose of dependency:_** _Find tags within HTML content and modify it in speed of light_
##### Bug Fixes

* uppercase <A tag leads to scanner results (CU-869496hwj)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.16.8</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* uppercase <A tag leads to scanner results (CU-869496hwj)


##### Testing

* rename test (CU-869496hwj)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.19.1</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Maintenance

* update stubs (CU-86949561p)</details>





## 4.7.1 (2024-04-09)


### Documentation

* mention new translations Greek, Romanian, Hungarian, Slovakian and Finnish in wordpress.org plugin description (CU-86947y4pv)


<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.9.5</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Bug Fixes

* cookie banner not shown in older Safari versions (Hotfix, CU-86947y4pv)</details>





# 4.7.0 (2024-04-09)


### Bug Fixes

* compatibility with NS Clonser Site Copier and a PHP fatal error while cloning (CU-86941hv75)
* compatibility with Perfmatters DelayJS functionality and Code on page load scripts (CU-869465a82)
* compatibility with Slider Revolution v7 not loading
* compatibility with wl-api-connector
* compatibility with WS Forms and Google Maps field (CU-86947u85x)
* implement a mechanism detecting a defect Consent REST API and recommend knowledgebase articles (CU-8693zknc0)
* introduce a fallback system when the remote server is not available (CU-1xpcvre)
* privacy policy mention usage checklist item is not checked when using Gutenberg patterns (CU-869454cmr)
* scanner recommandation does not get removed after cloning website (CU-86948fqwy)
* typo (CU-861myr2cq)


### Build Process

* remove minimal translations el fi and fix localization system (CU-861myr2cq)


### Features

* allow to delete consents individually in List of consents table (CU-86944k7fc)
* introduce sticky legal links widget in customizer (CU-1za40xb)
* translations into Hungarian, Romanian, Greek, Finnish and Slovak (CU-863gr8e97)


<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.17.2</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Bug Fixes

* compatibility with Perfmatters DelayJS functionality and Code on page load scripts (CU-869465a82)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-management 0.1.6</summary>


**_Purpose of dependency:_** _Provide cookie consent management with adapters to your environment_
##### Bug Fixes

* implement a mechanism detecting a defect Consent REST API and recommend knowledgebase articles (CU-8693zknc0)
* implement wait_for_update in Google Consent Mode to avoid issues with too early fired events (CU-86946wnva)
* introduce a fallback system when the remote server is not available (CU-1xpcvre)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.9.4</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Bug Fixes

* also sync the unblocking of a visual content blocker with other tabs (CU-8693gvgkh)
* introduce a fallback system when the remote server is not available (CU-1xpcvre)</details>

<details><summary>Dependency updates @devowl-wp/customize 1.12.0</summary>


**_Purpose of dependency:_** _Abstract utility for live preview (customize)_
##### Build Process

* remove minimal translations el fi and fix localization system (CU-861myr2cq)


##### Features

* translations into Hungarian, Romanian, Greek, Finnish and Slovak (CU-863gr8e97)</details>

<details><summary>Dependency updates @devowl-wp/deliver-anonymous-asset 0.2.70</summary>


**_Purpose of dependency:_** _Provide a functionality to deliver assets anonymous_
##### Bug Fixes

* use vigenere cipher for obfuscating the REST API URL (CU-8693zknc0)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.16.7</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* correctly block link HTML tags with multiple rels (CU-86945vky4)
* scanner finds Google Maps for MyListing theme when Mapbox instead of Google Maps is used (CU-86947zz6j)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-unblocker 0.8.9</summary>


**_Purpose of dependency:_** _Unblock mechanism for @devowl-wp/headless-content-blocker with visual capabilities._
##### Bug Fixes

* window.onload assignment should behave like window load event (CU-86947my22)</details>

<details><summary>Dependency updates @devowl-wp/multilingual 1.13.0</summary>


**_Purpose of dependency:_** _Provide helper functionality for multilingual plugins like WPML and PolyLang_
##### Build Process

* remove minimal translations el fi and fix localization system (CU-861myr2cq)


##### Features

* translations into Hungarian, Romanian, Greek, Finnish and Slovak (CU-863gr8e97)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.12.0</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* also sync the unblocking of a visual content blocker with other tabs (CU-8693gvgkh)
* introduce a fallback system when the remote server is not available (CU-1xpcvre)


##### Features

* introduce sticky legal links widget in customizer (CU-1za40xb)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.13.5</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* introduce a fallback system when the remote server is not available (CU-1xpcvre)
* reset all template fields in edit form</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.19.16</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Bug Fixes

* deactivate license domain detection when running WordPress through WP CLI (CU-869482eaf)


##### Build Process

* remove minimal translations el fi and fix localization system (CU-861myr2cq)</details>

<details><summary>Dependency updates @devowl-wp/real-queue 0.6.0</summary>


**_Purpose of dependency:_** _Provide a promise-based queue system working in frontend for client and server tasks_
##### Build Process

* remove minimal translations el fi and fix localization system (CU-861myr2cq)


##### Features

* translations into Hungarian, Romanian, Greek, Finnish and Slovak (CU-863gr8e97)</details>

<details><summary>Dependency updates @devowl-wp/real-utils 1.13.0</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Build Process

* remove minimal translations el fi and fix localization system (CU-861myr2cq)


##### Features

* translations into Hungarian, Romanian, Greek, Finnish and Slovak (CU-863gr8e97)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.19.0</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* compatibility with Perfmatters DelayJS functionality and Code on page load scripts (CU-869465a82)
* implement a mechanism detecting a defect Consent REST API and recommend knowledgebase articles (CU-8693zknc0)
* use vigenere cipher for obfuscating the REST API URL (CU-8693zknc0)


##### Build Process

* remove minimal translations el fi and fix localization system (CU-861myr2cq)


##### Features

* translations into Hungarian, Romanian, Greek, Finnish and Slovak (CU-863gr8e97)</details>

<details><summary>Development dependency update @devowl-wp/phpunit-config 0.1.13</summary>


**_Purpose of dependency:_** _Predefined functionalities for PHPUnit._
##### Bug Fixes

* scanner finds Google Maps for MyListing theme when Mapbox instead of Google Maps is used (CU-86947zz6j)</details>

<details><summary>Development dependency update @devowl-wp/web-cookie-banner 0.6.0</summary>


**_Purpose of dependency:_** _Provide a scoped stylesheet, types and util functionality for a web cookie banner._
##### Features

* introduce sticky legal links widget in customizer (CU-1za40xb)</details>





## 4.6.2 (2024-03-24) (not released)

**Note:** This version of the package has not (yet) been released publicly. This happens if changes have been made in dependencies that do not affect this package (e.g. changes for the development of the package). The changes will be rolled out with the next official update.

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/multilingual 1.12.16</summary>


**_Purpose of dependency:_** _Provide helper functionality for multilingual plugins like WPML and PolyLang_
##### Bug Fixes

* compatibility with latest PolyLang version and REST API (CU-86942c147)</details>





## 4.6.1 (2024-03-22)


### Bug Fixes

* avoid race conditions when contacting our backend servers to avoid triggering rate limit notice (CU-86939q6ce)
* close cookie banner in multi-tab scenario in all tabs (CU-8693gvgkh)
* compatibility with WordPress 6.5 (CU-869434yv9)
* do not allow to block the cookie banner overlay by a rule (CU-86943585g)


<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.9.3</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Bug Fixes

* close cookie banner in multi-tab scenario in all tabs (CU-8693gvgkh)</details>

<details><summary>Dependency updates @devowl-wp/deliver-anonymous-asset 0.2.69</summary>


**_Purpose of dependency:_** _Provide a functionality to deliver assets anonymous_
##### Bug Fixes

* correctly set chmod for anti-ad-block files in wp-content folder (CU-8694394ga)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.11.1</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* close cookie banner in multi-tab scenario in all tabs (CU-8693gvgkh)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.19.15</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Bug Fixes

* avoid race conditions when contacting our backend servers to avoid triggering rate limit notice (CU-86939q6ce)</details>

<details><summary>Dependency updates @devowl-wp/service-cloud-consumer 0.5.3</summary>


**_Purpose of dependency:_** _Consume service and blocker templates from service cloud_
##### Bug Fixes

* avoid race conditions when contacting our backend servers to avoid triggering rate limit notice (CU-86939q6ce)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.18.3</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* avoid race conditions when contacting our backend servers to avoid triggering rate limit notice (CU-86939q6ce)</details>





# 4.6.0 (2024-03-13)


### Bug Fixes

* allow also to ignore/unignore service templates in scanner results (CU-31mh4np)
* compatibility with AppThemes Vantage and Google Maps
* compatibility with Bricks Builder and lazy loaded iframes (CU-8693z2tw6)
* compatibility with GoodLayers page builder and unblocking background videos (CU-8693wxcad)
* compatibility with latest GA Google Analytics PRO version (CU-86941x3bv)
* compatibility with SureCart (CU-861mwehmt)
* deprecated:explode(): Passing null to parameter [#2](https://git.devowl.io/devowlio/devowl-wp/issues/2) () of type string is deprecated


### Features

* introduce a new customizer option to define a maximum height for the cookie banner (CU-86940n0a0)


<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.17.0</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Features

* support Swift Performance AI (CU-8693xe6a6)


##### Performance

* avoid that the cookie banner gets the LCP when WP Rocket is active (CU-86939bd3z)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-management 0.1.4</summary>


**_Purpose of dependency:_** _Provide cookie consent management with adapters to your environment_
##### Bug Fixes

* undefined array key flag PHP warning</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.16.6</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* allow also to ignore/unignore service templates in scanner results (CU-31mh4np)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-unblocker 0.8.8</summary>


**_Purpose of dependency:_** _Unblock mechanism for @devowl-wp/headless-content-blocker with visual capabilities._
##### Bug Fixes

* allow to avoid visual content blocker deduplication through custom class rcb-avoid-deduplication on parent element (CU-86940a5nt)
* compatibility with GoodLayers page builder and unblocking background videos (CU-8693wxcad)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.11.0</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* show service group with partial icon when a service with opt-out is configure (CU-86941b3h8)


##### Features

* introduce a new customizer option to define a maximum height for the cookie banner (CU-86940n0a0)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.13.3</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* allow also to ignore/unignore service templates in scanner results (CU-31mh4np)</details>

<details><summary>Dependency updates @devowl-wp/service-cloud-consumer 0.5.2</summary>


**_Purpose of dependency:_** _Consume service and blocker templates from service cloud_
##### Bug Fixes

* allow also to ignore/unignore service templates in scanner results (CU-31mh4np)</details>

<details><summary>Development dependency update @devowl-wp/web-cookie-banner 0.5.0</summary>


**_Purpose of dependency:_** _Provide a scoped stylesheet, types and util functionality for a web cookie banner._
##### Features

* introduce a new customizer option to define a maximum height for the cookie banner (CU-86940n0a0)


##### Styling

* break all links when viewport gets too small on mobile in second view of cookie banner</details>

<details><summary>Development dependency update @devowl-wp/web-scoped-css 0.3.0</summary>


**_Purpose of dependency:_** _Define a scoped stylesheet in JavaScript with performance in mind._
##### Features

* introduce a new customizer option to define a maximum height for the cookie banner (CU-86940n0a0)</details>





## 4.5.4 (2024-03-04) (not released)

**Note:** This version of the package has not (yet) been released publicly. This happens if changes have been made in dependencies that do not affect this package (e.g. changes for the development of the package). The changes will be rolled out with the next official update.

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Development dependency update @devowl-wp/webpack-config 0.2.29</summary>


**_Purpose of dependency:_** _Webpack config builder for multiple ecosystems like standalone React frontends, Antd, Preact and WordPress._
##### Bug Fixes

* do not index admin UI in search engines (CU-8693yzxhv)</details>





## 4.5.3 (2024-02-29)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/deliver-anonymous-asset 0.2.67</summary>


**_Purpose of dependency:_** _Provide a functionality to deliver assets anonymous_
##### Bug Fixes

* compatibility with latest TCF stub version not loading (CU-8693ubj9a)</details>





## 4.5.2 (2024-02-28)


### Bug Fixes

* compatibility with WP Fastest Cache when cookie banner does no longer get loaded (CU-8693ubj9a)


<details><summary>Dependency updates @devowl-wp/deliver-anonymous-asset 0.2.66</summary>


**_Purpose of dependency:_** _Provide a functionality to deliver assets anonymous_
##### Bug Fixes

* compatibility with WP Fastest Cache when cookie banner does no longer get loaded (CU-8693ubj9a)
* switch from free to PRO version sometimes did not recreate the JavaScript files for the cookie banner (CU-8693ubj9a)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.10.2</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* when changing consent and WordPress is too slow no changes are saved (CU-8693n1cc5)</details>





## 4.5.1 (2024-02-26)


### Bug Fixes

* cookie banner not loaded when anti ad block system is deactivated (CU-8693ubj9a)


### Documentation

* adaptation of README.txt to new guidelines from wordpress.prg (CU-8693xh2mk)







# 4.5.0 (2024-02-26)


### Bug Fixes

* client property value is empty error message when using serialized siteurl option (CU-8693uhwd7)
* compatibility with Kadence video popups (CU-8693jtbzu)
* improved compatibility with WP Fastest Cache and cookie banner not loading in customizer (CU-8693h1xfq)
* output the correct footnote when a custom group is created
* undefined array key vendorConfigurations (CU-apv5uu)


### Documentation

* mention Google Consent Mode in wordpress.org description (CU-apv5uu)


### Features

* improve performance for better Google PageSpeed Insights TBT and INP score (CU-8693u3e89)


### Performance

* allow to parse big objects localized via wp_localize_script lazily (CU-8693n1cc5)
* do no longer use webpackMode eager in favor of inline-require (CU-8693n1cc5)
* improve performance by not removing cookie banner from DOM after accepting for better INP in Google PageSpeed (CU-8693n1cc5)
* improve performance of applying consent and unblocking consent for better INP in Google PageSpeed (CU-8693n1cc5)
* improve Total Blocking Time in Page Speed Insights by inlining require statements (CU-8693n1cc5)
* improve Total Blocking Time in Page Speed Insights by yielding the main thread for TCF cookie banner (CU-8693n1cc5)
* lazy load data for the second layer / view of the cookie banner (CU-8693n1cc5)
* use code splitting for the cookie banner and content blocker to reduce initial download time (CU-8693ubj9a)


### Refactoring

* move all consent relevant structures and procedures to @devowl-wp/cookie-consent-management (CU-8693n1cc5)
* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)


<details><summary>Dependency updates @devowl-wp/api-real-cookie-banner 0.13.1</summary>


**_Purpose of dependency:_** _Shared typings for all Real Cookie Banner backend._
##### Bug Fixes

* empty template name on release warning message (CU-8693uepwd)</details>

<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.16.0</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Features

* allow to clear cache of enhance.com domains (NGINX FastCGI, CU-8693cqz75)
* introduce new filter DevOwl/CacheInvalidate/Custom (CU-8693w2vf0)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-management 0.1.2</summary>


**_Purpose of dependency:_** _Provide cookie consent management with adapters to your environment_
##### Bug Fixes

* 404 error in Consent Forwarding when GCM and TCF is deactivated (CU-8693265jz)


##### Performance

* lazy load data for the second layer / view of the cookie banner (CU-8693n1cc5)


##### Refactoring

* move all consent relevant structures and procedures to @devowl-wp/cookie-consent-management (CU-8693n1cc5)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.9.1</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Performance

* improve performance of applying consent and unblocking consent for better INP in Google PageSpeed (CU-8693n1cc5)
* improve Total Blocking Time in Page Speed Insights by yielding the main thread for TCF cookie banner (CU-8693n1cc5)
* use code splitting for the cookie banner and content blocker to reduce initial download time (CU-8693ubj9a)


##### Refactoring

* move all consent relevant structures and procedures to @devowl-wp/cookie-consent-management (CU-8693n1cc5)
* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)</details>

<details><summary>Dependency updates @devowl-wp/deliver-anonymous-asset 0.2.65</summary>


**_Purpose of dependency:_** _Provide a functionality to deliver assets anonymous_
##### Performance

* use code splitting for the cookie banner and content blocker to reduce initial download time (CU-8693ubj9a)</details>

<details><summary>Dependency updates @devowl-wp/fast-html-tag 0.10.0</summary>


**_Purpose of dependency:_** _Find tags within HTML content and modify it in speed of light_
##### Features

* introduce new comparator for selector syntax function to match regular expression with //= (CU-33z67qt)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.16.5</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* strtolower(): Passing null to parameter [#1](https://git.devowl.io/devowlio/devowl-wp/issues/1) () of type string is deprecated (CU-863gympe9)
* uncaught TypeError: Cannot access offset of type string on string TcfVendorDomainsBlockable.php</details>

<details><summary>Dependency updates @devowl-wp/headless-content-unblocker 0.8.7</summary>


**_Purpose of dependency:_** _Unblock mechanism for @devowl-wp/headless-content-blocker with visual capabilities._
##### Bug Fixes

* compatibility with Kadence video popups (CU-8693jtbzu)
* initiators for load event should wait also for async scripts (CU-8693wju7t)


##### Performance

* improve performance of applying consent and unblocking consent for better INP in Google PageSpeed (CU-8693n1cc5)
* use code splitting for the cookie banner and content blocker to reduce initial download time (CU-8693ubj9a)


##### Refactoring

* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)</details>

<details><summary>Dependency updates @devowl-wp/multilingual 1.12.13</summary>


**_Purpose of dependency:_** _Provide helper functionality for multilingual plugins like WPML and PolyLang_
##### Performance

* cache find i18n key of translation functionality (CU-8693cqz75)
* use raw database queries and bypass TranslatePress API for translating URLs (CU-8693cqz75)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.10.1</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Performance

* do no longer use webpackMode eager in favor of inline-require (CU-8693n1cc5)
* improve performance by not removing cookie banner from DOM after accepting for better INP in Google PageSpeed (CU-8693n1cc5)
* improve performance of applying consent and unblocking consent for better INP in Google PageSpeed (CU-8693n1cc5)
* improve Total Blocking Time in Page Speed Insights by yielding the main thread for TCF cookie banner (CU-8693n1cc5)
* lazy load data for the second layer / view of the cookie banner (CU-8693n1cc5)
* use code splitting for the cookie banner and content blocker to reduce initial download time (CU-8693ubj9a)


##### Refactoring

* move all consent relevant structures and procedures to @devowl-wp/cookie-consent-management (CU-8693n1cc5)
* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.13.1</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* errors were no longer shown in service edit form for technical definitions (CU-8693wmp4k)
* show a notice when activating GCM consent mode with Tag Manager integration when previsouly events were active (CU-8693wp05t)


##### Refactoring

* move all consent relevant structures and procedures to @devowl-wp/cookie-consent-management (CU-8693n1cc5)
* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)</details>

<details><summary>Dependency updates @devowl-wp/react-utils 0.1.2</summary>


**_Purpose of dependency:_** _Provide various React utils, side effect free and tree shakeable._
##### Bug Fixes

* cookie banner cannot be accepted on old Safari browsers (CU-8693u1wzm)


##### Performance

* do no longer use webpackMode eager in favor of inline-require (CU-8693n1cc5)
* improve performance by not removing cookie banner from DOM after accepting for better INP in Google PageSpeed (CU-8693n1cc5)
* improve performance of applying consent and unblocking consent for better INP in Google PageSpeed (CU-8693n1cc5)
* improve Total Blocking Time in Page Speed Insights by yielding the main thread for TCF cookie banner (CU-8693n1cc5)
* lazy load data for the second layer / view of the cookie banner (CU-8693n1cc5)
* render shortcodes async and add lazy-require() webpack plugin (CU-8693cqz75)


##### Refactoring

* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.19.13</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Bug Fixes

* client property value is empty error message when using serialized siteurl option (CU-8693uhwd7)


##### Refactoring

* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)</details>

<details><summary>Dependency updates @devowl-wp/real-queue 0.5.13</summary>


**_Purpose of dependency:_** _Provide a promise-based queue system working in frontend for client and server tasks_
##### Refactoring

* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.18.1</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Continuous Integration

* readme-to-json parser did no longer work due to missing taxonomy_exists function (CU-8693wju7t)


##### Performance

* allow to parse big objects localized via wp_localize_script lazily (CU-8693n1cc5)
* do no longer use webpackMode eager in favor of inline-require (CU-8693n1cc5)
* use code splitting for the cookie banner and content blocker to reduce initial download time (CU-8693ubj9a)


##### Refactoring

* move all util functions to @devowl-wp/react-utils (CU-8693cqz75)</details>

<details><summary>Dependency updates @devowl-wp/web-html-element-interaction-recorder 0.2.22</summary>


**_Purpose of dependency:_** _Record and replay interactions on a given HTML element._
##### Performance

* improve Total Blocking Time in Page Speed Insights by inlining require statements (CU-8693n1cc5)</details>

<details><summary>Development dependency update @devowl-wp/continuous-integration 0.6.2</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Performance

* improve performance by not removing cookie banner from DOM after accepting for better INP in Google PageSpeed (CU-8693n1cc5)


##### Refactoring

* move all consent relevant structures and procedures to @devowl-wp/cookie-consent-management (CU-8693n1cc5)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.7</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Performance

* improve Total Blocking Time in Page Speed Insights by yielding the main thread for TCF cookie banner (CU-8693n1cc5)</details>

<details><summary>Development dependency update @devowl-wp/web-cookie-banner 0.4.1</summary>


**_Purpose of dependency:_** _Provide a scoped stylesheet, types and util functionality for a web cookie banner._
##### Performance

* improve Total Blocking Time in Page Speed Insights by inlining require statements (CU-8693n1cc5)
* lazy load data for the second layer / view of the cookie banner (CU-8693n1cc5)
* use code splitting for the cookie banner and content blocker to reduce initial download time (CU-8693ubj9a)


##### Styling

* reset anchor / link styles in cookie banner correctly when theme overwrites it (CU-8693wx9ec)</details>

<details><summary>Development dependency update @devowl-wp/web-scoped-css 0.2.14</summary>


**_Purpose of dependency:_** _Define a scoped stylesheet in JavaScript with performance in mind._
##### Performance

* improve Total Blocking Time in Page Speed Insights by inlining require statements (CU-8693n1cc5)
* use code splitting for the cookie banner and content blocker to reduce initial download time (CU-8693ubj9a)</details>

<details><summary>Development dependency update @devowl-wp/webpack-config 0.2.28</summary>


**_Purpose of dependency:_** _Webpack config builder for multiple ecosystems like standalone React frontends, Antd, Preact and WordPress._
##### Performance

* improve Total Blocking Time in Page Speed Insights by inlining require statements (CU-8693n1cc5)</details>





## 4.4.1 (2024-02-05)


### Bug Fixes

* accordions and list colors were not migrated successfully when TCF is active (CU-8693qpd7a)







# 4.4.0 (2024-02-05)


### Bug Fixes

* compatibility with UnitedThemes (CU-8693qm7f8)
* illegal mix of collations (CU-8693nwm9m)


### Features

* introduce Google Consent Mode (CU-apv5uu)


### Maintenance

* use non-docker URL with HTTPS in development environment to not bypass Traefik (CU-86939q6ce)


### Performance

* save one SQL SELECT query in WordPress admin dashboard (CU-86939q6ce)


### Refactoring

* introduce @devowl-wp/cookie-consent-management package (CU-apv5uu)
* introduce @devowl-wp/react-utils package (CU-8693nj8v6)
* move Google Consent Mode calculations to @devowl-wp/cookie-consent-management (CU-apv5uu)
* move some util methods to @devowl-wp/utils (CU-86939q6ce)


<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.9.0</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Features

* introduce Google Consent Mode (CU-apv5uu)


##### Refactoring

* introduce @devowl-wp/react-utils package (CU-8693nj8v6)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.16.4</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* compatibility with embed HTML tags (CU-8693p91em)


##### Refactoring

* move Google Consent Mode calculations to @devowl-wp/cookie-consent-management (CU-apv5uu)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.10.0</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Features

* introduce Google Consent Mode (CU-apv5uu)


##### Refactoring

* introduce @devowl-wp/react-utils package (CU-8693nj8v6)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.13.0</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Features

* introduce Google Consent Mode (CU-apv5uu)


##### Refactoring

* introduce @devowl-wp/react-utils package (CU-8693nj8v6)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.19.12</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Maintenance

* use non-docker URL with HTTPS in development environment to not bypass Traefik (CU-86939q6ce)


##### Performance

* save one SQL SELECT query in WordPress admin dashboard (CU-86939q6ce)


##### Refactoring

* move some util methods to @devowl-wp/utils (CU-86939q6ce)</details>

<details><summary>Dependency updates @devowl-wp/service-cloud-consumer 0.5.0</summary>


**_Purpose of dependency:_** _Consume service and blocker templates from service cloud_
##### Features

* introduce Google Consent Mode (CU-apv5uu)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.18.0</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Features

* introduce a new notice when a rate limited request was done to devowl.io backend services (CU-86939q6ce)


##### Maintenance

* use non-docker URL with HTTPS in development environment to not bypass Traefik (CU-86939q6ce)


##### Performance

* save one SQL SELECT query in WordPress admin dashboard (CU-86939q6ce)


##### Refactoring

* move some util methods to @devowl-wp/utils (CU-86939q6ce)</details>

<details><summary>Dependency updates @devowl-wp/web-html-element-interaction-recorder 0.2.21</summary>


**_Purpose of dependency:_** _Record and replay interactions on a given HTML element._
##### Refactoring

* introduce @devowl-wp/react-utils package (CU-8693nj8v6)</details>

<details><summary>Development dependency update @devowl-wp/web-cookie-banner 0.4.0</summary>


**_Purpose of dependency:_** _Provide a scoped stylesheet, types and util functionality for a web cookie banner._
##### Features

* introduce Google Consent Mode (CU-apv5uu)


##### Refactoring

* introduce @devowl-wp/react-utils package (CU-8693nj8v6)</details>





## 4.3.7 (2024-01-25)


### Bug Fixes

* check if service exists when reading services without privacy policy (CU-apv5uu)
* cookie banner did not load in customizer when using SG Optimizer JavaScript minification (CU-8693h1xfq)
* improved compatibility with PixelYourSite (CU-8692wdhdy)
* show a notice for successor templates which replace other templates (CU-869372jf7)


### Maintenance

* add security hashes (CU-861mmp30r)
* merge conflict (CU-869372jf7)
* update to antd@5 (CU-863gku332)
* wordpress part review 4 (CU-869372jf7)
* wordpress part review 5 (CU-869372jf7)


### Performance

* optimize the SQL query which deletes scanner results for a scanned URL (CU-8693h2quv)


<details><summary>Dependency updates @devowl-wp/api-real-cookie-banner 0.13.0</summary>


**_Purpose of dependency:_** _Shared typings for all Real Cookie Banner backend._
##### Bug Fixes

* added successorOfIdentifier field to services and content blocker templates (CU-869372jf7)
* extended client response for services and blocker successorOf (CU-869372jf7)
* introduce ruleNotice to content blockers (CU-86938uzfp)
* removed shouldRemoveTechnicalHandlingWhenOneOf (CU-8693g1maw)
* show a notice for successor templates which replace other templates (CU-869372jf7)


##### Features

* introduce Google Consent Mode (CU-8693g1maw)</details>

<details><summary>Dependency updates @devowl-wp/customize 1.11.11</summary>


**_Purpose of dependency:_** _Abstract utility for live preview (customize)_
##### Maintenance

* update to antd@5 (CU-863gku332)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.16.3</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* add autoplay to wistia.net (CU-8693jdhgk)
* extract external URL from inline script of TradeTracker (CU-8693jrh8f)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-unblocker 0.8.6</summary>


**_Purpose of dependency:_** _Unblock mechanism for @devowl-wp/headless-content-blocker with visual capabilities._
##### Bug Fixes

* compatibility with Flatsome UX builder and visual content blockers for video embeds (CU-8693jdhgk)


##### Maintenance

* update to antd@5 (CU-863gku332)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.9.10</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Maintenance

* update to antd@5 (CU-863gku332)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.12.5</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* show a notice for successor templates which replace other templates (CU-869372jf7)
* show rule notice above the rules textare in content blockers if one given (CU-86938uzfp)


##### Maintenance

* update to antd@5 (CU-863gku332)
* wordpress part review 4 (CU-869372jf7)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.19.11</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Bug Fixes

* allow to copy client UUID by hovering the installation type icon (CU-8693hv7vb)
* show a notice for successor templates which replace other templates (CU-869372jf7)


##### Maintenance

* update to antd@5 (CU-863gku332)</details>

<details><summary>Dependency updates @devowl-wp/real-queue 0.5.11</summary>


**_Purpose of dependency:_** _Provide a promise-based queue system working in frontend for client and server tasks_
##### Maintenance

* update to antd@5 (CU-863gku332)</details>

<details><summary>Dependency updates @devowl-wp/service-cloud-consumer 0.4.4</summary>


**_Purpose of dependency:_** _Consume service and blocker templates from service cloud_
##### Bug Fixes

* show a notice for successor templates which replace other templates (CU-869372jf7)
* show rule notice above the rules textare in content blockers if one given (CU-86938uzfp)


##### Maintenance

* merge conflict (CU-869372jf7)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.17.9</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* php error automatic conversion of false to array is deprecated (CU-apv5uu)
* show a notice for successor templates which replace other templates (CU-869372jf7)
* sometimes the WordPress REST API is contacted infinite when WP heartbeat is deactivated and login no longer valid (CU-8693jq17r)


##### Maintenance

* update to antd@5 (CU-863gku332)


##### Performance

* reduce bundle size by replacing sha-1 by a simple hash function (CU-apv5uu)</details>

<details><summary>Dependency updates @devowl-wp/web-html-element-interaction-recorder 0.2.20</summary>


**_Purpose of dependency:_** _Record and replay interactions on a given HTML element._
##### Bug Fixes

* sometimes the recorder registered two clicks with a delay of zero (CU-apv5uu)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.6</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Maintenance

* update to antd@5 (CU-863gku332)</details>

<details><summary>Development dependency update @devowl-wp/iso-codes 0.5.0</summary>


**_Purpose of dependency:_** _Enums and key value getters for all countries in different ISO code standards._
##### Bug Fixes

* remove unused duplicated translations of country name (CU-866av8d30)


##### Features

* data processing countries group for Automattic (CU-866av8d30)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.11</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Continuous Integration

* use project ID to read associated merge request for pipeline (CU-apv5uu)</details>

<details><summary>Development dependency update @devowl-wp/web-cookie-banner 0.3.9</summary>


**_Purpose of dependency:_** _Provide a scoped stylesheet, types and util functionality for a web cookie banner._
##### Maintenance

* update to antd@5 (CU-863gku332)</details>

<details><summary>Development dependency update @devowl-wp/web-scoped-css 0.2.13</summary>


**_Purpose of dependency:_** _Define a scoped stylesheet in JavaScript with performance in mind._
##### Bug Fixes

* allow updating variables before stylesheet is created (CU-apv5uu)


##### Maintenance

* update to antd@5 (CU-863gku332)</details>

<details><summary>Development dependency update @devowl-wp/webpack-config 0.2.27</summary>


**_Purpose of dependency:_** _Webpack config builder for multiple ecosystems like standalone React frontends, Antd, Preact and WordPress._
##### Maintenance

* update to antd@5 (CU-863gku332)</details>





## 4.3.6 (2024-01-18) (not released)

**Note:** This version of the package has not (yet) been released publicly. This happens if changes have been made in dependencies that do not affect this package (e.g. changes for the development of the package). The changes will be rolled out with the next official update.

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Development dependency update @devowl-wp/continuous-integration 0.6.0</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Bug Fixes

* output ci summary for review application URLs for traefik v2 (CU-2rjtd0)


##### Continuous Integration

* automatically retry to fetch the git repository three times when there is a temporary error (CU-8693j5ngt)
* deploy backends in production to docker-host-6.owlsrv.de (CU-2rjtd0)


##### Features

* introduce public-changelogs command (CU-2mjxz4x)</details>

<details><summary>Development dependency update @devowl-wp/monorepo-utils 0.2.0</summary>


**_Purpose of dependency:_** _Predefined monorepo utilities and tasks._
##### Features

* introduce public-changelogs command (CU-2mjxz4x)</details>





## 4.3.5 (2024-01-08)


### Bug Fixes

* whitespace above visual content blocker when using OceanWP responsive video embeds (CU-8693fg0uu)
* whitespace below visual content blocker when using Astra Theme video embeds (CU-8693fg0uu)


<details><summary>Dependency updates @devowl-wp/headless-content-unblocker 0.8.4</summary>


**_Purpose of dependency:_** _Unblock mechanism for @devowl-wp/headless-content-blocker with visual capabilities._
##### Bug Fixes

* awin ad within a link is not correctly unblocked, only on reload (CU-8693ff6kr)</details>





## 4.3.4 (2024-01-05)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.16.1</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* never touch internal links when forwarding TCF consent string via link (CU-8693cyetg)</details>





## 4.3.3 (2024-01-04)


### Bug Fixes

* compatibility with PT Novo Shortcodes (CU-8693dg00y)
* correctly add GDPR TCF URL parameters to e.g. Awin Affiliate links (CU-8693cyetg)
* scanner does not work when WP Meteor is active (CU-8693e1ap0)
* uncaught TypeError: explode(): Argument [#2](https://git.devowl.io/devowlio/devowl-wp/issues/2) () must be of type string, array given after importing TCF content blcker (CU-8693dmfxd)


<details><summary>Dependency updates @devowl-wp/api-real-cookie-banner 0.12.7</summary>


**_Purpose of dependency:_** _Shared typings for all Real Cookie Banner backend._
##### Fix

* Admin-UI - Add import/export functionality (CU-86934facb)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.8.4</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Bug Fixes

* transform also anchor links with GDPR TCF URL parameters (CU-8693cyetg)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.16.0</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* correctly deduplicate Elementor Forms with Google reCaptcha (CU-8693e1f9x)


##### Features

* introduce new plugin hook setup() (CU-8693cyetg)
* introduce TcfForwardGdprStringInUrl plugin (CU-8693cyetg)</details>

<details><summary>Dependency updates @devowl-wp/real-queue 0.5.9</summary>


**_Purpose of dependency:_** _Provide a promise-based queue system working in frontend for client and server tasks_
##### Performance

* scanner does extra WP REST API request  when WP Meteor is active (CU-8693e1ap0)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.17.7</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Build Process

* correctly autoload composer package files autoload.files per plugin (CU-8693dhuhv)</details>





## 4.3.2 (2023-12-22)


### Bug Fixes

* searching for TCF vendors in Content Blockers did not work by vendor name (CU-8693cun4j)


### Performance

* migrate template upgrade notice to the new notice system to use cache (CU-869372jf7)


<details><summary>Dependency updates @devowl-wp/api-real-cookie-banner 0.12.6</summary>


**_Purpose of dependency:_** _Shared typings for all Real Cookie Banner backend._
##### Bug Fixes

* several bugs in release view, introduce release status as calculated field for release view (CU-86936mue3)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-unblocker 0.8.3</summary>


**_Purpose of dependency:_** _Unblock mechanism for @devowl-wp/headless-content-blocker with visual capabilities._
##### Bug Fixes

* adsbygoogle.push() error: Fluid responsive ads must be at least 250px wide (CU-8693cxm1p)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.12.1</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* do not output ad networks for vendor configurations with deleted vendors as this leads to TypeError (CU-866aw8zqu)</details>

<details><summary>Dependency updates @devowl-wp/tcf-vendor-list-normalize 0.3.7</summary>


**_Purpose of dependency:_** _Download and persist vendor-list.json to database_
##### Bug Fixes

* function wpdb::prepare was called incorrectly when using TCF in German (CU-8693cun4j)</details>





## 4.3.1 (2023-12-21)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Dependency updates @devowl-wp/utils 1.17.6</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* fatal error after latest update as WordPress stubs were no longer compatible with WordPress < 6.2 (CU-8693cg7cp)</details>





# 4.3.0 (2023-12-21)


### Bug Fixes

* cannot access the settings page when switching from free to PRO version (CU-8693ccu6u)
* compatibility with Elementor media carousels (CU-8693bahqc)
* correctly implement the usage of rules and rule groups (CU-8693a7gmn)
* the WooCommerce Google Analytics Integration plugin was only active when standard tracking was enabled (CU-86935hudw)


### Features

* improved compatibility with latest Google Adsense requirements and TCF requirements (CU-866aw8zqu)


### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)


<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.15.2</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Bug Fixes

* improved compatibility with HummingBird (CU-8692zgd6n)


##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/customize 1.11.7</summary>


**_Purpose of dependency:_** _Abstract utility for live preview (customize)_
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/deliver-anonymous-asset 0.2.59</summary>


**_Purpose of dependency:_** _Provide a functionality to deliver assets anonymous_
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/fast-html-tag 0.9.10</summary>


**_Purpose of dependency:_** _Find tags within HTML content and modify it in speed of light_
##### Bug Fixes

* allow attributes with numerics in selector syntax (CU-8693a7gmn)


##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/freemium 1.3.70</summary>


**_Purpose of dependency:_** _Make your plugin to a freemium plugin with predefined Envato support_
##### Bug Fixes

* cannot access the settings page when switching from free to PRO version (CU-8693ccu6u)


##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.15.2</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* correctly implement the usage of rules and rule groups (CU-8693a7gmn)


##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)


##### Testing

* update available content blockers from service cloud</details>

<details><summary>Dependency updates @devowl-wp/multilingual 1.12.7</summary>


**_Purpose of dependency:_** _Provide helper functionality for multilingual plugins like WPML and PolyLang_
##### Bug Fixes

* undefined array key in NavMenuList.php file (CU-8693bd1ku)


##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.9.5</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* list of consents with visual content blockers did no longer work (CU-86939w8af)


##### Performance

* do not encode TCModel when initial cookie banner is loaded (CU-8693cf1zr)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.12.0</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Features

* improved compatibility with latest Google Adsense requirements and TCF requirements (CU-866aw8zqu)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.19.7</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/real-queue 0.5.7</summary>


**_Purpose of dependency:_** _Provide a promise-based queue system working in frontend for client and server tasks_
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/real-utils 1.12.7</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/service-cloud-consumer 0.4.0</summary>


**_Purpose of dependency:_** _Consume service and blocker templates from service cloud_
##### Features

* improved compatibility with latest Google Adsense requirements and TCF requirements (CU-866aw8zqu)


##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/tcf-vendor-list-normalize 0.3.5</summary>


**_Purpose of dependency:_** _Download and persist vendor-list.json to database_
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.17.5</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/composer-licenses 0.1.15</summary>


**_Purpose of dependency:_** _Helper functionalities for your composer project to validate licenses and generate a disclaimer._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/continuous-integration 0.5.1</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Bug Fixes

* correctly check for the SHA of the latest master branch (CU-8693bzjkb)


##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.5</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/phpcs-config 0.1.14</summary>


**_Purpose of dependency:_** _Predefined functionalities for PHPCS._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/phpunit-config 0.1.12</summary>


**_Purpose of dependency:_** _Predefined functionalities for PHPUnit._
##### Maintenance

* upgrade to PHP 8.2 including composer packages (CU-arua06)</details>

<details><summary>Development dependency update @devowl-wp/webpack-config 0.2.25</summary>


**_Purpose of dependency:_** _Webpack config builder for multiple ecosystems like standalone React frontends, Antd, Preact and WordPress._
##### Bug Fixes

* use correct name for long term caching for extracted CSS files (CU-8693bc0d2)</details>





## 4.2.1 (2023-12-19) (not released)

**Note:** This version of the package has not (yet) been released publicly. This happens if changes have been made in dependencies that do not affect this package (e.g. changes for the development of the package). The changes will be rolled out with the next official update.

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.


<details><summary>Development dependency update @devowl-wp/continuous-integration 0.5.0</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Continuous Integration

* rotate transaction_ids_by_target_id every 14 days (CU-86937dv6w)
* upload did not work with newer Debian version, disable StrictHostKeyChecking for lftp upload (CU-86937dw3d)


##### Features

* allow to skip publish of packages by regular expression in merge request description with target branch master (CU-8693bzjkb)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.8.1</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* copy files always once and overwrite existing files (CU-8693bq3nh)</details>

<details><summary>Development dependency update @devowl-wp/monorepo-utils 0.1.13</summary>


**_Purpose of dependency:_** _Predefined monorepo utilities and tasks._
##### Bug Fixes

* show skipped publish packages as those in the generated CHANGELOG.md files (CU-8693bzjkb)</details>





# 4.2.0 (2023-12-15)


### Bug Fixes

* avoid getting errors when could not update the meta value of isProviderCurrentWebsite in database error (CU-86935hrd1)
* compatibility with Beaver Builder and the option to render assetes inline (CU-8693992x4)
* do not show contact form in cookie banner after removing the first selection (CU-869382qk5)
* do not show licensing tab when user does not have enough capabilities (CU-86938n5gk)
* map all edit_rcb... capabilities to manage_real_cookie_banner (CU-86938n5gk)
* show creation date of templates instead of version number in Differing from template popup (CU-86936mue3)
* when TCF is active, it sometimes requests new consent every day (CU-86939gwcj)


### Build Process

* do not expose de@formal and nl@formal to Weblate (CU-86938ba8a)


### Features

* allow to configure a privacy manager role by adding manage_real_cookie_banner capability (CU-86938n5gk)
* allow to set required purposes for TCF content blockers (CU-86933edy3)


### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)


<details><summary>Dependency updates @devowl-wp/api-real-cookie-banner 0.12.3</summary>


**_Purpose of dependency:_** _Shared typings for all Real Cookie Banner backend._
##### Bug Fixes

* added delete button to media images (CU-8692wfhgk)
* added possibility for release dependency checks WIP (CU-86932dder)</details>

<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.15.0</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Bug Fixes

* compatibility with Cloudflare Rocket Loader (CU-86938z54n)


##### Features

* compatibility with Debloat - Remove Unused CSS, Optimize JS cache plugin (CU-86939h8my)


##### Performance

* page speed insights score when Remove Unused CSS is used in WP Rocket and no cookie banner animations are shown (CU-86939bd3z)


##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.8.0</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Bug Fixes

* when TCF is active, it sometimes requests new consent every day (CU-86939gwcj)


##### Features

* allow to set required purposes for TCF content blockers (CU-86933edy3)</details>

<details><summary>Dependency updates @devowl-wp/customize 1.11.5</summary>


**_Purpose of dependency:_** _Abstract utility for live preview (customize)_
##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/deliver-anonymous-asset 0.2.57</summary>


**_Purpose of dependency:_** _Provide a functionality to deliver assets anonymous_
##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/freemium 1.3.68</summary>


**_Purpose of dependency:_** _Make your plugin to a freemium plugin with predefined Envato support_
##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.15.0</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* compatibility with Beaver Builder and the option to render assetes inline (CU-8693992x4)


##### Features

* allow to negate rules with a prefixed exclamation mark (CU-869387nbx)
* allow to set required purposes for TCF content blockers (CU-86933edy3)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-unblocker 0.8.0</summary>


**_Purpose of dependency:_** _Unblock mechanism for @devowl-wp/headless-content-blocker with visual capabilities._
##### Features

* allow to set required purposes for TCF content blockers (CU-86933edy3)</details>

<details><summary>Dependency updates @devowl-wp/multilingual 1.12.5</summary>


**_Purpose of dependency:_** _Provide helper functionality for multilingual plugins like WPML and PolyLang_
##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.9.3</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* animation out did no longer work for cookie banner in some cases (CU-869383vck)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.11.0</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* show creation date of templates instead of version number in Differing from template popup (CU-86936mue3)


##### Features

* allow to set required purposes for TCF content blockers (CU-86933edy3)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.19.5</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/real-queue 0.5.5</summary>


**_Purpose of dependency:_** _Provide a promise-based queue system working in frontend for client and server tasks_
##### Bug Fixes

* allow to configure capabilities instead of hardcoded edit_posts (CU-86938n5gk)


##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/real-utils 1.12.5</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/service-cloud-consumer 0.3.7</summary>


**_Purpose of dependency:_** _Consume service and blocker templates from service cloud_
##### Bug Fixes

* show creation date of templates instead of version number in Differing from template popup (CU-86936mue3)</details>

<details><summary>Dependency updates @devowl-wp/tcf-vendor-list-normalize 0.3.3</summary>


**_Purpose of dependency:_** _Download and persist vendor-list.json to database_
##### Bug Fixes

* when TCF is active, it sometimes requests new consent every day (CU-86939gwcj)


##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.17.3</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* allow to configure capabilities via Activator#registerCapabilities (CU-86938n5gk)
* compatibility with Cloudflare Rocket Loader (CU-86938z54n)


##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.8.0</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* allow to configure branch settings via root package.json instead of hardcoded (CU-86938ba8a)
* respect branch settings in weblate-prune-deleted-branches CLI command (CU-86938ba8a)
* show a hint when a language is in Weblate but not configured in package.json in weblate-status command (CU-86938ba8a)


##### Build Process

* do not expose de@formal and nl@formal to Weblate (CU-86938ba8a)


##### Features

* allow to exclude locales from projects with overrides.excludeLocales in package.json settings (CU-86938ba8a)


##### Refactoring

* use a class instead of an object for continuous localization settings (CU-86938ba8a)</details>





## 4.1.2 (2023-11-28)


### Bug Fixes

* allow to show cookie banner also on wp-login.php page when body has class force-cookie-banner (CU-869379120)
* flickering when deactivating WPML/PolyLang and trying to configure footer links (CU-86937an80)


### Refactoring

* remove all cypress dependencies and tests (CU-8692yek74)


### Testing

* introduce @devowl-wp/playwright-utils with smoke test functionality (CU-8692yek74)


<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.7.2</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Bug Fixes

* allow to show cookie banner also on wp-login.php page when body has class force-cookie-banner (CU-869379120)</details>

<details><summary>Dependency updates @devowl-wp/tcf-vendor-list-normalize 0.3.2</summary>


**_Purpose of dependency:_** _Download and persist vendor-list.json to database_
##### Performance

* a huge TCF vendors table with outdated vendor list information can lead to high CPU / database usage (CU-869372e3a)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.17.2</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Refactoring

* remove all cypress dependencies and tests (CU-8692yek74)</details>

<details><summary>Development dependency update @devowl-wp/continuous-integration 0.4.5</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Refactoring

* remove all cypress dependencies and tests (CU-8692yek74)


##### Testing

* introduce @devowl-wp/playwright-utils with smoke test functionality (CU-8692yek74)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.4</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Refactoring

* remove all cypress dependencies and tests (CU-8692yek74)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.9</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Bug Fixes

* update Gitlab YAML typings (CU-8692yek74)</details>





## 4.1.1 (2023-11-24)


### Bug Fixes

* compatibility with Thrive Architect Lightbox and performance when many lightboxes on the page (CU-869306a74)
* do not block content in OptimizeBuilder (CU-1ydtzkv)
* manager Google Tag Manager can be created through scanner even marked as Disabled (CU-86936qzwq)


<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.14.0</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Features

* compatibility with OptimizePress cache (CU-1ydtzkv)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.7.1</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Bug Fixes

* uncaught exception in frontend when using PixelYourSite template (CU-86936r76h)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.14.7</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* do not find link with rel me and alternate as external URL in scanner (CU-2f7ccf4)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.9.1</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* customizer did not load in some cases (CU-86936qctz)</details>

<details><summary>Dependency updates @devowl-wp/service-cloud-consumer 0.3.5</summary>


**_Purpose of dependency:_** _Consume service and blocker templates from service cloud_
##### Bug Fixes

* manager Google Tag Manager can be created through scanner even marked as Disabled (CU-86936qzwq)</details>

<details><summary>Development dependency update @devowl-wp/continuous-integration 0.4.4</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Continuous Integration

* show inconsistent translations always in translation status (CU-86932cagc)
* validate production docker compose config on compose YAML changes (CU-86934wg6z)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.9</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* do find propagated string translations from other components when merging a branch to another (CU-86932nwn8)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.8</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Bug Fixes

* also delete skipped pipelines and pipelines of deleted branches</details>





# 4.1.0 (2023-11-22)


### Bug Fixes

* 404 error when navigating too fast from cookies tab (CU-86935hjf1)
* allow to reset fields for templates even without update (CU-86930f9du)
* when requesting new consent the cookie banner was visible on privacy policy page (CU-869357t9d)


### Features

* allow to create multiple TCF vendors in batch by using table checkboxes (CU-86930ub71)
* introduce TCF 2.2 / GVL v3 compatibility (CU-863gt04va)


### Refactoring

* move TCF vendor creation form and list view to @devowl-wp/react-cookie-banner-admin (CU-86930ub71)


<details><summary>Dependency updates @devowl-wp/api-real-cookie-banner 0.12.0</summary>


**_Purpose of dependency:_** _Shared typings for all Real Cookie Banner backend._
##### Documentation

* added description to distinct langs (CU-86934ynxa)


##### Features

* compose GVL v3 and purposes v3 for TCF 2.2 (CU-863gt04va)
* introduce TCF 2.2 / GVL v3 compatibility (CU-863gt04va)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.7.0</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Bug Fixes

* when requesting new consent the cookie banner was visible on privacy policy page (CU-869357t9d)


##### Features

* introduce TCF 2.2 / GVL v3 compatibility (CU-863gt04va)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.9.0</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* accessibility score in PageSpeed Insights for the language switcher in cookie banner (CU-86935zy2c)


##### Features

* introduce TCF 2.2 / GVL v3 compatibility (CU-863gt04va)


##### Performance

* wait for all suspended components to be mounted and then show cookie banner to avoid CLS (CU-8693572fn)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.10.0</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* 404 error when navigating too fast from cookies tab (CU-86935hjf1)
* allow to reset fields for templates even without update (CU-86930f9du)


##### Features

* allow to create multiple TCF vendors in batch by using table checkboxes (CU-86930ub71)
* compose GVL v3 and purposes v3 for TCF 2.2 (CU-863gt04va)
* introduce TCF 2.2 / GVL v3 compatibility (CU-863gt04va)


##### Refactoring

* move TCF vendor creation form and list view to @devowl-wp/react-cookie-banner-admin (CU-86930ub71)</details>

<details><summary>Dependency updates @devowl-wp/tcf-vendor-list-normalize 0.3.0</summary>


**_Purpose of dependency:_** _Download and persist vendor-list.json to database_
##### Features

* introduce TCF 2.2 / GVL v3 compatibility (CU-863gt04va)


##### Maintenance

* fetch GVL v3 instead of v2 for TCF 2.2 support (CU-863gt04va)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.17.0</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Features

* introduce batch requests (CU-86930ub71)
* introduce TCF 2.2 / GVL v3 compatibility (CU-863gt04va)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.8</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* machine translate all unfinished strings as changed strings are not detected with nottranslated (CU-86932nwn8)</details>





## 4.0.1 (2023-11-16)


### Maintenance

* fix non-ASCII characters in POT msg strings (CU-86932nwn8)


<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.13.3</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Bug Fixes

* compatibility with WP Meteor optimization plugin (CU-86933j1zb)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.14.6</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* podigee player did not get blocked correctly (CU-86934av6a)</details>

<details><summary>Dependency updates @devowl-wp/multilingual 1.12.1</summary>


**_Purpose of dependency:_** _Provide helper functionality for multilingual plugins like WPML and PolyLang_
##### Maintenance

* fix non-ASCII characters in POT msg strings (CU-86932nwn8)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.8.3</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* use data-nosnippet to avoid banner texts in SEO snippets (CU-86934vczd)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.9.4</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Maintenance

* fix non-ASCII characters in POT msg strings (CU-86932nwn8)</details>

<details><summary>Dependency updates @devowl-wp/real-utils 1.12.1</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Maintenance

* fix non-ASCII characters in POT msg strings (CU-86932nwn8)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.16.1</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* compatibility with WP Meteor optimization plugin (CU-86933j1zb)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.7</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Bug Fixes

* always use auto_source=others in Weblate autotranslate to avoid picking inconsistent strings across projects (CU-86932nwn8)
* do not fuzzy autotranslate machine translated strings (CU-86932nwn8)
* use auto translate others instead of download and upload ZIP when creating feature branch in Weblate (CU-86932nwn8)


##### Reverts

* back to ZIP download/upload as it is faster than autotranslate with others (CU-86932nwn8)</details>





# 4.0.0 (2023-11-07)


### Bug Fixes

* correct available translations in README.txt (CU-2gfb42y)
* reload checklist when privacy policy text suggestion were updated (CU-86932cagc)
* remote language codes for cs, da and sv (CU-2gfb42y)
* typo (CU-2gfb42y)


### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


### Continuous Integration

* enable machine translation for various languages (CU-2gfb42y)
* translation completeness thresholds defined for main languages (CU-861n4aer5)


### Documentation

* add synonyms of GDPR and ePrivacy Directive in README.txt (CU-2gfb42y)
* localize links in README.txt (CU-2gfb42y)
* rework wordpress.org plugin description for v4.0 (CU-861n7amw6)


### Features

* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)


### Maintenance

* add legal-text to some texts (CU-2gfb42y)
* add screenshots to v4 upgrade wizard (CU-2gfb42y)
* disable minimal translations in favor of full translations (CU-2gfb42y)
* merge conflict resolution (CU-2gfb42y)
* minimum required PHP version 7.4 and WP version 5.8 (CU-arvdr3)
* move translations of README.txt for wordpress.org from repository to continuous localization (CU-861n8mnx8)
* replace GDPR link to gdpr-info.eu with gdpr-text.com as source (CU-2gfb42y)
* replace go-link to GDPR text with direct link (CU-2gfb42y)


### Styling

* make images float right correctly in upgrade wizard (CU-861n7any3)


### BREAKING CHANGES

* We are happy to announce that we have now reached
another Real Cookie Banner milestone with version 4.0. Read more about
it here https://devowl.io/2023/real-cookie-banner-4-0/.


<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.13.2</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Maintenance

* add de@informal with threshold 100 in continuous localization (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/customize 1.11.0</summary>


**_Purpose of dependency:_** _Abstract utility for live preview (customize)_
##### Bug Fixes

* remote language codes for cs, da and sv (CU-2gfb42y)


##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Continuous Integration

* enable machine translation for various languages (CU-2gfb42y)
* translation completeness thresholds defined for main languages (CU-861n4aer5)


##### Features

* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/deliver-anonymous-asset 0.2.52</summary>


**_Purpose of dependency:_** _Provide a functionality to deliver assets anonymous_
##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Maintenance

* add de@informal with threshold 100 in continuous localization (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/fast-html-tag 0.9.6</summary>


**_Purpose of dependency:_** _Find tags within HTML content and modify it in speed of light_
##### Bug Fixes

* fatal error: Uncaught TypeError: array_walk_recursive(): Argument [#1](https://git.devowl.io/devowlio/devowl-wp/issues/1) () must be of type array, null given</details>

<details><summary>Dependency updates @devowl-wp/freemium 1.3.63</summary>


**_Purpose of dependency:_** _Make your plugin to a freemium plugin with predefined Envato support_
##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Maintenance

* add de@informal with threshold 100 in continuous localization (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/multilingual 1.12.0</summary>


**_Purpose of dependency:_** _Provide helper functionality for multilingual plugins like WPML and PolyLang_
##### Bug Fixes

* remote language codes for cs, da and sv (CU-2gfb42y)


##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Continuous Integration

* enable machine translation for various languages (CU-2gfb42y)
* translation completeness thresholds defined for main languages (CU-861n4aer5)


##### Features

* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.9.3</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* reload checklist when privacy policy text suggestion were updated (CU-86932cagc)
* typo (CU-2gfb42y)


##### Maintenance

* add legal-text to some texts (CU-2gfb42y)
* replace GDPR link to gdpr-info.eu with gdpr-text.com as source (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.19.0</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Bug Fixes

* remote language codes for cs, da and sv (CU-2gfb42y)


##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Continuous Integration

* enable machine translation for various languages (CU-2gfb42y)
* translation completeness thresholds defined for main languages (CU-861n4aer5)


##### Features

* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)


##### Maintenance

* add legal-text to some texts (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/real-queue 0.5.0</summary>


**_Purpose of dependency:_** _Provide a promise-based queue system working in frontend for client and server tasks_
##### Bug Fixes

* remote language codes for cs, da and sv (CU-2gfb42y)


##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Continuous Integration

* enable machine translation for various languages (CU-2gfb42y)
* translation completeness thresholds defined for main languages (CU-861n4aer5)


##### Features

* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/real-utils 1.12.0</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Bug Fixes

* remote language codes for cs, da and sv (CU-2gfb42y)


##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Continuous Integration

* enable machine translation for various languages (CU-2gfb42y)
* translation completeness thresholds defined for main languages (CU-861n4aer5)


##### Features

* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)


##### Maintenance

* add legal-text to some texts (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/service-cloud-consumer 0.3.3</summary>


**_Purpose of dependency:_** _Consume service and blocker templates from service cloud_
##### Bug Fixes

* typo (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/tcf-vendor-list-normalize 0.2.61</summary>


**_Purpose of dependency:_** _Download and persist vendor-list.json to database_
##### Build Process

* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Maintenance

* add de@informal with threshold 100 in continuous localization (CU-2gfb42y)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.16.0</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* remote language codes for cs, da and sv (CU-2gfb42y)


##### Build Process

* remove local language files from built ZIP file and use remote files (CU-861n4ahzb)
* set @automattic/interpolate-components as enforced check in weblate (CU-2gfb4w6)
* set php-format as enforced check in weblate (CU-2gfb4w6)


##### Continuous Integration

* enable machine translation for various languages (CU-2gfb42y)
* translation completeness thresholds defined for main languages (CU-861n4aer5)


##### Features

* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)
* translations in Spanish, French, Italian, Dutch, Polish, Danish, Swedish, Norwegian, Czech, Portuguese and Romanian (CU-2gfb42y)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.6</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Continuous Integration

* show inconsistent translations always in translation status (CU-86932cagc)


##### Maintenance

* machine translated strings should be trusted and not set as fuzzy in Weblate (CU-2gfb42y)</details>





## 3.13.3 (2023-11-02)


### Bug Fixes

* banner presets could not be applied in some cases (CU-861n7amqx)
* passing null to parameter [#1](https://git.devowl.io/devowlio/devowl-wp/issues/1) () of type string is deprecated
* some Custom Post Types lead to /wp-admin scan results (CU-86930u18a)


### Maintenance

* tested up to WordPress 6.4 (CU-8692zwmth)


<details><summary>Dependency updates @devowl-wp/fast-html-tag 0.9.5</summary>


**_Purpose of dependency:_** _Find tags within HTML content and modify it in speed of light_
##### Bug Fixes

* commented out inline script should not get mixed with external scripts when blocking content (CU-869314r0e)
* passing null to parameter [#1](https://git.devowl.io/devowlio/devowl-wp/issues/1) () of type string is deprecated</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.14.5</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Bug Fixes

* commented out inline script should not get mixed with external scripts when blocking content (CU-869314r0e)
* do not override script type attribute value as some services are not compatible with the standards (CU-8692xx4j4)
* passing null to parameter [#1](https://git.devowl.io/devowlio/devowl-wp/issues/1) () of type string is deprecated
* passing null to parameter [#1](https://git.devowl.io/devowlio/devowl-wp/issues/1) () of type string is deprecated (CU-86930u18a)


##### Documentation

* update PHPDoc for selector syntax functions</details>

<details><summary>Dependency updates @devowl-wp/multilingual 1.11.3</summary>


**_Purpose of dependency:_** _Provide helper functionality for multilingual plugins like WPML and PolyLang_
##### Bug Fixes

* passing null to parameter [#1](https://git.devowl.io/devowlio/devowl-wp/issues/1) () of type string is deprecated</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.8.2</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* in some cases the button links are not sticky to the bottom in cookie banner (CU-86931j3bm)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.9.2</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* privacy policy text is not copyable when content is empty in case of page builder usage (e.g. Oxygen, CU-86930u18a)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.18.3</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Bug Fixes

* passing null to parameter [#1](https://git.devowl.io/devowlio/devowl-wp/issues/1) () of type string is deprecated</details>

<details><summary>Development dependency update @devowl-wp/web-scoped-css 0.2.7</summary>


**_Purpose of dependency:_** _Define a scoped stylesheet in JavaScript with performance in mind._
##### Bug Fixes

* in some cases the button links are not sticky to the bottom in cookie banner (CU-86931j3bm)</details>





## 3.13.2 (2023-10-27)


### Bug Fixes

* compatibility with JetMenu mobile sidebar (CU-8693098ra)
* teachings cannot be customized in customizer when only list of services is enabled (CU-8692yu9ka)


<details><summary>Dependency updates @devowl-wp/api-real-cookie-banner 0.11.5</summary>


**_Purpose of dependency:_** _Shared typings for all Real Cookie Banner backend._
##### Bug Fixes

* added dataProcessingInCountriesSpecialTreatments as extendable attribute (CU-8692z4h5f)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.6.2</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Bug Fixes

* the Google Tag Manager (GTM) receives opt-ins in Data Layer too late (CU-8692xt11g)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.14.4</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Documentation

* better explain the expression schema for ScriptInlineJsonBlocker (CU-8693098ra)


##### Maintenance

* remove unwanted error_log (CU-8693098ra)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.8.1</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* open cookie banner when initial URL contains #consent-change (CU-8692zqve3)


##### Performance

* use dedicated <Suspense component for each lazy loaded component (CU-86930ajxx)</details>

<details><summary>Development dependency update @devowl-wp/api 0.5.13</summary>


**_Purpose of dependency:_** _Shared typings for all Node.js backends and frontends._
##### Documentation

* update JSDoc, make some methods private and extend some typings (CU-866avtm7z)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.7</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Continuous Integration

* purge master pipelines after 90 days instead of 360</details>

<details><summary>Development dependency update @devowl-wp/web-cookie-banner 0.3.2</summary>


**_Purpose of dependency:_** _Provide a scoped stylesheet, types and util functionality for a web cookie banner._
##### Bug Fixes

* font color on hover does not get changed for buttons in cookie banner (CU-869305hpq)</details>





## 3.13.1 (2023-10-17)


### Bug Fixes

* edit form for content blockers resulted in blank screen (CU-8692xmztw)


<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.13.0</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Features

* compatibility with Perfmatters Remove Unused CSS functionality (CU-8692x4h03)</details>





# 3.13.0 (2023-10-12)


### Bug Fixes

* add notice about explicit consent mode when Pixel Manager for WooCommerce is active (CU-1raqwk8)
* checklist item about privacy policy does not get updated (CU-866ay8jeb)
* compatibility with SuperFly Menu plugin (CU-8692wzd25)
* compatibility with WoodMart theme and scanner (CU-861mbpq2x)
* dashboard showed incorrect, swapped count of draft and disabled services (CU-866aybq9e)
* do not find YouTube in scanner for SeoPress localized variable
* do not show warning about preview images if no one gets imported for content blockers (CU-866aybq9e)
* improved compatibility to PixelYourSite (CU-8692wdhdy)
* improved magnificPopup compatibility as arrows in galleries are not rendered (CU-861n86a5n)
* wrong minimal translation in Italian (CU-866ayck5z)


### Build Process

* composer.lock had same content-hash accross some projects (CU-866aybq9e)


### Features

* introduce age notice age limit (CU-866awy2fr)


### Maintenance

* comma-separated list of caching plugins with space (CU-866aybq9e)
* introduce new developer action RCB/Templates/TechnicalHandlingIntegration (CU-1raqwk8)
* major update apidoc (CU-3cj43t)
* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update react-router-dom (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)
* prepare upgrade wizard for v4 release (CU-861n7amqx)


<details><summary>Dependency updates @devowl-wp/api-real-cookie-banner 0.11.4</summary>


**_Purpose of dependency:_** _Shared typings for all Real Cookie Banner backend._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/cache-invalidate 1.12.26</summary>


**_Purpose of dependency:_** _Provide a single entry point to trigger cache invalidation of known caching plugins_
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/cookie-consent-web-client 0.6.1</summary>


**_Purpose of dependency:_** _Apply cookies consent (opt-in, opt-out) to the current webpage._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* remove supports-color, update focusable-selectors react-quill react-codemirror2 js-cookie (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/customize 1.10.5</summary>


**_Purpose of dependency:_** _Abstract utility for live preview (customize)_
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)
* prepare upgrade wizard for v4 release (CU-861n7amqx)</details>

<details><summary>Dependency updates @devowl-wp/deliver-anonymous-asset 0.2.50</summary>


**_Purpose of dependency:_** _Provide a functionality to deliver assets anonymous_
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/fast-html-tag 0.9.3</summary>


**_Purpose of dependency:_** _Find tags within HTML content and modify it in speed of light_
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/freemium 1.3.61</summary>


**_Purpose of dependency:_** _Make your plugin to a freemium plugin with predefined Envato support_
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-blocker 0.14.3</summary>


**_Purpose of dependency:_** _Block HTML content by URLs and selector syntax_
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/headless-content-unblocker 0.7.4</summary>


**_Purpose of dependency:_** _Unblock mechanism for @devowl-wp/headless-content-blocker with visual capabilities._
##### Bug Fixes

* do not deduplicate content blocker for confirm selector syntax function (CU-866axjayz)


##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/multilingual 1.11.1</summary>


**_Purpose of dependency:_** _Provide helper functionality for multilingual plugins like WPML and PolyLang_
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner 0.8.0</summary>


**_Purpose of dependency:_** _Provide UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* do not replace minAge and legalBasis variables in cookie banner texts so they work with TranslatePress editor (CU-866awy2fr)


##### Features

* introduce age notice age limit (CU-866awy2fr)


##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/react-cookie-banner-admin 0.9.0</summary>


**_Purpose of dependency:_** _Provide admin UI for a cookie banner and content blocker for multiple services._
##### Bug Fixes

* inconsistent wording in service overview (CU-866aybqhm)
* isDemoEnv was not used correctly (CU-31976ru)
* when a service is configured essential show the unsafe-countries notice depending on calculated unsafe countries (CU-866aybq9e)


##### Features

* introduce age notice age limit (CU-866awy2fr)


##### Maintenance

* introduce new developer action RCB/Templates/TechnicalHandlingIntegration (CU-1raqwk8)
* major update react-router-dom (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/real-product-manager-wp-client 1.18.1</summary>


**_Purpose of dependency:_** _A WordPress client for Real Product Manager_
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)
* prepare upgrade wizard for v4 release (CU-861n7amqx)</details>

<details><summary>Dependency updates @devowl-wp/real-queue 0.4.49</summary>


**_Purpose of dependency:_** _Provide a promise-based queue system working in frontend for client and server tasks_
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/real-utils 1.11.13</summary>


**_Purpose of dependency:_** _Create cross-selling ads, about page, rating and newsletter input for WP Real plugins._
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/resolve-none-computed-style 1.1.23</summary>


**_Purpose of dependency:_** _Read the actually applied CSS property value instead of the calculated one._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/service-cloud-consumer 0.3.1</summary>


**_Purpose of dependency:_** _Consume service and blocker templates from service cloud_
##### Maintenance

* introduce new developer action RCB/Templates/TechnicalHandlingIntegration (CU-1raqwk8)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/sitemap-crawler 0.2.24</summary>


**_Purpose of dependency:_** _Find and crawl sitemaps to get a full list of URLs._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/tcf-vendor-list-normalize 0.2.59</summary>


**_Purpose of dependency:_** _Download and persist vendor-list.json to database_
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>

<details><summary>Dependency updates @devowl-wp/utils 1.15.13</summary>


**_Purpose of dependency:_** _Utility functionality for all your WordPress plugins._
##### Bug Fixes

* compatibility with latest Swift Performance version (CU-866aybgxm)


##### Maintenance

* drop concurrently package as no longer needed (CU-3cj43t)
* major update apidoc (CU-3cj43t)
* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update tsc-watch immer lint-staged sort-package-json (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)
* remove supports-color, update focusable-selectors react-quill react-codemirror2 js-cookie (CU-3cj43t)
* update Lerna v7 (CU-31956up)</details>

<details><summary>Dependency updates @devowl-wp/web-html-element-interaction-recorder 0.2.13</summary>


**_Purpose of dependency:_** _Record and replay interactions on a given HTML element._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/api-real-product-manager 0.3.0</summary>


**_Purpose of dependency:_** _Shared typings for all Real Commerce backend._
##### Features

* obtaining telemetry data consent after license activation (CU-861n7amqx)


##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/continuous-integration 0.4.2</summary>


**_Purpose of dependency:_** _DevOps macros, job templates and jobs for Gitlab CI and @devowl-wp/node-gitlab-ci._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* update Lerna v7 (CU-31956up)</details>

<details><summary>Development dependency update @devowl-wp/continuous-localization 0.7.4</summary>


**_Purpose of dependency:_** _Provide a CLI to push and pull localization files from different translation management systems._
##### Maintenance

* major update commander (CU-3cj43t)
* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/eslint-config 0.2.3</summary>


**_Purpose of dependency:_** _Provide eslint configuration for our complete monorepo._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/iso-codes 0.4.5</summary>


**_Purpose of dependency:_** _Enums and key value getters for all countries in different ISO code standards._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/monorepo-utils 0.1.9</summary>


**_Purpose of dependency:_** _Predefined monorepo utilities and tasks._
##### Continuous Integration

* include changelogs from dependencies (CU-2k54tcb)


##### Maintenance

* major update commander (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* update Lerna v7 (CU-31956up)</details>

<details><summary>Development dependency update @devowl-wp/node-gitlab-ci 0.7.6</summary>


**_Purpose of dependency:_** _Create dynamic GitLab CI pipelines in JavaScript or TypeScript for each project. Reuse and inherit instructions and avoid duplicate code!_
##### Maintenance

* major update commander (CU-3cj43t)
* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/regexp-translation-extractor 0.2.19</summary>


**_Purpose of dependency:_** _Provide a performant translation extractor based on regular expression._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/web-cookie-banner 0.3.1</summary>


**_Purpose of dependency:_** _Provide a scoped stylesheet, types and util functionality for a web cookie banner._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* prepare upgrade wizard for v4 release (CU-861n7amqx)
* remove supports-color, update focusable-selectors react-quill react-codemirror2 js-cookie (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/web-scoped-css 0.2.5</summary>


**_Purpose of dependency:_** _Define a scoped stylesheet in JavaScript with performance in mind._
##### Maintenance

* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* remove supports-color, update focusable-selectors react-quill react-codemirror2 js-cookie (CU-3cj43t)</details>

<details><summary>Development dependency update @devowl-wp/webpack-config 0.2.20</summary>


**_Purpose of dependency:_** _Webpack config builder for multiple ecosystems like standalone React frontends, Antd, Preact and WordPress._
##### Maintenance

* major update jest-junit glob @types/jest jest ts-jest (CU-3cj43t)
* major update tsc-watch immer lint-staged sort-package-json (CU-3cj43t)
* major update typescript [@typescript-eslint](https://git.devowl.io/typescript-eslint) typedoc (CU-3cj43t)
* major update webpack components (CU-3cj43t)</details>





# 3.12.0 (2023-09-29)


### chore

* add notice text about special treatments when DSG is active (CU-863h7nj72)
* misc (CU-85ztzbdjt)
* review 1 (CU-85ztzbdjt)
* review 1 (CU-863h7nj72)
* review 1 (CU-cawgkp)
* review 2 (CU-863h7nj72)
* review 2 (CU-cawgkp)
* review 3 (CU-863h7nj72)
* review 3 (CU-cawgkp)
* review 4 (CU-863h7nj72, CU-866aw15cc)
* review 5 (CU-863h7nj72)
* update migrations (CU-863h7nj72)


### docs

* remove not understandable commit messages from changelog (CU-861n7an31)


### feat

* import / export functionality for footer links (CU-cawgkp)
* introduce new website operator details fields in Cookies > Settings > General (CU-863h7nj72)
* introduce provider contact fields (phone, email, contact form) for services (CU-863h7nj72)
* make links to legal documents more dynamic (CU-cawgkp)
* show a langauge switcher in cookie banner when a multilingual plugin is active (CU-cawgkp)
* when WPML or PolyLang is active show translations with flags in the list view (CU-866aw15cc)


### fix

* add migrations for DSG implementation for existing users (CU-863h7nj72)
* add new Checklist item for website operator details (CU-863h7nj72)
* add new Is provider current website option for services (CU-863h7nj72)
* codemirror and template updates cannot be applied (CU-861n7ak3a)
* compatibility with WPForms stripe integration and wpformsReady event (CU-866ax37f4)
* consent forwarding shows wrong entry in consent history when multilingual plugin is active (CU-866axjk0a)
* content blocker for TCF vendor should only check for consent, no legitimate interest (CU-866ax5x2z)
* do not render powered-by link when in page builders (CU-866axn617)
* download of DHL labels did not work (CU-866ax5ke5)
* export and import template version, too, so updates are shown correctly for imported services (CU-866axer0c)
* issue with qTranslate-XT as it tries to translate consent-by (CU-866aw1mrk)
* service in one language deleted, it automatically removed the service from blocker connections in other langauges, too (CU-866aw15cc)
* show the legal basis in cookie banner with the help of {{legalBasis}} variable (CU-863h7nj72)
* time units for Swedish minimal translations (CU-866axjbr8)
* when changing website operator details automatically reflect to local services (CU-863h7nj72)


### perf

* do not Remove Unused CSS for animate.css to improve PageSpeed CLS when WP Rocket is active (CU-866axeb2m)
* further PageSpeed insights improvements (CU-866avmt9a)
* improve Content Blocker how it affects PageSpeed Insights (CU-866axeb2m)
* improve Content Blocker rendering how it affects PageSpeed Insights (CU-866axeb2m)
* more performant cookie banner rendering by eager lazy loading components (CU-866axeb2m)
* yield entrypoint scripts in main thread (CU-866axeb2m)


### refactor

* move settings form to @devowl-wp/react-cookie-banner-admin (CU-863h7nj72)
* rename legalBasis which is applied to the whole cookie banner to territorialLegalBasis (CU-863h7nj72)
* use operator country also for TCF publisher country (CU-863h7nj72)





## 3.11.5 (2023-09-22)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.11.4 (2023-09-21)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.11.3 (2023-09-07)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.11.2 (2023-09-06)


### ci

* enable Continuous Localization for the wordpress.org/README.wporg.txt file (CU-861n8mnx8)


### fix

* compatibility with WonderPlugin gallery plugin (CU-866avwjtw)
* do not delete service notice transient when creating a new auto-draft (CU-866avt8n1)
* do not send dataProcessingInUnsafeCountries telemetry data in free version (CU-866avtre5)
* scanner gets stuck when sitemap contains a non-existing URL which results into 404 error (CU-866avmxc9)


### perf

* improve Google PageSpeed Insights score by using fastdom.mutate (CU-866avmt9a)





## 3.11.1 (2023-08-30)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





# 3.11.0 (2023-08-28)


### build

* use @babel/plugin-proposal-class-properties with updated caniuse-lite database (CU-863h37kvr)


### chore

* text adjustments for translations (CU-2gfb4w6)


### feat

* introduce accessibility (a11y) notices about contrast ratio for font colors in customizer (CU-863h37kvr)
* introduce accessibility (a11y) notices about font size in customizer (CU-863h37kvr)
* introduce accessibility (a11y) notices about font weight in customizer (CU-863h37kvr)
* introduce accessibility score in customizer (CU-863h37kvr)


### fix

* compatibility with WooCommerce Google Analytics Integration plugin as it could not be created as temlate (CU-8678qabqn)
* contrast ratio calculator is wrong for hover effects for buttons (CU-863h37kvr)


### style

* update existing banner presets with 100% accessibility score (CU-863h37kvr)





# 3.10.0 (2023-08-24)


### chore

* minimal translations for accessability (CU-863h2xzc9)
* show notice for older consents as they are no longer replayable (CU-863h2xzc9)
* wrong Activate free license text in PRO version when service cloud could not be downloaded (CU-1raqwk8)


### docs

* compatibility with Swiss DSG declared in wordpress.org product description (CU-861n5ar23)


### feat

* accessible cookie banner (CU-863h2xzc9)
* introduce new confirm() selector-syntax rule for content blockers (CU-861n86a5n)
* introduce new selector syntax function transformAttribute (CU-861n7upvp)


### fix

* compatibility with content blocker and newsfeed of BuddyBoss (CU-861n6e5kf)
* compatibility with FloTheme contact form as it does not get rendered with Google Fonts content blocker (CU-861n7fgt7)
* compatibility with LayTheme and video embeds (CU-861n6p9uq)
* compatibility with more cases where magnificPopup loads external content (CU-861n86a5n)
* do not add dynamic stylesheets to WP Rockets RUCSS optimizaton (CU-2yt81xz)
* fatal PHP error json_decode(): Argument #1 () must be of type string, array given (CU-861n7hwqr)
* restore functionality in scanner for external URLs (CU-861n7u689)
* too many TCF vendors lead to a too huge JSON revision in database (CU-861n6fudh)


### refactor

* introduce class names and a scoped stylesheet to Cookie Banner instead of style attribute (CU-2yt81xz)





## 3.9.5 (2023-08-04)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.9.4 (2023-08-04)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.9.3 (2023-08-04)


### fix

* language packs could not be downloaded from SVN repository for slugs ending with -lite (CU-861n4ahzb)





## 3.9.2 (2023-08-02)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.9.1 (2023-08-02)


### chore

* adjust texts for country selection for predefined lists (CU-861n2wt7d)
* checked compatibility with WordPress 6.3 (CU-861n42pdy)


### fix

* compatibility with Ghost Kit and false-positive Google Maps gets found in scanner (CU-861n3md74)
* compatibility with one.com maintenance plugin (CU-861n48b69)
* could not update the meta value of technicalDefinitions in database (CU-861n4602e)
* proper error handling when Service Cloud is down (CU-861n1rzgm)
* some services in the scanner redirects to the wrong creation form (CU-861n46vpw)
* some TCF vendors could not be created when no additional information is given (CU-861n4dyd2)





# 3.9.0 (2023-07-18)


### chore

* consider USA as a country with secure data transfer (if companies certify themself) (CU-861m47jgm)
* fixed variable typos (CU-863h6pdwd)


### feat

* minimal translations for special treatments for processing data in unsafe countries (CU-863h6pdwd)


### fix

* blank cookie banner dashboard (CU-861n1rzgm)
* compatibility with Elementor Popups when it gets hidden with click on the content blocker overlay (CU-863h3ah8x)
* introduce special treatments for processing data in unsafe countries (CU-863h6pdwd)
* show only a limited amount of countries in service form (CU-861n2g4ag)





# 3.8.0 (2023-07-06)


### docs

* update filter documentation with more examples and use cases


### feat

* new feature to collect consent for services processing data in unsecure countries (CU-861m47jgm)


### fix

* compatibility with Elementor Popups when it gets hidden with click on the cookie banner (CU-863h3ah8x)
* compatibility with Enfold and Vimeo embeds (CU-863h48vp2)
* compatibility with fluidvids (CU-863gymp32)
* compatibility with videos in widgets in Extra theme (CU-863h5dak1)
* compatibility with videos in widgets in Extra theme (CU-863h5dak1)
* difference from template for service group is empty (CU-32wu2g8)
* elementor not shown as recommended service in scanner (CU-861mzap32)
* ignore 410 HTTP code in scanner (CU-863gzu8gh)
* introduce pagination to technical cookie information as many items could slow down the form (CU-32wu2g8)
* show notice when service is processing data in unsafe countries and the banner notice is disabled (CU-861m47jgm)
* uncaught Error: Class DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration in free version (CU-863h4mazx)
* uncaught Error: Class DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration in free version (CU-863h4mazx)
* update notice about templates could not be dismissed (CU-863h2byjk)


### refactor

* introduce custom ESLint rules ability in @devowl-wp/eslint-config (CU-863gxjbn4)
* introduce KeyValueMapOption and migrate notice states to it (CU-861m47jgm)
* move enableOptionAutoload to @devowl-wp/utils (CU-861m47jgm)


### test

* make e2e tests work again (CU-861m47jgm)





## 3.7.2 (2023-06-05)


### ci

* technical renaming all languages that they contains the formality (CU-2gfb42y)
* technical renaming of German, French, Spanish, Italian and Dutch translations that they contains the formality (CU-2gfb42y)


### fix

* compatibility with Bricksbuilder as pages were no longer editable (CU-861mw0bcc)
* mapping of language files for copying to correct language (CU-2gfb42y)
* some services were not shown as created in scanner results (CU-863gwufp5)





## 3.7.1 (2023-05-30)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





# 3.7.0 (2023-05-30)


### chore

* remove old PHP template/preset system as replaced by cloud templates (CU-861me62d8)
* resolve conflict (CU-3207gvx)
* versionized wp_rcb_templates database table and keep outdated templates (CU-861me62d8)


### feat

* prepare versions for templates so we can visualize the difference between them (CU-861me62d8)


### fix

* translate created service and blocker templates from a translation database table for WPML/PolyLang compatibility (CU-861me62d8)
* use correct charset and collate in database for newly added database tables (CU-863gtqpz0)


### perf

* speed up scanner (CU-861mv177f)


### refactor

* connect new template center with scanner (WIP, CU-861me62d8)
* connect new template center with service and blocker form (WIP, CU-861me62d8)
* createFromPreset to TemplateConsumers::createFromTemplate (CU-861me62d8)
* remove usage of CookiePresets and BlockerPresets (CU-861me62d8)
* rename wp_rcb_templates to wp_rcb_template (CU-863gtqpz0)


### style

* cookie banner overflows to the right when hero content blocker is too wide (CU-861muuzq3)





## 3.6.11 (2023-05-22)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.6.10 (2023-05-21)


### chore

* remove dotenv package (CU-861m6e3mz)


### refactor

* migrate Traefik environment variables to Envkey (CU-861m6e3mz)





## 3.6.9 (2023-05-19)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.6.8 (2023-05-15)


### fix

* compatibility with WP Matomo when network-wide active (CU-863gqw8bg)





## 3.6.7 (2023-05-12)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.6.6 (2023-05-11)


### fix

* allow to reset all settings with option to reset also consents (CU-861mk857a)
* call to a member function localizeGroups() on null (CU-863gp8ag0)
* compatibility with Essentials Addons for Elementor and YouTube and Vimeo videos (CU-863gnduk0)
* compatibility with Video Gallery & Slider For YouTube (CU-863gmpp0n)
* compatibility with WP Go Maps (CU-863gq67nt)
* obfuscate public REST API calls (CU-206yrf0)
* some TCF vendors could not be saved without error message (CU-863gmnvet)
* uncaught WpOrg Requests Exception: Only HTTP(S) requests are handled (CU-863gp8g9h)





## 3.6.5 (2023-04-28)


### fix

* compatibility with latest GeoDirectory plugin (CU-33z125m)
* typo in privacy policy sample text for Real Cookie Banner (CU-2vqpmwj)





## 3.6.4 (2023-04-24)


### fix

* compatibility with Directorist and OpenStreetMaps (CU-863ghhh2w)
* compatibility with latest IONOS performance plugin (CU-32003j3)
* notice when AMP plugin is active and scanner does not work (CU-863gjbfxp)
* storing templates from cloud: allowed memory size of bytes exhausted (CU-863ghz41w)





## 3.6.3 (2023-04-19)


### chore

* add more security hashes for disabled footer (CU-332e8qr)
* introduce new UI for template center from service cloud (WIP, CU-861me62d8)
* remove non-ASCII characters from POT files (CU-863gffr77)
* start with new template center (WIP, CU-861me62d8)
* update README.org text replacing Article 66 with Racital 66 (CU-861mc9hc2)


### fix

* compatibility with latest Impreza and unblocking Google Maps (CU-861mkbd3p)
* compatibility with latest RankMath version and finding Google Analytics UA and v4 with local script files enabled (CU-863gdnt50)
* compatibility with Perfmatters Minimal v4 Google Analytics embed (CU-2eggmy7)
* correctly handle async cache calculation for service cloud (CU-861me62d8)
* hook into Pretty Links plugin to only set tracking cookies when consent is given (CU-863gftjna)
* output logo URL in content-blockers route (CU-861me62d8)
* scanner does not find any Google Analytics embeds when RankMath Exclude Logged-in users option is active (CU-863gdnt50)
* theme not detected as active when using e.g. wp-content/themes/Divi (capital letter, CU-861mkuxh1)


### perf

* wp_load_alloptions called for each subsite within multisite (CU-861med012)


### refactor

* extract isPro and i18n functions to own context for reusability (CU-861me62d8)
* introduce taskfile.dev Taskfiles (CU-85zrrymj0)
* rename doNotConsiderInGroups to needsRequiredSiblingRule (CU-863gdnt50)





## 3.6.2 (2023-03-24)


### fix

* compatibility with Dejure theme and unblocking Google Maps (CU-863gac0ng)
* compatibility with Point of Sale for WooCommerce (CU-863gaceu4)


### style

* fusion builder content blocker overlaps with column content (CU-861mhr4pe)





## 3.6.1 (2023-03-21)


### chore

* introduce new service cloud to better manage service and blocker templates (WIP, CU-2mjzexr)
* update dependencies including TypeScript 4.9, antd and eslint (CU-85zrqk9pd)
* updated note on legal state of TCF (CU-861mgt18f)


### fix

* checklist item of legal links is not checked when legal links are placed manually (CU-2ep07vd)
* compatibility with Bandtheme and YouTube embeds (CU-85zrrv779)
* cookie banner pops up on every page when changing the cookie domain manually (CU-85zrrve3w)
* development docker build does sometimes not startup correctly (CU-85zrqk9pd)
* use correct release info when saving templates from cloud (CU-2mjzexr)


### refactor

* rename grunt-continuous-localization to continuous-localization and remove grunt dependency (pure bin, CU-85zrrytg6)





# 3.6.0 (2023-03-14)


### chore

* add legal notice URL to all self-hosted services (CU-2wpbbhr)
* compatibility with WordPress 6.2 (CU-861mfxmc1)
* remove unused dependencies (CU-85zrqj4jp)
* restructure .env and replace Scaleway API keys with new IAM (CU-37q5f2x)


### feat

* new field for service templates "Legal notice URL for provider" (CU-2wpbbhr)


### fix

* compatibility with 10Web Map Builder for Google Maps (CU-85zrrkfzw)
* compatibility with ThemeDraft themes and Google Maps (CU-863g65whr)
* do also base64 encode scripts in localized variable in customize preview (CU-8677knwy0)
* javascript error wp.mediaUtils is undefined and media library does not work (CU-863g6v17m)





## 3.5.3 (2023-03-01)


### fix

* compatibility with latest version of Social Feed Gallery Instagram (CU-2d8ba1v)
* compatibility with WooCommerce Blocks plugin (CU-863g5rqfp)





## 3.5.2 (2023-02-28)


### chore

* update wordpress stubs (CU-863g4efkw)


### fix

* compatibility with OSM Map Widget for Elementor (CU-861mdhpu4)
* compatibility with Supreme Maps Pro (CU-861mdakyh)
* consider 404 errors in scanner as non-error (CU-863g3v71n)
* invalid JSON int database helper class with the help of JSON5 (CU-863g4efkw)
* scanner finds OMGF inline script as Google Fonts (CU-861mdaurx)





## 3.5.1 (2023-02-21)


### fix

* apache modsecurity complains about localized JSON object when there are scripts and iframes (CU-863g375z3)
* compatibility with Elfsight Vimeo Gallery CC (CU-863g3kmfw)
* compatibility with Streamtube and YouTube videos (CU-861mcrub5)
* do not load scripts in WP Bakery edit mode (CU-861mcfwa4)
* typo in privacy policy text proposal (CU-863g3867t)
* uncaught error: Undefined constant NONCE_SALT (CU-863g3m0tm)
* validate UUID in cookie value (CU-861mchkwt)


### perf

* save creation date of cookie in cookie value instead of SQL query (CU-861mchkwt)
* speed up counting and pagination in list of consents (CU-861mchkwt)
* speed up reading consent history (CU-861mchkwt)
* speed up saving consent by adjusting how stats are saved (CU-861mchkwt)


### refactor

* move all user-consent relevant SQL queries to UserConsent class (CU-861mchkwt)
* streamline IP handler to use UserConsent#byCriteria (CU-861mchkwt)





# 3.5.0 (2023-02-15)


### build

* consider dependencies in cache invalidation in i18n generation (CU-2x5m1gu)


### chore

* streamline docker-compose settings with non-production context (CU-861m5btfw)
* update disclaimer checkboxes (CU-2x5kb66)


### feat

* allow to record interactions and introduce player in list of consents (CU-2undj42)
* introduce copyable sentence for your privacy policy in Cookies > Settings > General (CU-2vqpmwj)
* introduce new individual text field to put text below service groups in second layer (postamble, CU-861mbjkht)
* introduce new service field Unique Name so 3rd party plugins can obtain consent via Consent API (CU-2unhn5x)
* new checklist item to update privacy policy with Real Cookie Banner mention (CU-2vqpmwj)


### fix

* allow multiline texts when copying texts (CU-2vqpmwj)
* block Vimeo live events in Vimeo content blocker
* compatibility with Breakdance page builder (CU-861m4yxej)
* compatibility with HTML blocks in Woodmart themes and scanner (CU-861mbpq2x)
* compatibility with latest Thrive Leads version (CU-863g124r8)
* compatibility with latest version of Divi and Ajax Search Pro (CU-863g1n0ve)
* delete origin of redirected URL while scanning from scanner results (CU-863fyjeee)
* do not aggregate data from consents instead use own aggregation database table to be more GDPR compliant (CU-2z4e99b)
* do not modify redirected scan URL when job id is already in params list (CU-861mbpq2x)
* new selector syntax function to delegate a click on blocked node (CU-863g124r8)
* new text for Content Blocker Load content button / link (CU-2z4eg7v)
* new text for Revoke consent link / shortcode (CU-30chpnz)
* scanner got stuck 99% when redirection on webserver was too early (CU-863fyjeee)
* show Continue without consent button in Change privacy settings dialog (CU-2x5q7ny)
* title repeated multiple times when emoji is in e.g. YouTube title (CU-863g20zqz)





## 3.4.13 (2023-01-25)


### chore

* introduce new selector syntax function keepAttributes and style (CU-33z67qt)
* introduce new selector syntax functions forceVisual() and visualParent() (CU-33z67qt)


### fix

* compatibility with BeTheme / BeBuilder (CU-861m7mmu0)
* compatibility with Breakdance page builder and Goolge Maps embeds (CU-33z67qt)
* compatibility with Enfold and performance JavaScript mreging (CU-861m8g071)
* compatibility with Estatik Real Estate Plugin and Google Maps (CU-861m9594v)
* compatibility with Magnific Popup with visual content blockers (CU-861m7cb9u)
* do not load unncessery assets in login mask wp-login.php (CU-861m9bm8g)





## 3.4.12 (2023-01-10)


### chore

* update @antv/g2 to latest version (CU-861m5gzx6)




## 3.4.11 (2023-01-03)


### chore

* introduce new filter RCB/Blocker/AdminAjaxActions (CU-861m53rv3)
* show better error message when creating the default service groups fails


### fix

* compatibility with Breakdance page builder (CU-861m4yxej)
* disable US data processing for Spotify preset (CU-861m5pk1f)
* improved compatibility with Contact Form 7 and additional class name
* list of consents and history of consents did not load any entries (CU-861m58gk8)
* provide one more dataLayer variable for GTM/MTM realCookieBannerConsents (CU-861m538z2)





## 3.4.10 (2022-12-22)


### chore

* link to kb article for development license warnings / red warnings (CU-388ch1x)


### fix

* block leaflet.min.js in OpenStreetMap content blocker (CU-31mkbne)
* compatibility with BoldThemes and Google Maps
* compatibility with OSMapper (CU-861m4bqrd)
* compatibility with visual content blockers and WP Bakery tab content (CU-861m3hgxg)
* detect single gtag events to Google Ads (CU-388ak7a)
* improved compatibility with latest version of WP Google Maps (CU-861m4d0ea)
* vulnerability XSS in shortcode class parameter (CU-861m3j4b4)
* warning trying to access offset on value of type null in PHP log (CU-861m47fm0)


### perf

* remove path_join calls and use trailingslashit instead (CU-861m3qqb7)





## 3.4.9 (2022-12-12)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.4.8 (2022-12-12)


### docs

* update README contributors


### fix

* added google maps compatibility for bricks builder (CU-37qavun)
* compatibility with Event Calendar and downloading ICS file, in general never try to block inline downloads (CU-37wwyu7)
* compatibility with latest Elementor PRO version and Google Maps JetEngine (CU-37wv9wu)
* compatibility with Pixel Manager for WooCommerce plugin (CU-37he9cj)
* do not show REST API notice when offline, hide when route works again and trace log in textarea (CU-37q9evr)
* german texts not shown for some strings (with context) when using TranslatePress (CU-37q61pt)
* improved compatibility with Geo Directory plugin (CU-33z125m)
* show notice for invalid TCF device closure within the vendor configuration (CU-37hg97j)
* tcf vendor with not-existing purpose cannot be added to TCF vendor configuration (CU-37hg97j)


### refactor

* introduce @devowl/api-real-cookie-banner package (CU-33tam4h)





## 3.4.7 (2022-12-05)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.4.6 (2022-12-02)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.4.5 (2022-12-01)


### chore

* adjust telemetry data collection (CU-2ufnyc2)
* execute deferred telemetry data transmit (CU-2ufnyc2)


### fix

* add new TCF vendor leads to JavaScript error when too much are registered (CU-34g9kbw)
* compatibility with Impreza and OpenStreetMap embed (CU-344n7q3)
* compatibility with WP Go Maps and Google Maps embed (CU-37bnu5f)
* improved compatibility with Oxygen youtube embeds (CU-34g8wne)


### style

* use another blur method as it could break absolute positioned menus (CU-3764wqn)





## 3.4.4 (2022-11-24)


### fix

* add notice when plugins are activated/deactivated (CU-2bujq84)
* compatibility with background video in Elementor sections and column (CU-33z36er)
* compatibility with Bold Page Builder and Google Maps embed (CU-33z66qn)
* compatibility with Bold Page Builder and Google Maps embed (CU-33z66qn)
* compatibility with Bold Page Builder and Google Maps embed (CU-33z66qn)
* compatibility with Bold Page Builder and Google Maps embed (CU-33z66qn)
* compatibility with Elementor playlist when loaded deferred (CU-33z3dh8)
* compatibility with Google Maps in GeoDirectory (CU-33z125m)
* compatibility with Impreza WP Bakery Google Maps embed (CU-344n7q3)
* compatibility with LeafLet Map extension plugin (CU-344mvx1)
* compatibility with Mikado Themes and Google Maps (CU-33z1k0n)
* compatibility with Uncode fluid objects not rendering visual content blockers (CU-344p8r3)
* compatibility with Uncode fluid objects not rendering visual content blockers (CU-344p8r3)
* empty form for creating services within content blocker form (CU-32wtxkt)
* improved compatibility with Elementor Pro and lazy loaded scripts (CU-33z3dh8)
* improved compatibility with WP ImmoMakleer plugin (CU-200ykt6)
* introduce new content blocker selector syntax matchUrls to fix false-positive Elementor videos (CU-33z3dh8)
* sometimes visual content blockers did not unblock after page reload when deferred scripts loading too long (CU-33ternv)
* wrong spacing for visual content blocker for WP Bakery video embeds inside columns (CU-33z5vfd)


### test

* error 1 smoke test failing (CU-344wgj9)
* error 2 smoke test failing (CU-344wgj9)
* error 3 smoke test failing (CU-344wgj9)





## 3.4.3 (2022-11-21)


### perf

* speed up saving of consent for the first consent of the day (CU-33yxgb6)





## 3.4.2 (2022-11-18)


### fix

* compatibility with latest Elementor version and no Vimeo playlist visual content blocker (CU-32h6xq0)
* expand header logo with alt text and correct dimensions for SVG file (CU-33t99y8)
* false-positive REST API notice about real-queue/v1 (CU-33tce0y)
* some translations were still in english instead of Swedisch or other incomplete translation (CU-33t8u66)
* user consents are not deleted after x months when there were too many consents (CU-33yxgb6)


### perf

* reduce time to interactive by rendering visual content blockers earlier (CU-33ternv)


### refactor

* rename handleCorruptRestApi function (CU-33tce0y)





## 3.4.1 (2022-11-15)


### fix

* allow to pass class as parameter to shortcodes
* compatibility with Events Manager and Google Maps (CU-33drdw6)
* compatibility with Google Maps in Essential Addons for Elementor plugin (CU-3388522)
* compatibility with Ovatheme and Google Maps (CU-33drbyt)
* do not show notice about missing privacy policy URL when license activation is not yet done (CU-2kpd6z4)
* force to use option home_url and siteurl instead of constants when within subdomain MU (CU-33khexz)
* service code on page load is not executed when Custom CSS is enabled (CU-33khjmy)
* technical definitions cannot be saved because WordPress unslash JSON value in post meta (CU-33km1q9)


### revert

* we still need to scan elementor libraries (CU-332fn7n)





# 3.4.0 (2022-11-09)


### feat

* automatic deletion of consents (CU-1xgphqf)


### fix

* compatibility with blocked content for Jet Smart Filters lazyloading (CU-332jgxy)
* compatibility with Google Maps in Sober theme (CU-332ev4y)
* compatibility with latest version of WPImmomakler
* compatibility with MapPress Google Maps (CU-32wpgv9)
* compatibility with MapsMarkerPro unblocking (CU-32wnjpu)
* compatibility with Vehica theme
* do not show preset check when editing a template in services or content blocker form (CU-2wmf0yr)
* duplicate technical definition in Vimeo and JetPack Site Stats template (CU-32wkt35, CU-332f81e)
* improved compatibility with Elementor and Elementor PRO to block individual widgets (CU-32q09j9)
* listen to elementor init with vanilla JS event listener instead of jQuery (CU-332h9tj)
* skip elementor library and skip in scanner (CU-332fn7n)
* visual content blocker not visible when using content in Kadence Blocks accordion module (CU-32pzryx)


### refactor

* improved compatibility with PHP 8.1 (CU-1y7vqm6)
* static trait access (Assets enqueue features, CU-1y7vqm6)
* static trait access (Assets handles, CU-1y7vqm6)
* static trait access (Assets types, CU-1y7vqm6)
* static trait access (Localization i18n public folder, CU-1y7vqm6)
* static trait access (Localization, CU-1y7vqm6)


### revert

* handle child themes correctly when blocked (CU-32pymrn)


### style

* full width content blocker for elementor widgets





# 3.3.0 (2022-10-31)


### chore

* compatibility with WordPress 6.1 (CU-32bjn2k)


### feat

* add scan again for individual scan results (CU-yrhr8c)


### fix

* automatically block child theme URLs when using parent slug in content blocker rule (CU-32pymrn)
* compatibility with Elementor PRO video playlists (CU-32h6xq0)
* compatibility with Ezoic CDN and content blocker (CU-32h9k0n)
* compatibility with GDPR mode of Avada theme (CU-2fd0phg)
* compatibility with Magnific Popup (CU-32pvhdp)
* compatibility with The Events Calendar Google Maps embed (CU-32h7mh4)
* compatibility with WooCommerce Point of Sale (CU-32hc0zw)
* list of consents white screen when IPv6 entry is shown (CU-32pvj24)





# 3.2.0 (2022-10-25)


### chore

* add restore option for ignored external URLs (CU-11efdym)


### feat

* allow to filter by IP addresses with truncated results in list of consents (CU-3203uve)
* native integration to CMP – Coming Soon & Maintenance Plugin by NiteoThemes (CU-319a6mz)
* native integration to Maintenance plugin by WebFactory Ltd (CU-319a6mz)
* native integration to Website Builder by SeedProd (CU-319a6mz)
* native integration to WP Maintenance Mode & Coming Soon (CU-319a6mz)


### fix

* better explains import/export section (CU-30r534y)
* block Twitter timeline (CU-32be81u)
* compatibility for Directories Pro with Google Maps (CU-31mkbne)
* compatibility with CheckoutWC autocomplete (CU-31zzkuj)
* compatibility with Elementor PRO actions (e.g. YouTube lightbox, CU-3204cj6)
* compatibility with GiveWP stripe gateway plugin (CU-325v56y)
* compatibility with latest Enfold / Avia google maps embed (CU-31mp857)
* compatibility with Salient theme and OpenStreetMap embed (CU-3200g2t)
* compatibility with SiteOrigin Google Maps widget (CU-32044f1)
* configure form content blocker templates as visual by default (CU-31mnthw)
* content blocker not applied with IONOS performance plugin (CU-32003j3)
* license activation error 'Client property value is Emty' (CU-31zz2mk)
* localize original home URL to be not dependent on admin bar when it got removed / disabled (CU-3203g9v)
* white space below footer when Thrive Leads content blocker is created (CU-32be9fh)





## 3.1.7 (2022-10-11)


### build

* add webpack as dependency to make it compatible with PNPM (CU-3rmk7b)


### chore

* add new team member to wordpress.org plugin description (CU-2znqfnu)
* introduce consistent type checking for all TypeScript files (CU-2eap113)
* prepare script management for self-hosted Gitlab migrations (CU-2yt2948)
* rebase conflicts (CU-3rmk7b)
* remove unused dependencies (CU-3rmk7b)
* start introducing common webpack config for frontends (CU-2eap113)
* switch from yarn to pnpm (CU-3rmk7b)


### ci

* make PNPM and our backends work in CI pipeline (CU-3rmk7b)


### fix

* block content in Enfold theme slider (CU-30jdd2j)
* compatibility for new Mailerlite embed (CU-d10rw9)
* compatibility with Avada fusion builder video shortcode (CU-30r31hk)
* compatibility with Divi multi view and allow deeply blocking content in JSON attributes (CU-30jcz089)
* compatibility with Enfold / Avia google maps embed
* compatibility with HivePress and memoize jQuery events with their parameters (CU-30xxbyt)
* compatibility with Impreza + WP Bakery vimeo embed and video thumbnail (CU-2yyye6w)
* compatibility with Neuron themes and their advanced google maps Elementor widget (CU-313bduc)
* compatibility with OnePress maps and jQuery.each hijacking (CU-30cg9tv)
* compatibility with WoodMart themes and Google Maps (CU-30r6bk1)
* create stub for window.consentApi (CU-30xpafq)
* do not find false-positive attributes in HTML strings in JSON attribute (CU-30xnaa3)
* do not find Gravatar when using Elementor Notes module in scanner (false-positive, CU-30jdeqb)
* do not find links in RankMath localized variable and false-positive e.g. YouTube (CU-30cgtat)
* do not scan OMGF inline scripts as Google Fonts (CU-2znv6e2)
* improved UX when configuring Continue without consent and Save button in customizer (CU-2znk1f4)
* show cookie banner on pages selected as Imprint / privacy policy when external page / URL is used (CU-313j6wv)
* show Facebook Page Plugin in scanner when used with Elementor PRO sdk injection
* show Facebook Page Plugin in scanner when used with Elementor PRO sdk injection
* warning when OceanWP is active and trying to add a new menu item in Design > Menu (CU-2znuj8j)


### test

* setup VNC with noVNC to easily create Cypress tests (CU-306z401)





## 3.1.6 (2022-09-21)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.1.5 (2022-09-21)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 3.1.4 (2022-09-20)


### fix

* consent could not be created due to invalid NONCE_SALT (CU-2yypq95)
* google maps content blocker could not be created (CU-2zfw1cy)





## 3.1.3 (2022-09-16)


### chore

* validate service and blocker templates for specific rules (CU-2kav8bg)


### fix

* allow to configure essentials button independent of body design
* compatibility for Google Maps via Ultimate Addons for WPBakery Page Builder (CU-2yt24kh)
* compatibility with BeaverBuilder PowerPack videos and overlays (CU-2yyvjag)
* compatibility with Creativo theme by Rockythemes
* compatibility with Oxygen accordion and visual content blockers (CU-2yypktj)
* compatibility with YouTube blocker and Impreza + WP Bakery in lightbox
* make minimal languages work again with legal texts in cookie banner (CU-2yt84ad)
* show correct link when PolyLang / WPML active in banner footer instead of page_id (CU-2yyph19)





## 3.1.2 (2022-09-06)


### fix

* compatibility for Widgets for Google Reviews by Trustindex.io (CU-2wu8qtc)
* compatibility for WP Map Block with Google Maps (CU-2x5p9r8)
* compatibility for WP Map Block with OpenStreetMap (CU-2x5p9r8)
* compatibility with Agile Store Locator (CU-2wu2gjc)
* compatibility with blocked content in Impreza theme popups (CU-2ep5dt0)
* compatibility with Divi video embed, thumbnail overlays and autoplay (CU-2vxpf7d)
* compatibility with Elementor PRO and facebook page widget
* compatibility with Elementor Video API when no script is loaded without consent (CU-2wu8u5j)
* compatibility with Oxygen lightbox and visual content blockers (CU-2x5j0cy)
* compatibility with Ultimate Addons for WPBakery Google Maps widget
* compatibility with wrong margin when embedding video in WP Bakery page builder (CU-2wu94qk)
* correctly copy content when default language differs from setup language in WPML / PolyLang (CU-2x5p7yh)
* do not show notice about privacy policy when not needed
* facebook page plugin content blocker could not be created (CU-2x5j2kg)





## 3.1.1 (2022-08-30)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





# 3.1.0 (2022-08-29)


### chore

* compatibility for JetEngine Google Maps Listing version >= 3.0 (CU-2jzg7yc)
* extract urls from texts for better translatability (CU-2gfbm5v)
* introduce devowl-scripts binary (CU-2n41u7h)
* introduce for non-flat node_modules development experience (CU-2n41u7h)
* optimize explanation texts for EU-wide instead of German consideration (CU-2gfbm5v)
* prepare packages for PNPM isolated module mode (CU-2n41u7h)
* rebase conflicts (CU-2n41u7h)
* reduce bundle size by removing some vendor files (CU-2d8dedh)
* show a notice when deactivating animation-in in customizer (CU-2w3br3w)


### ci

* generate webpack json stats and upload to storage-dev (CU-1r55qj4)


### feat

* introduce a more intuitive way updating service templates (CU-d0zyw3)


### fix

* caching issues with consent history dialog (CU-2vqu2gd)
* caching issues with dynamic predecision (GEO restriction, CU-2vqu2gd)
* compatibility with image overlay for Elementor videos (CU-2vxf7tf)
* compatibility with Jupiter X and their Google Web Font Loader (CU-2w90px5)
* compatibility with latest MailerLite version
* compatibility with latest TCF vendor list and additional information (CU-20r2upf)
* compatibility with PHP 7.2.1 (CU-2w38zkr)
* compatibility with Presto Player (CU-2w3au1b)
* compatibility with WP Optimize lazyloading (CU-2w39gdf)
* delete HTTP cookies was called multiple times (CU-2d8dedh)
* remove unnecessery hint for ePrivacy USA setting in customizer (CU-2w3awb1)
* sometimes Custom HTML blocks got no YouTube thumbnail and block iframe onload attribute (CU-2wetw74)
* visual content blockers are rendered 1 second delayed when GTM/MTM is active (CU-2v12m07)


### perf

* drop IE support completely (CU-f72yna)
* permit process.env destructuring to save kb in bundle size (CU-f72yna)


### refactor

* all legal relevant texts put into own context (CU-2uv31dz)
* introduce new admin-UI package to prepare for intuitive service template updates (CU-2d8dedh)
* move blocker list component to @devowl-wp/react-cookie-banner-admin (CU-2d8dedh)
* move components of cookie form to @devowl-wp/react-cookie-banner-admin (CU-2d8dedh)
* move first components of cookie form to  @devowl-wp/react-cookie-banner-admin (CU-2d8dedh)
* move group form component to @devowl-wp/react-cookie-banner-admin (CU-d0zyw3)
* move list component to @devowl-wp/react-cookie-banner-admin (CU-d0zyw3)
* rename meta field codeOptOutDelete to deleteTechnicalDefinitionsAfterOptOut (CU-2d8dedh)
* rename meta field cookies to services (CU-2d8dedh)
* rename meta field criteria cookies to services (CU-2d8dedh)
* rename meta field forceHidden to shouldForceToShowVisual (CU-2d8dedh)
* rename meta field hosts to rules (CU-2d8dedh)
* rename meta field noTechnicalDefinitions to isOnlyEmbeddingExternalResources (CU-2d8dedh)
* rename meta field providerPivacyPolicy to providerPrivacyPolicyUrl (CU-2d8dedh)
* rename meta field sessionDuration to isSessionDuration (CU-2d8dedh)
* rename meta field visual to isVisual (CU-2d8dedh)
* rename meta field visualDarkMode to isVisualDarkMode (CU-2d8dedh)
* rename meta fields for Google/Matomo Tag Manager (CU-2d8dedh)
* rename template field cookies to serviceTemplates (CU-2d8dedh)
* rename template field deactivateAutomaticContentBlockerCreation to shouldUncheckContentBlockerCheckbox (CU-2d8dedh)
* rename template field disableTechnicalHandlingThroughPlugin to shouldRemoveTechnicalHandlingWhenOneOf (CU-2d8dedh)
* restructure template field blockerPresets to contentBlockerTemplates (CU-2d8dedh)
* restructure template field dynamicFields from object to array (CU-2d8dedh)
* use browsers URL implementation instead of url-parse (CU-f72yna)





## 3.0.2 (2022-08-09)


### chore

* add more security hashes for disabled footer (CU-232h7c4)
* compatibility for Themovation Google Maps embeds (CU-2ufxfgv)


### fix

* block content in FacetWP facets html (CU-2r5967v)
* compatibility with Borderland theme and Google Maps embed (CU-2pc4umm)
* compatibility with CMSMasters plugins and jQuery gMap plugin (CU-2tdff1g)
* compatibility with Elementor lightbox links and Vimeo and YouTube content blocker (CU-2uvazkm)
* compatibility with Elementor popup content and content blocker (CU-2uvazkm)
* compatibility with FacetWP inline scripts which hold blocked data (CU-2r5967v)
* compatibility with PremiumAddons for Elementor OffCanvas menu (CU-38kmfgj)
* compatibility with Ultimate Blocks accordion and visual content blockers (CU-2r5ej7e)
* compatibility with vanilla-lazyload used by WP Rocket Lazy Load plugin (CU-2pc568x)
* compatibility with YouTube and Vimeo videos in Avada lightbox (CU-2ufpd83)
* compatibility with YouTube content blocker and jetpack embed
* connect.facebook.com was found as external URL in scanner when using facebook page plugin (CU-2tdfh2z)
* disable content blocker for rendered AMP pages (CU-2uvazv6)
* introduce cookie name version and allow new installations using the cookie path in cookie name (CU-2rb441c)
* powered by link is print on the bottom page instead of in cookie banner (CU-2phzbpj)
* using custom WP_CONTENT_DIR for wp-content/plugins and wp-content/themes blocker rules (CU-2rb3arg)


### style

* cookie banner hidden behind header when positioned on top in Divi theme (CU-2r5evnq)





## 3.0.1 (2022-07-06)


### chore

* send accepted group slugs to consent forwarding endpoints (CU-2mk0wyq)


### fix

* allow to block JSON in inline scripts granularly (e.g. inline translations, CU-2my9x5r)
* compatibility with autoptimize and aggregate inline CSS (CU-2m7jfhg)
* compatibility with Avada Fusion Builder video facade (lite-youtube-embed, CU-2nfkhc3)
* compatibility with Elementor Pro popups and visual content blocker (CU-2kp8vmg)
* compatibility with FacetWP and Maps add-on (CU-2p6az87)
* compatibility with latest Thrive Ledas ribbons
* compatibility with NitroPack (CU-232f9nh)
* compatibility with ProvenExpert badge (CU-2nv12n8)
* compatibility with RankMath SEO and Google Analytics GA4 property (CU-2je6juk)
* exclude rcb-calc-time from scanner result source url (CU-2my9x5r)
* text for list of services not changeable when WPML/PolyLang active (CU-2nfktuh)
* wrong notice in media library about services without privacy policy (CU-2jzg30c)





# 3.0.0 (2022-06-13)


### chore

* add updated blog links to different services (CU-2fjkw82)
* rebase conflicts (CU-2jm1m37)
* remove unnecessery update client third-party scripts in free version (CU-2kat97y)
* update README.txt title and remove WordPress wording (CU-2kat97y)
* update WordPress.org assets (banner, screenshots, CU-2kat97y)


### feat

* provide wizard for v3 features (CU-2fjk49z)


### fix

* compatibility content blocker with latest Typeform embed (CU-2kgpkcb)
* compatibility with Podigee podcast player (CU-2kawh0f)
* sanitize input fields where needed (CU-2kat97y)


### refactor

* remove deprecated renderings and options (CU-2k54e7h)


### BREAKING CHANGE

* we now offer a wizard for all important changes from v2 onwards





## 2.18.2 (2022-06-08)


### chore

* etracker settings moved in their dashboard; adjust notice in service template (CU-2fd0ejp)
* update embera third-party dependency (CU-2d2n29v)


### docs

* clean up changelog (CU-294ugp0)
* update GIFs in wordpress.org product description (CU-2fjkwc6)


### fix

* better error message when TCF GVL could not be downloaded completely (CU-2jm2eb7)
* compatibility with JetEngine Maps Listing component (CU-2jzg7yc)
* compatibility with Thrive Leads ribbons with animations
* compatibility with visual content blocker of play.ht plugin (CU-2jm27t4)
* security vulnerability XSS, could be exploited by logged in administratos (CU-2j8f5fa)
* some PHP notices about missing variables (CU-2j8gba7)


### perf

* introduce new database indexes for large consent database table (CU-2jtrjnz)


### refactor

* extract cookie banner UI to @devowl-wp/react-cookie-banner (CU-2jm1m37)
* use is_multisite instead of function_exists checks (CU-2k54b8m)


### style

* superscript was set too hight (CU-2fcwcx0)





## 2.18.1 (2022-05-24)


### fix

* migrations did not work as expected for newer features and existing users (hotfix, CU-2f1fcfv)





# 2.18.0 (2022-05-24)


### chore

* highlight consent options equally in design presets (CU-20chay0)
* show in-app promo coupons in free version (CU-23tayej)


### docs

* animated banner in wordpress.org product description (CU-237uw9d)
* compatibility with WordPress 6.0 (CU-2e4yvvt)
* mention new features in wordpress.org product description (CU-294ugp0)


### feat

* add optional purpose field to technical definitions (CU-20ch8fp)
* allow to disable the bullet list of groups in customizer (CU-20chd53)
* allow to list all services with their associated groups as superscript in first view (CU-20ch8w2)
* allow to modify the button order in customizer (CU-20chay0)
* allow to use the same styling in customizer of Accept All for Continue without consent and Save button (CU-20chay0)


### fix

* automatically update the privacy policy URL of the RCB service when the privacy policy setting changes (CU-1z4gr4p)
* compatibility with local Windows environment as all templates are shown as free
* compatibility with Rodich theme and their Google Maps shortcode (CU-2eg9czv)
* contact form 7 showed up without any Google reCAPTCHA script (CU-2eghepk)
* correctly reset new feature defaults for existing installations (CU-20ch8be)
* correctly sync Settings > Privacy policy setting in cookie settings (CU-1z4gr4p)
* do not translate texts with placeholder in translation editor (TranslatePress, CU-2f1fcfv)
* facebook pixel enabled all facebook services in scanner (CU-2eghepk)
* make privacy policy required and show notice for already existing services without URL (CU-1z4gr4p)
* no reuse of consent UUID to prevent tracking of consent concatenation on server side (CU-20che0e)
* preview images for youtube-nocookie.com embeds (CU-2f1fcfv)
* show correct status for Content Blocker in admin bar menu (CU-2dz5058)
* update all on-premise / local services with updates privacy policy from Cookies > Settings (CU-1z4gr4p)
* update texts in cookie banner to be compliant with latest law (CU-2cbpypb)
* use range input slider for all PX values in customizer (CU-20chay0)
* use range input with value with unit in customizer (CU-20chay0)


### refactor

* move consent management to @devowl-wp/cookie-consent-web-client
* namings for headless-content-blocker scan options (CU-2eghepk)





## 2.17.3 (2022-05-13)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 2.17.2 (2022-05-09)


### fix

* compatibility with Enfold/Avia video embeds and visual content blockers (CU-2e50h21)
* compatibility with OSM - OpenStreetMap plugin (CU-2e512a8)
* compatibility with platform.js and YouTube subscribe embed (CU-2dkvyrh)
* compatibility with WP Download Codes and download files greater than 50 MB (CU-2e51kwj)
* dynamic predecision for GEO-restriction always returned false (CU-2dzb1xr)
* listen to URL changes for custom legal links (CU-2dkw9dk)





## 2.17.1 (2022-04-29)


### fix

* compatibility with Buddyapp theme as banner buttons were not clickable (CU-2chdca5)
* compatibility with BuddyPress and cookie banner not visible (CU-2cx02ch)
* compatibility with CAPI events in Facebook for WordPress plugin (CU-2buj68e)
* compatibility with Essential Addons for Elementor and blocked content in tabs (CU-2d89n4c)
* compatibility with podcaster.de and podcast-player plugin (CU-2d89n4c)
* compatibility with Social Feed Gallery instagram feed (CU-2d8ba1v)
* duplicate rule in Google Analytics content blocker templates (CU-23tdjz8)
* hero visual content blocker is sometimes cut through overflow or too small parent containers (CU-2d89n4c)
* never block any dns-prefetch link tags as they are GDPR compliant without any blocking (CU-22h5xz6)
* service was shown in two groups after it got moved to another group (CU-22h6bee)
* support multisites with more than 100 subsites (CU-2de4am1)
* when changing a post also scan the translated page if WPML, PolyLang or TranslatePress is active (CU-23tehfc)





# 2.17.0 (2022-04-20)


### chore

* add a description to the texts section in customizer (CU-2195q0e)
* block channel embed of Anchor.fm in content blocker (CU-bcwmqj)
* code refactoring and calculate monorepo package folders where possible (CU-2386z38)
* enable media library selection for content blocker image (CU-eb4h2q)
* implement UI for new content blocker visual settings (CU-eb4h2q9)
* introduce predefined images for content blocker content types (CU-1y2d0mb)
* prepare new visual content blockers for lite version (CU-eb4h2q)
* remove React and React DOM local copies and rely on WordPress version (CU-awv3bv)
* store embed thumbnails in a more generic folder in wp-content/uploads (CU-eb4h2q)
* update embera (CU-eb4h2q)


### feat

* allow content blocker with preview images in list of consents (CU-eb4h2q)
* allow to create navgiation / menu links with one click instead of shortcodes (CU-we4qxh)
* allow to export / import visual content blocker settings (CU-eb4h2q)
* implement visual content blocker with visual audio player (CU-eb4h2q)
* introduce new visual settings in Content Blocker form (CU-eb4h2q)


### fix

* better explain the Matomo Tag Manager script URL in service template (CU-2386cvv)
* block 1.gravatar.com in Gravatar content blocker template (CU-2200n8k)
* cleanup code and adjust checklist for legal links (CU-we4qxh)
* compatibility of nav menus with WPML (CU-we4qxh)
* compatibility with customizer theme and disabling the footer link in the customizer (CU-244r9ag)
* compatibility with Gravity Geolocation and Google Maps (CU-23z12mr)
* compatibility with latest version of HappyForms and Google reCAPTCHA (CU-1znd8x2)
* compatibility with TranslatePress Automatic User Language Detection popup and blurred popup (CU-244r841)
* do not show busy indicator in scanner result table when not needed (CU-23tchda)
* download thumbnail in standard format and force 16/9 ratio for YouTube videos (CU-eb4h2q)
* drill down visual thumbnail to nested blocked content when parent gets visual (CU-1z4fxer)
* improved compatibility with Podigee (CU-eb4h2q)
* improved compatibility with WP YouTube Lyte (CU-eb4h2q)
* in multisite environments there could be a wrong WP_COOKIEPATH, respect always the latest in document.cookie (CU-23z12mr)
* provide a grouped admin menu node for all Real Cookie Banner actions (CU-1zad7fx)
* remove duplicate URLs from scanned sitemaps (CU-2200n8k)
* same font size for essential button as default value of accept all button (CU-23kq9gb)
* show busy indicator when unblocking visual content blocker (CU-1z4ndd2)
* show visual content blocker within tab panels (CU-23kq9gb)
* when using animations the header was flickering while scrolling (CU-2c60q8h)


### perf

* lazy load visual content blockers in a more convenient way using idle callbacks (CU-eb4h2q)


### refactor

* extract composer dev dependencies to their corresponding dev package (CU-22h231w)
* move more files to @devowl-wp/headless-content-unblocker
* move wordpress packages to isomorphic-packages (CU-22h231w)
* put composer license packages to @devowl-wp/composer-licenses (CU-22h231w)
* rename wordpress-packages and wordpress-plugins folder (CU-22h231w)
* revert empty commits for package folder rename (CU-22h231w)
* use phpunit-config and phpcs-config in all PHP packages (CU-22h231w)





## 2.16.2 (2022-04-04)


### chore

* add more security hashes for disabled footer (CU-23292y8)


### fix

* better compatibility with Popup Maker and delayed content blocker creation (CU-22pyyhj)
* blocked DNS prefetches were not indicated as Blocked in scanner results (e.g. WordPress Emojis, CU-22h6rp3)
* compatibility with Hero Maps Premium (CU-2202t4e)
* compatibility with JetElements Advanced Maps (CU-22q59y5)
* compatibility with latest Divi version and some unresponsive behavior (CU-20xrmn7)
* compatibility with Widget for Google Reviews (CU-2202q1c)
* compatibility with WP Staging and scanner (CU-1ykd052)
* compatibility with WP Video Lightbox (CU-294vh4j)
* ignoring external URLs did not work in real-time (transient not updated, CU-22wkx1g)


### style

* blurry cookie banner when using Age Gate plugin (CU-22wtfv3)
* history select dropdown wrong color in dark mode (CU-22pyy0u)





## 2.16.1 (2022-03-15)


### chore

* update TCF dependencies to latest version (CU-22bavpa)
* use wildcarded composer repository path (CU-1zvg32c)


### fix

* adjust US data processing consent setting description (CU-20cherc)
* bypass geo-restriction when using Lighthouse page speed score user agent (CU-20chp0h)
* change privacy settings modal did not show accepted visual content blockers (CU-1znufvk)
* compatibility with latest Oxygen page builder version (CU-20crzbn)
* compatibility with SiteGround Optimizer (CU-1znmzvx)
* correctly invalidate scanner query transients for post deletion and when invalidating preset cache (CU-20jc4q1)
* disable animations in Firefox mobile as it breaks the layout (CU-20jbyp5)
* fire OptInAll event after GTM/MTM datalayer pushes (CU-20162wr)
* notice while exporting consent by UUID (CU-2015tvy)
* recommend to use the change privacy preferences shortcode on every page (e.g. footer, CU-20chbhc)
* scanner on recurring exception reruns successful jobs again (CU-20jc0cf)
* show notice when changing the service group for a preset (CU-20ch93c)


### perf

* cache dashboard notice about recommendations to speed up admin load (CU-20jc4q1)
* cache external URL hosts result as it slows down the admin area (CU-20jc4q1)
* use correct grouping for read external URLs (CU-20jc4q1)


### refactor

* make plugin updates independent of single store (CU-1jkmq84)


### style

* no line break in footer when using mobile experience (CU-20jbyp5)
* use correct text align when theme uses justified text align (CU-1znufvk)


### test

* smoke tests





# 2.16.0 (2022-03-01)


### chore

* add links to useful resources and blog posts about specific thematics (CU-1wepcvt)
* additional notice for WordFence template about their IP transmission to the cloud (CU-1y7vxg1)
* block all plugins from Ninja Forms when forms created with Ninja Forms are blocked (CU-1za7zg5)
* block Instagram background images embedded by tagDiv (CU-1ydpf9k)
* content blocker rule to block OpenStreetMap embedded via "Ultimate Maps by Supsystic" (CU-1yyy4ae)
* provide ready promise for OptInAll event (CU-1wernq1)


### ci

* use Traefik and Let's Encrypt in development environment (CU-1vxh681)


### feat

* new customizer setting to only use animations on mobile devices (CU-1xwnv8m)
* new service and content blocker template etracker (CU-1wernq1)
* new service and content blocker template Facebook Graph (CU-1w8rmkp)
* new service and content blocker template Google User Content (CU-1w8rmkp)
* new service and content blocker template trustindex.io (CU-1w8rmkp)


### fix

* allow current language for other blogs in multisite for consent forwarding (CU-1ydjdeg)
* allow to apply code dynamics to code on page load (CU-1wernq1)
* better memory allocation for scanner and persisting found markups to database (CU-1ydq6ff)
* block CSS styles in style-attributes of HTML elements (CU-1ydpqa1)
* compatibility with latest X Pro theme and YouTube embed (CU-1ydp482)
* compatibility with OptimizePress page builder (CU-1ydtzkv)
* compatibility with Thrive Visual Editor and background youtube videos (CU-1yyxmwn)
* compatibility with TinyMCE and OceanWP (CU-cmwwwj)
* compatibility with WP Grid Builder and lazy loading facets (CU-1y25df6)
* compatibility with WP YouTube Lyte (CU-1yyrrw1)
* compatibility with wpDiscuz and Gravatar content blocking (CU-1z4ghy7)
* compatibility with wpDiscuz and Gravatar content blocking when sorting and posting comments (CU-1z4ghy7)
* compatibility with YouTube Embed Plus (CU-1z4gg3k)
* compatibilty with latest Divi video module and overlay (CU-1yyyc2d)
* correctly show blocked URL in scanner results for inline styles (CU-1ydq6ff)
* detect Google Analytics service template without inline script (CU-1yt64aa)
* do migrations also for prerelease versions (CU-1ydq6ff)
* do not anonymouize assets when anti-ad-block system is deactivated (CU-1ydtzkv)
* empty alt text for cookie banner logo (CU-1yduvtv)
* ignore URLs to files while scanning (CU-1za72vj)


### style

* do not break all words in service groups description (CU-1ydutuz)





# 2.15.0 (2022-02-11)


### feat

* new content blocker template Divi Contact form (CU-1wepwec)
* new content blocker template Five Star Restaurant Reservations form with reCAPTCHA (CU-1vqz6f1)
* new service and content blocker template Piwik PRO (CU-1wernc9)


### fix

* allow to determine if preset is active depending on active theme (CU-1wepwec)
* compatibility to WP Grid Builder Map Facet add-on (CU-1y25df6)
* compatibility with MyListing directory theme (CU-1y7v6cm)
* compatibility with Salient theme and Google Maps (CU-1y7xfwx)
* compatibility with tagDiv composer and Vimeo/YouTube playlists (CU-1xwmenz)
* compatibility with tagDiv Composer page builder (CU-1xwmenz)
* compatibility with Ultimate Member logout page as it automatically logout while scanning pages (CU-1xwmc5f)
* compatibility with WooCommerce Google Analytics Pro when using manual tracking ID (CU-1y7vj2j)
* compatiblity with Norebro Theme (CU-1wmhnke)
* warning about enable_local_ga when Perfmatters is active


### perf

* reduce lifecycle rerenderings by moving height calculations to CSS var implentation (CU-1xwnnwu)





## 2.14.3 (2022-02-04)


### chore

* show notice about TCF illegal usage (CU-1wmjkr6)





## 2.14.2 (2022-02-02)


### build

* use correct namespace in ember composer package through custom patch


### fix

* compatibility with Autoptimize when obkiller is active (CU-1weqdr2)
* compatibility with Divi contact forms and Google reCAPTCHA (CU-1wepwec)
* security issue (only as signed-in uses exploitable) as the reset-all action did not have a CSRF token (CU-1werk7m)
* tcf consent is correctly saved, but wrong at time of changing privacy preferences (CU-1w9587v)


### style

* close icon is not clickable when overlay is deactivated
* long links in indivual privacy leads to horizontal overflow (CU-1vxgxxb)





## 2.14.1 (2022-01-31)


### chore

* clean up and refactor coding for image preview / thumbnails (WIP, CU-1w3c9t7)
* introduce plugin to extract image preview / thumbnails from embed URLs (WIP, CU-1w3c9t7)
* new developer API wp_rcb_invalidate_presets_cache (CU-1w93u4z)


### fix

* compatibility with Bridge theme and their Elementor Google Map shortcode (Qode, CU-1vxgywx)
* facebook.com got found as external URL when using noscript-tag (CU-1vqz5av)
* google-analytics.com got found as external URL when using noscript-tag (e.g. PixelYourSite, CU-1vqx293)
* move Already exists tag to own database column (CU-1vqym25)
* native integration for MailChimp for WooCommerce to not set cookies (CU-1y7r3r1)
* provide _dataLocale parameter to all our REST API requests to be compatible with PolyLang / WPML (CU-1vqym25)
* show error message if scanner results coult not be loaded in scanner table (CU-1v6c7nv)
* unify enqueue_scripts hooks to be compatible with AffiliateTheme (CU-1xpm56k)


### style

* overflow on horizontal screen when using Elementor landingpage Hero section (CU-1w3c2v8)





# 2.14.0 (2022-01-25)


### chore

* add more security hashes for disabled footer (CU-1znbady)
* add notice to mobile experience in free version as it is always responsive even in free (CU-2328pwb)
* update Facebook provider to Meta provider for all FB service templates (CU-23kf838)
* update upgrade notice to be more descriptive about update process (CU-23kf838)


### feat

* allow to skip failed jobs (e.g. scan process, CU-1px7fvw)
* introduce new close icon in cookie banner header (CU-22b6qqj)


### fix

* compatibility with latest ExactMetrics Premium version (CU-23keqgb)
* compatibility with ProgressMap (Google Maps, CU-23284bc)
* config page could not be loaded if there is no admin color scheme defined (CU-23djh08)
* reduce required length of Hotjar ID to 5 instead of 7 (CU-23dk3f1)
* shortcode buttons did not work as expected with custom HTML tag (CU-23dmpjf)
* umlauts could not be saved in opt-in scripts (CU-1zb10r8)


### refactor

* extract unblocking mechanism to @devowl-wp/headless-content-unblocker (CU-23dqww5)


### style

* cookie banner had a small gap on the bottom when mobile experience is active (CU-237tnje)





# 2.13.0 (2022-01-17)


### build

* create cachebuster files only when needed, not in dev env (CU-1z46xp8)
* improve build and CI performance by 50% by using @devowl-wp/regexp-translation-extractor (CU-1z46xp8)


### chore

* new developer filter RCB/IsPreventPreDecision (CU-1yk0nxf)
* suppress webpack warnings about @antv/g2 as it does not impact the frontend but disturbs CI and DX (CU-1z46xp8)


### feat

* introduce new mobile experience (CU-nz2k7f)
* new content blocker template HappyForms with Google reCAPTCHA (CU-1znd8x2)
* new service and content blocker template Analytify Google Analytics v4 (CU-qtf2u6)
* new service and content blocker template ExactMetrics Google Analytics v4 (CU-1xgxrnt)
* new service and content blocker template Klaviyo (CU-1x5enat)
* new service and content blocker template Kliken (CU-1x5ejtu)
* new service and content blocker template MonsterInsights Google Analytics v4 (CU-1xgxrnt)
* new service and content blocker template TI WooCommerce Wishlist (CU-1x5e0jt)
* new service and content blocker template WooCommerce Google Analytics Pro (CU-1z4eara)
* simulate viewport in List of consents when viewing a cookie banner (CU-nz2k7f)


### fix

* allow to disable the powered by link via our license server (CU-1znbady)
* compatibility with a3 Lazy Load (CU-22gym0m)
* compatibility with WP Contact Slider (CU-1y7nw9p)
* compatibility with WP ImmoMakler Google Maps (CU-200ykt6)
* compatibility with YouTube + Vimeo + Premium Addons for Elementor (CU-1wecmxt)
* correctly break line for dotted groups in cookie banner on iOS safari (CU-nz2k7f)
* detect more ad blockers in admin page (CU-1znepfw)
* empty external URL shown when plugin disable WordPress Emojis is active (CU-1y7rr78)
* for older WP < 5.4 versions an encodedString was printed to website (CU-1yk0may)
* rule to block Google Maps JS API in content blocker for Levelup theme compatibility (CU-20100kp)
* use anchor-links for shortcodes instead of class so they can be used without shortcodes, too (CU-1z9yf6b)


### refactor

* move scanner to @devowl-wp/headless-content-blocker package (CU-1xw52wt)


### style

* scrollbar did not look pretty in windows together with dialog border radius (CU-1z9yaaq)


### test

* compatibility with Xdebug 3 (CU-1z46xp8)





# 2.12.0 (2021-12-21)


### chore

* show notice in dashboard when using an language which has incomplete translations in RCB (CU-1vc3ya0)


### feat

* introduce minimal translations for frontend: FR, IT, PL, RO, NL, TR, RU, BG, CS, DA, SV, FI, GL, PT, ES (CU-1vc3ya0)
* new service template for WooCommerce Geolocation (CU-1rgeyre)


### fix

* check for consent before doing WooCommerce default customer location (CU-1rgeyre)
* compatibility with Akea theme when shortcode links were not clickable (CU-1y232uq)
* compatibility with customizer and OceanWP (use async wp.customize.control, CU-1vc3y2f)
* compatibility with Elementor Hosted websites (CU-1xw5rqp)
* compatibility with Elementor overlay, the content blocker button was not clickable (CU-1xpm3v3)
* compatibility with Page Links To plugin and plugins overwriting permalinks (avoid scanner takes external URL, CU-1xw95xq)
* compatibility with Ultimate Addons for Elementor and Google Maps (CU-1xpm0ze)
* compatibility with WPForms and Google Maps (CU-1xpm0ze)
* in some edge cases, the own URL was shown as external URL (CU-1xw7bmp)
* return value for jQuery.fn.fitVids (CU-1xw9jnb)


### refactor

* move WordPress scripts to @devowl-wp/wp-docker package (CU-1xw9jgr)





## 2.11.2 (2021-12-15)


### chore

* introduce new filter RCB/SetCookie (CU-1xpffw5)


### fix

* recommended templates are shown as non-existing if already existing in scanner tab (CU-1xpfu3p)





## 2.11.1 (2021-12-15)


### chore

* backwards compatible footer visibility in list of consents table (CU-1vhtwa2)
* cleanup code for scanner (CU-1v6cf91)
* description of the legitimate interest and essential cookies according to the TTDSG concretized (CU-1wejt3d)
* introduce new PHP api wp_rcb_consent_given (CU-1rgeyre)
* introduce plugin and design version for new consents (CU-1vhtwa2)
* introduce query argument validations for scanner (CU-1v6crwz)
* new developer filter RCB/Presets/Cookies/Recommended and RCB/Presets/Blocker/Recommended (CU-1xazcrh)
* remove non-saw-out descriptions from content blocker templates to save space in the content blocker (CU-1vhtwa2)


### docs

* highlight availability of German formal translations in wordpress.org description (CU-1n9qnvz)


### fix

* allow to dismiss the request new consent notice (CU-1wtzm8t)
* apply preset middlewares in correct order (CU-1x5cj8w)
* compatibility with Ark theme and jQuery(window).load (CU-1wznta2)
* compatibility with fitVids when using together with a caching plugin (CU-1wm4u9v)
* compatibility with Journey theme (indieground, CU-1wu21c3)
* compatibility with latest Advanced Ads version and floating tracking (CU-1vxejft)
* compatibility with Plesk security as hosts are not allowed in scanner result URLs (CU-1vxd9gz)
* compatibility with ProvenExpert PRO Seal in ProvenExpert content blocker (CU-1xb3cmd)
* consider empty values for query parameters as optional in scanner (CU-1x5az10)
* do no longer request consent for abandoded TCF vendors (CU-1xaz66y)
* external DNS prefetches should be checked again against known presets (CU-1vxd8qc)
* false-positive when using Google Analytics with googletagmanager.com and gtag directive (CU-1v6crwz)
* find inline scripts semantically loading another script and show as external URL (CU-1v6cf91)
* formal german texts got not updated for new Real Cookie Banner service (CU-1vxdu4n)
* only remove external URLs while scanning when a proper preset was also found (CU-1v6cf91)
* recommened Jetpack Site Stats when module is active (CU-1v6c4da)
* refreshing the settings form with F5 leads to an error (CU-1weh6c2)
* register custom post types and taxonomies earlier (CU-1rgeyre)
* scanner shows Google Trends when using an unknown Google service (CU-1vxd8qc)
* show potential external URL found in inline-script (CU-1v6cf91)
* the new MonsterInsights update could no longer be scanned (missing protocol in script URL, CU-1x5az10)
* unblock attributes also for selector-syntax applied on inline scripts (CU-1xb6wg7)


### refactor

* move mustHosts definitions into scanOptions (CU-1v6crwz)


### style

* content blocker last teaching should be above the link and styled as teaching (CU-1vhtwa2)
* customizer presets should respect hidden powered-by-link
* do not show footer for visual content blockers as not needed (CU-1vhtwa2)
* show USA data processing notice in visual content blocker only when needed (CU-1vhtwa2)





# 2.11.0 (2021-12-01)


### chore

* improving the description of cookies set by Real Cookie Banner (CU-1td2xu0)
* texts for recognized adblocker more clearly expressed (CU-1hwuugw)


### docs

* adjustment of the product description to the new legal situation (CU-1rvxtf1)


### feat

* introduce formal german translations (CU-1n9qnvz)
* new service and content blocker preset Perfmatters Local Analytics (CU-knc88p)
* new service and content blocker template Komoot (CU-1qtja83)
* new service template WP Cerber Security (CU-1qtja83)


### fix

* allow to overwrite attributes when extending from a preset (CU-knc88p)
* automatically update the Real Cookie Banner service for this update (CU-1td2xu0)
* compatibility with latest React v17 version of WordPress 5.9 (CU-1vc94eh)
* compatibility with YouTube inside Ultimate Addons for Elementor (CU-1vqmbh4)
* compatiblity with Thrive Events maps and LeafLet (CU-1vhzm2e)
* compatiblity with WordPress 5.9 (CU-1vc94eh)
* find semantic IIFE scripts which load another external script and show as scanned result (CU-1v6cf91)
* in some cases safari lead to a race condition and some scripts did not correctly load (CU-1ty9n1b)
* introduce new legal basis for Real Cookie Banner service (legal-requirement, CU-1td2xu0)
* truncate service description in list view after three rows (CU-1td2xu0)





## 2.10.1 (2021-11-24)


### chore

* block Google Maps embedded with Premium Addons for Elementor (CU-1u409yv)


### fix

* compatibility with WP Cloudflare Super Page Cache plugin (CU-1uv3wuf)
* consider newly requested consent as no-consent-given in consentApi (CU-qtbjxk)


### perf

* large websites with a lot of external URLs makes the WordPress admin slow (CU-1u9wehh)


### style

* avoid CLS animation warning in Lighthouse when animations are deactivated (CU-1u9xage)





# 2.10.0 (2021-11-18)


### feat

* new content blocker template Elementor Forms with Google reCAPTCHA (CU-nqbu52)


### fix

* add TCF stub to anti-ad-block system (CU-1phrar6)
* compatiblity with Themeco X Pro page builder (CU-11eagky)
* consents could not be given in private wordpress.com sites (CU-1td2p11)
* do not show all Facebook services when only one is found (CU-1nn1qrg)
* missing Linkedin Partner ID in service template for noscript fallback (CU-rga6b3)
* rename some cookies to be more descriptive about their origin (CU-1tjwxmr)
* show a warning in main settings page when the user is using an adblocker (CU-1hwuugw)
* show essential services' labels in content blocker form (CU-p5fgk8)
* show notice if GTM/MTM is not defined as service but setted as manager (CU-z9n7g2)
* with some MySQL database versions the scanner found external URLs are not displayed (CU-1tjtn8q)


### refactor

* save user country in consent itself instead of independent revision (CU-1tjy2nr)





## 2.9.3 (2021-11-12)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 2.9.2 (2021-11-11)


### chore

* remove not-finished translations from feature branches to avoid huge ZIP size (CU-1rgn5h3)


### fix

* block Google Analytics embedded via Rank Math as locally hosted gtag (CU-1q2x5pa)
* block Google Maps in Elementor widget
* block gtag events in WooCommerce Enhanced Tracking (CU-1qe7tam)
* compatibility with latest Ninja Forms update (CU-1uf8fq9)
* compatibility with Modern Events Calendar and Google Maps (CU-1qecxy4)
* compatibility with UCSS / CCSS in LiteSpeed Cache plugin (CU-1m1h4mh)
* content blocker for Google Maps in WPResidence theme
* correctly display special characters in page dropdown in settings (CU-1phmb9g)
* correctly filter modified publisher restrictions for TCF purposes (CU-1rarxc7)
* do not block content in script text templates (CU-1qe7t0t)
* filter by URL with more accurate pure referer respecting current permalink settings (CU-ad0gf1)
* redirect back to scanner when creating the WooCommerce service (service without content blocker, CU-1nn08eb)


### refactor

* extract content blocker to own package @devowl-wp/headless-content-blocker (CU-1nfazd0)
* extract HTML-extractor to own package @devowl-wp/fast-html-tag


### style

* line height in header of elementor widget so content blocker text does not overlap





## 2.9.1 (2021-11-03)


### fix

* create visual content blocker within responsive container (like Vimeo, CU-1mju68j)
* do not lazy load Code on page load scripts when WP Rocket is active (CU-1mjk6cn)
* never block inline scripts only holding JSON objects (CU-1mjv9gh)
* try to find possible visual content blockers in hidden modals (CU-1my8az3)
* warning in PHP 8 when creating a new service (CU-1my8zcu)
* warning in PHP 8 when using WP CLI (CU-1my8zcu)





# 2.9.0 (2021-10-12)


### feat

* allow to filter by context, period and URL in list of consents (CU-ad0gf1)
* block Vimeo Showcases in Vimeo content blocker
* new service and content blocker template Taboola (CU-n1bn4x)


### fix

* allow to reset group texts correctly for the current blog language (CU-1k51cgn)
* compatibility with Extension for Elementor plugin (CU-1kvu486)
* compatibility with Meow Lightbox (CU-1m784c9)
* compatibility with WP Google Maps Gold add-on (CU-1kankt3)
* compatiblity with Groove Mneu Plugin (CU-1kgeggn)
* do not allow to import PRO templates in free version as we cannot ensure up-to-date (CU-1bzrthu)
* do not show empty context in context dropdown if there were already consents
* do not try to expose empty hosts / URLs in content blocker settings (CU-1k51ax2)
* remove Google Adsense warnings in console when ads are initialized multiple times (CU-1m7c86a)
* scanner did not found Google reCAPTCHA when used standalone (CU-1kvurfe)
* show fallback language for language context is list of consents
* use correct user locale for REST API requests in admin area when different from blog language (CU-1k51hkh)


### perf

* block very (very) large inline CSS (like fusion builder does) took up to 5 seconds (CU-1kvpuwz)





# 2.8.0 (2021-09-30)


### build

* allow to define allowed locales to make release management possible (CU-1257b2b)
* copy files for i18n so we can drop override hooks and get performance boost (CU-wtt3hy)


### chore

* english translation revision (CU-vhmn9k)
* prepare for continuous localization with weblate (CU-f94bdr)
* remove language files from repository (CU-f94bdr)
* rename 'Statistic' to 'Statistics' (CU-12gwu5r)


### ci

* introduce continuous localization (CU-f94bdr)


### feat

* allow to declare an external URL for imprint and privacy policy page (CU-kv7qu2)


### fix

* allow to translate external URL of imprint and privacy policy page with WPML and PolyLang in customizer (CU-kv7qu2)
* backwards-compatible Statistic cookie group naming for service templates (CU-12gwu5r)
* block content also on pages which got declared as hidden in cookie settings (CU-1jkue32)
* block Google Maps in Adava with Fusion Builder as "Fusion Google Map" (CU-12b2jft)
* content blocker for Google Maps in Avada theme
* custom config for COOKIEPATH never should be empty (CU-1jth67d)
* do not follow CORS redirected URLs in scanner (CU-11m6me9)
* do not show cookie banner in legacy widget preview coming with WP 5.8 (CU-1jdzfnn)
* link for customer center in Licensing tab not present (CU-vhmn9k)
* make animations work again in Divi page builder when a custom link with blocked URL got created (CU-1jz6bgn)
* save job result for cors requests while scanning pages (CU-1je508f)
* scanner threw an error when using WP < 5.5 and deleting a file


### perf

* remove translation overrides in preference of language files (CU-wtt3hy)


### refactor

* grunt-mojito to abstract grunt-continuous-localization package (CU-f94bdr)
* introduce @devowl-wp/continuous-integration
* introduce new command with execa instead of own exec implementation


### style

* do line break content blocker buttons (CU-12b05vm)





# 2.7.0 (2021-09-08)


### chore

* translate new service templates (CU-yrgfkk)


### docs

* mention support for automatic video playback for Dailymotion and Loom at wordpress.org (CU-yrge7n)


### feat

* autoplay for Loom and Dailymotion (CU-yrge7n)
* new service and content blocker template Dailymotion (CU-n1f306)
* new service and content blocker template Giphy (CU-mt8ktd)
* new service and content blocker template LinkedIn Ads (Insight Tag, CU-rga6b3)
* new service and content blocker template Loom (CU-u9fxx7)
* new service and content blocker template OpenStreetMap (CU-pn8mu0)
* new service and content blocker template TikTok Pixel (CU-p1a7av)
* new service and content blocker template WordPress Plugin embed (CU-p382wk)


### fix

* adjust texts for powered-by link (CU-we5cq1)
* allow force hidden also for absolute positioned content like Dailymotion embed
* bypass CMP – Coming Soon & Maintenance Plugin when scanning a site (CU-118ud0m)
* bypass Under Construction by WebFactory plugin when scanning a site (CU-118ud0m)
* compatibility with lazysizes (used e.g. in EWWW, CU-11ehp99)
* content blocker removes inline style in some cases (e.g. when parent is wrapper)
* do not clear cache too often when accesing the Dashboard and no consents are given yet (CU-10huz72)
* extract @font-face CSS rules correctly (Divi latest update, CU-118mpjh)
* php logging Undefined offset: 1 in scanner/Query.php
* server error when content blocker finds CSS style which does not represent an URL (CU-10hruca)
* transmit realCookieBannerOptInEvents and realCookieBannerOptOutEvents variable to GTM/MTM data layer (CU-118ugwy)
* wrong GTM template variables for AddToAny service





## 2.6.5 (2021-08-31)


### fix

* add missing script to be scanned for Google Adsense (CU-yyep3k)
* allow to unblock nested jQuery ready functions (WP Google Maps, CU-wkyk4h)
* compatibility with latest PHP version 8.0.9
* compatibility with latest Thrive Leads plugin version (CU-yrkt9b)
* compatibility with latest Thrive themes & plugins (global CSS variables, CU-wkuq39)
* compatibility with Thrive Quiz Builder (CU-yjt538)
* console warning when google maps is used but jQuery is not yet ready on page load
* decode URLs differently than e.g. JSON attributes when unblocking content (CU-z3zua1)
* do not try to apply content blocker to rewritten endpoints which server downloads / binary data (CU-z9qhnd)
* make CSS functions work when they are blocked via Content Blocker (CU-wkuq39)
* scanner should not find link rel=author links
* with some caching plugins enabled the consent can no longer be saved after x hours (CU-wtj9td)





## 2.6.4 (2021-08-20)


### chore

* update PHP dependencies


### docs

* use redirects for legal documents


### fix

* allow emojis in cookie banner and content blocker (CU-u3xv7j)
* banner not visible for older safari and internet explorer browser (CU-vhq9jn)
* compatibility with latest Avada Fusion Builder (live editor, CU-u9mb2h)
* consider non-WWW host as same host and do not detect as external URL (CU-u9m6rv)
* consider WWW subdomain also for link preconnects and dns-prefetch for the correct template (CU-u9m5e5)
* cookie banner history dropdown gets wrong font color (CU-u9m484)
* do not show content blocker in Fusion Builder live editor (CU-u9mb2h)
* empty Google Analytics 4 opt-in code (CU-w8c0r4)
* false-positive detection of Reamaze in scanner
* modals wrongly titled
* modify composer autoloading to avoid multiple injections (CU-w8kvcq)
* scanner did not find sitemap correctly when WPML is active (CU-vhpgdw)


### style

* delete button in service form in wrong position





## 2.6.3 (2021-08-12)


### chore

* update text when scanner has finished to make clear it is coming from Real Cookie Banner (CU-t1ccx6)


### docs

* enhance wordpress.org product description (CU-rvu601)


### fix

* allow different site and home URL for the scanner to find robots.txt (CU-t1mafb)
* allow optional path to Matomo Host (CU-t1cpvz)
* customizer did not load correctly (CU-u3q46w)
* link to multisite consent forwarding knowledge base article (CU-rg8p46)
* remove React warning in developer console about unique keys (CU-u3q46w)
* scanner compatibility with PHP < 7.3
* www URLs of the same WordPress installations were considered as external URL in scanner (CU-6fcxcr)





## 2.6.2 (2021-08-11)


### fix

* loose sitemap index URLs (CU-rvwmnk)





## 2.6.1 (2021-08-10)


### fix

* link rel blocker should handle subdomains correctly
* userlike blocker should block by their CDN instead of usual URL





# 2.6.0 (2021-08-10)


### chore

* introduce new developer filter RCB/Blocker/IsBlocked/AllowMultiple and RCB/Blocker/ResolveBlockables (CU-7mvhak)
* new developer filter RCB/Blocker/SelectorSyntax/IsBlocked
* update texts for scanner tab (hint, CU-mtddjt)


### docs

* service scanner featured in wordpress.org description (CU-n9cuyh)


### feat

* add 9 new content blockers for existing services (CU-mtdp7v)
* add content blocker for 19 services so the scanner can find it (CU-mtdp7v)
* add new checklist item to scan the website (CU-mk8ec0)
* allow to create a new service from scratch directly within a content blocker form (CU-mk8ec0)
* allow to scan also essential services which could not be blocked (e.g. Elementor)
* automatically rescan updated posts
* block link preconnect's and dns-prefetch's automatically based on URL hosts defined in content blocker (CU-nn7g16)
* handle external URLs popover with Cookie Experts dialog (CU-mk8ec0)
* introduce client worker and localStorage restore functionality (CU-kh49jp)
* introduce functionality to find sitemap or fallback to WP default if not existing (CU-kfbzc6)
* introduce mechanism to scan a site for usable presets and external URLs (CU-kf71p4)
* introduce new package @devowl-wp/sitemap-crawler to parse and crawl a sitemap (CU-kh49jp)
* introduce scanner UI for found presets and external URLs (CU-m57phr)
* introduce UI for scanned markups for predefined presets (CU-m57phr)
* new service and content blocker preset Ad Inserter (plugin, CU-kvcmp7)
* popup notification when scan hast finished and allow to ignore external URLs (CU-m57phr)
* proper error handling with UI when e.g. the Real Cookie Banner scanner fails (CU-7mvhak)
* show global notice when using services without consent
* show recommended services not by content blocker but by dependency (CU-mtdp7v)
* translate scanner into German (CU-n9cuyh)
* use @devowl-wp/real-queue to scan the complete website (CU-kh49jp)


### fix

* add remarketing to Google Ads Conversation Tracking service template (CU-pb9txp)
* allow to block the same element by multiple attributes (CU-p3agpd)
* always save the markup so redundant external URLs can be wiped (CU-mtdp7v)
* automatically start scan process for the first time
* be more loose when getting and parsing the sitemap
* block ad block from Ad Inserter newer than 2.7.2 in content blocker template (CU-kvcmp7)
* change close label text when updating privacy preferences (CU-rgdp01)
* compatibility with Impreza frontend page builder
* compatibility with latest Thrive Architect plugin (CU-p3agpd)
* compatibility with Ultimate Video WP Bakery Page builder add-ons (CU-pd9uab)
* create new service within content blocker shows zero as prefilled group
* do not add duplicate URLs to queue
* do not enqueue real-queue on frontend for logged-in users
* german support link (CU-rg8qrt)
* include all revision data in single consent export
* native integration for Analytify preset (disabled status, CU-n1f1xc)
* native integration for GA Google Analytics preset (disabled status, CU-n1f1xc)
* native integration for MonsterInsights preset (disabled status, CU-n1f1xc)
* native integration for RankMath SEO Google Analytics (install code, CU-n1bd59)
* native integration for WooCommerce Google Analytics preset (disabled status, CU-n1f1xc)
* preset WordPress Emojis should also block the DNS prefetch
* remove extended presets from scan results
* split Google Analytics into two content blockers UA and V4 (CU-nq8c3j)
* tag to fully blocked associated with found count instead of distinct of sites count
* update Facebook Post preset to be compatible with Facebook Video (CU-p1dxwp)
* use correct cookie experts link (CU-mtddaa)


### perf

* speed up scan process by reducing server requests (CU-nvafz0)


### refactor

* introduce new keywords needs for presets (CU-mzf8gj)
* move code dynamic fields to preset attributes (CU-h38crf)
* presets extends should no longer be a class name, instead use identifier (CU-n19da6)
* split i18n and request methods to save bundle size
* use instance for blocked result in RCB/Blocker/IsBlocked filters (CU-nxeknj)


### style

* background color for recommandations admin bar menu
* gray out already existing prestes in service and content blocker template screen
* move Google Ads hint about Adwords ID to the input field





## 2.5.1 (2021-08-05)


### chore

* update TCF dependencies to latest version (CU-pq8wt4)


### fix

* decode and encode HTML attributes correctly and only when needed (CU-q1a82b)
* duplicate external hosts in multisite forwarding leads to invisible banner
* enhance Google Maps Content Blocker to be compatible with WP Store Locator (CU-pkhmqy)
* introduce new unique-write attribute in opt-in field for Google Ads and Google Analytics (CU-raj3eg)
* put powered-by link in banner in same align as the legal links (CU-pn8pcz)
* reload page after consent change (CU-pnbunr)
* reset essential cookies correctly when custom choice is selected


### refactor

* remove TCF global scope coding (CU-pq8wt4)


### style

* make content blocker hosts collapsable instead of showing all (CU-pkhcg8)





# 2.5.0 (2021-07-16)


### chore

* update compatibility with WordPress 5.8 (CU-n9dfx9)


### feat

* new service and content blocker preset Podigee (CU-nzbb2q)


### fix

* assign GetYourGuide preset to Marketing cookie group instead of Functional (CU-nv85ef)
* imported content blockers leads to empty admin page in lite version (CU-nzc6gg)
* regex for Google Ads Conversation Tracking ID too strict





# 2.4.0 (2021-07-09)


### feat

* new cookie and content blocker preset MailPoet (CU-m3dtuf)


### fix

* add EFTA countries to countries where the GDPR applies (CU-mhcqjz)
* compatibility with dynamic modules in Thrive Architect (CU-n9bup4)
* compatibility with Elementor video overlay and lightbox (CU-nkb66n)
* compatibility with Pinterest JavaScript SDK (CU-nkaq8m)
* compatibility with themify.me Builder Maps Pro add-on (CU-nna6bg)
* compatibility with themify.me video modules (CU-nna6bg)
* compatibility with WP Rocket 3.9 (CU-nkav4w)
* cookie groups are sortable again via drag & drop (CU-nhfmkt)
* detect multisite / network wide plugins as active for services (CU-mzb2kw)
* do not block content in Themify.me page builder (CU-nna6bg)
* do not hide blocked elements when they use visual parent from children element
* do not show banner for browsers without cookie support (CU-v77cgg)
* do not stop code execution for opt-in scripts and content blocker when blocked through Ad blocker (CU-ndd0dp)
* explain where to find Google Adwords ID in Google Ads service template (CU-mtav6f)
* lite version dashboard not scrollable (CU-nd8e07)
* recalculate responsive handlers after content got unblocked (CU-nnfb22)
* typo in Google Maps content blocker description





# 2.3.0 (2021-06-15)


### chore

* allow to check for consent with consentApi by post ID (CU-m9e56j)
* introduce new PHP developer API wp_rcb_service_groups() and wp_rcb_services_by_group() (CU-m9e56j)
* simplify text of the age notice (CU-m3a6n2)
* translate new presets (CU-m38dkk, CU-kt8cat, CU-m3dtuf, CU-m15mty)


### feat

* automatically delegate click from content blocker when we unblock a link
* content blocker Google Translate compatible with "Translate WordPress" plugin (CU-m3e1fm)
* define Google Adsense Publisher ID in Google Adsense service template to alloew e.g. auto ads (CU-m7e13d)
* new cookie and content blocker preset Calendly (CU-m38dkk)
* new cookie and content blocker preset MailPoet (CU-m3dtuf)
* new cookie and content blocker preset My Cruise Excursion / meine-landesausflüge (CU-kt8cat)
* new cookie and content blocker preset Smash Balloon Social Photo Feed (CU-m15mty)


### fix

* adjust three customizer presets to be compatible with latest Dr. Schwenke newsletter (Dark patterns, CU-m1e0zn)
* allow service for MailPoet 2 (deprecated plugin, CU-m3dtuf)
* allow window.onload assignments in blocked content (CU-m38dkk)
* block reddit post embed as iframe (CU-m15mty)
* compatibility with Astra theme and hamburger menu (automatically collapse if clicked too early)
* compatibility with BookingKit and blur effect (CU-m1acj0)
* content blocker could not find already existing cookies
* do not show element server-side rendered to improve web vitals (CU-m15mty)
* elementor ready trigger is dispatched too early
* hide Refresh site on consent option as it is not needed (CU-m9dey3)
* load animate.css only when needed (CU-mddt99)
* show warning when accept essentials differs from accept all button type (CU-m1e0zn)


### revert

* disable MailPoet preset as it is not yet ready (https://git.io/JnqoX, CU-m3dtuf)





# 2.2.0 (2021-06-05)


### chore

* clearer differentiation of the plugin's benefits in wordpress.org description (CU-kbaequ)
* translate new cookie and content blocker presets (CU-kt7e5r, CU-kk8gvu, CU-k759kz)
* update Cloudflare service template (CU-ff6vzc)


### feat

* allow match elements by div[my-attribute-exists], div[class^="starts-with-value"] and div[class$="ends-with-value"] (CU-kt829t)
* new content blocker for WordPress login when using e.g. reCaptcha (CU-jqb6y0)
* new cookie and content blocker preset Awin Link and Image Ads (CU-k759kz)
* new cookie and content blocker preset Awin Publisher MasterTag (CU-k759kz)
* new cookie and content blocker preset ConvertKit (CU-kk8gvu)
* new cookie and content blocker preset GetYourGuide (CU-kt829t)
* new cookie and content blocker preset WP-Matomo Integration (former WP-Piwik, CU-kt7e5r)


### fix

* avoid duplicate execution of inline scripts when they take longer than 1 second
* block more JS code in content blocker of "Mailchimp for WooCommerce" template
* compatibility with 'Modern' admin style
* compatibility with Elementor PRO Video API / blocks (CU-kd5nne)
* compatibility with Elementor Video API for Vimeo and YouTube (CU-kd5nne)
* compatibility with Google Maps plugin by flippercode (CU-kn82nw)
* do anonymize localized variables in wp-login.php (CU-jqb6y0)
* do not allow creating a content blocker when you try to assign a cookie to essential group (CU-jqb6y0)
* do not apply content blocker in customizer preview
* page does not get reloaded automatically after consent on safari / iOS (CU-kt8q4n)
* use anti-ad-block system also in login page (CU-kh5jpd)
* use script tag with custom type declaration to be HTML markup compatible (head, CU-kt4njv)





# 2.1.0 (2021-05-25)


### chore

* compatibility with latest antd version
* introduce new developer filter RCB/Misc/ProUrlArgs (CU-jbayae)
* introduce new RCB/Hint section to add custom tiles to the right dashboard section (CU-jbayae)
* migarte loose mode to compiler assumptions
* own chunk for blocker vendors, but still share (CU-jhbuvd)
* polyfill setimmediate only if needed (CU-jh3czf)
* prettify code to new standard
* remove es6-promise polyfill (CU-jh3czn)
* remove whatwg-fetch polyfill (CU-jh3czg)
* revert update of typedoc@0.20.x as it does not support monorepos yet
* upgrade dependencies to latest minor version


### ci

* move type check to validate stage


### docs

* highlight that not all service templates are free in wordpress.org plugin description


### feat

* allow to block content in login page (e.g. using Google reCaptcha, CU-jqb6y0)
* new service and content blocker preset Sendinblue (CU-k3cf3r)
* new service and content blocker preset Xing Events (CU-k3cfab)


### fix

* allow visual parent by children selector (querySelector on blocked content, CU-k7601j)
* block new elements of Popup Maker in content blocker template
* compatibility with Astra theme oEmbed container (CU-k18eqe)
* compatibility with Dynamic Content for Elementor plugin (CU-k7601j)
* compatibility with elementor widgets when they are directly blocked (CU-k7601j)
* do not content block when elementor preview is active
* do not rely on install_plugins capability, instead use activate_plugins so GIT-synced WP instances work too (CU-k599a2)
* padding of content blocker parent got reset
* support for @font-face directive when blocking inline style (CU-k3cf3r)
* visual parent does not work for custom elementor blocker (CU-k7601j)
* when an inline script creates a new DOM element it is sometimes invisible (CU-k3cf3r)
* white screen when searching for duplicate content blockers


### refactor

* move compatibility code to own folder
* own function to override native addEventListener functionality
* style classes to functions for tree shaking (CU-jh75eg)


### revert

* own vendor bundle for blocker


### style

* pro dialog (CU-jbayae)


### test

* make window.fetch stubbable (CU-jh3cza)





## 2.0.3 (2021-05-14)


### fix

* customizer does not work when WP Fastest Cache is active (CU-jq9aua)
* multilingual plugins like Weglot and TranslatePress should show more options in Consent Forwarding setting





## 2.0.2 (2021-05-12)


### fix

* compatibility with PixelYourSite Facebook image tag (pixel)
* compatibility with WP Rocket lazy loading scripts (CU-jq4bhw)





## 2.0.1 (2021-05-11)


### docs

* update README typos


### fix

* new cookie presets are not visible for Weglot users (CU-hk3jfn)





# 2.0.0 (2021-05-11)


### build

* allow to patch scoped build artifact to fix unicode issues (CU-80ub8k)
* allow to set config name for yarn dev
* consume TCF CMP ID via environment variable (CU-h15h9f)
* own JS bundle for TCF banner and enqueue stub (CU-fk051q)
* update wordpress.org screenshot assets (CU-gf917p)
* wrong refernce to PSR-4 namespace


### chore

* add screenshots for TCF compatibility and Geo-restriction (CU-gf917p)
* core features description text (CU-gf7dnf)
* deactivate option to resepect Do Not Track by default (CU-gx1m76)
* increase minimum PHP version to 7.2 (CU-fh3qby)
* introduce new filter to disable setting the RCB cookie via RCB/SetCookie/Allow
* minimum required version of PHP is 7.2
* name cookie designs consistently (CU-g779gw)
* remove classnames as dependency
* rename "cookies" to "services" for consistent wording (CU-f571nh)
* sharp terms of buttons and labels in cookie banner
* update @iabtcf packages to >= 1.2.0 to support TCF 2.1 (CU-h539k3)
* update @iabtcf packages to stable version (CU-g977x9)
* update texts to be more informative about legal basis and print text for Consent Forwarding if active (respects also TCF global scope) (CU-cq1rka)
* use more normal style to be independent from formal/informal language (CU-f4ycka)


### docs

* wordpress.org description revised (CU-gf7dnf)


### feat

* add contrast ratio validator and call-to-action adjustments for TCF compatibility (CU-cq25hu)
* add GVL instance to all available banner contexts (CU-fjzcd8)
* allow to customize the text of the powered-by link (CU-f74d53)
* allow to define a list of countries to show only the banner to them e.g. only EU (Country Bypass, CU-80ub8k)
* allow to export and import TCF vendor configurations (CU-ff0yvh)
* allow to forward TCF consent with Consent Forwarding (CU-ff10cy)
* allow to reset all settings to default in Settings tab (CU-8extcg)
* automatically refresh GVL via button and periodically (CU-63ty1t)
* calculate suitable stacks and add them to revision (CU-fh0bx6)
* compatibility of TCF vendors with ePrivacy USA functionality (CU-h57u92)
* compatibility with TCF v2.1 (device storage disclosures, CU-h74vna)
* complement translations for English and German (CU-ex0u4a)
* completion of English and German translations (CU-ex0u4a)
* completion of English and German translations (CU-ex0u4a)
* contrast ratio warning for non-TCF users, opt-in cookie banner activation through popconfirm (CU-j78m3t)
* create content blockers for TCF vendor configurations (CU-gv58rr)
* download and normalize Global Vendor List for TCF compatibility (CU-63ty1t)
* eight new cookie banner presets (CU-g779gw)
* introduce Learn More links to different parts of the UI (CU-gv58rr)
* introduce new service field to allow opt-out based on legal basis (CU-ht2zwt)
* introduce origin of business entity field for TCF integration (CU-g53zgk)
* introduce revision for TCF vendors and declarations (CU-ff0zhy)
* introduce settings tab for TCF compatibility in Cookies > Settings (CU-cq29n2)
* introduce so-called Custom Bypass so developers can dynamically set a predecision and hide the banner automatically (e.g. Geolocation, CU-80ub8k)
* introduce UI to create a TCF vendor configuration and create TCF vendor configuration REST API (CU-crwq2r)
* introduce UI to edit a TCF vendor configuration (CU-crwq2r)
* native compatibility with preloading and defer scripts with caching plugins (CU-h75rh2)
* new cookie presets for Ezoic (CU-ch2rng)
* new customizer control to adjust the opacity of box shadow color (CU-cz1d9t)
* persist TCF strings for proof of consent and dispatch to CMP API (CU-ff0z49)
* properly replace non-javascript ad tags with current TC String (CU-ct1gfd)
* provide a migration wizard for v2 in the dashboard (CU-g75t1p)
* register new Custom Post Type for TCF vendor configurations (CU-crwq2r)
* show and allow to customize TCF stacks (CU-cq1rka)
* show TCF vendors and declarations (purposes, special purposes, ...) in second view of cookie banner (CU-ff0yvh)
* translate backend into German (CU-ex0u4a)
* translate frontend into German (CU-ex0u4a)
* when navigating to /tcf-vendors/new show a list of all available vendors (CU-crwq2r)


### fix

* add custom bypasses to the DnT stats pie chart (CU-gf4egf)
* add United Kingdom (GB) as default to Country Bypass list (CU-hz8rka)
* assign cookie groups and cookies to correct source language after adding a new language to WPML (CU-hz3a83)
* automatically clear page caches after license activation / deactivation (CU-jd7t87)
* automatically deactivate option to respect DnT header when activating TCF for the first time
* compatibility TCF and WPML / PolyLang
* compatibility with Customizer checkbox values and Redis Object Cache (CU-jd4662)
* cookie history could not be closed when no consent given
* do not output RCB settings as base64 encoded string (CU-gx8jkw)
* first review with Advanced Ads (Pro, CU-g9665t)
* localize stacks correctly and sort by score (CU-ff0zhy)
* make consentAPI available in head scripts
* make group description texts resettable (CU-gf3dew)
* notices thrown when no vendor given (CU-ff0yvh)
* output UUID on legal sites, too (CU-jha8xc)
* show vendor ID in list table of TCF vendors (CU-gf8h2g)
* show vendor list link for TCF banner in footer (CU-g977x9)
* the Lighthouse crawler is not a bot (CU-j575je)
* translate "legitimate interest" always with "Berechtigtes Interesse" (CU-ht31w2)
* translate footer text correctly for TranslatePress / Weglot (CU-ht82qm)
* usage with deferred scripts and content blocker (DOM waterfall, CU-gn4ng5)


### perf

* avoid catastrophal backtracing and speed up regular expression for inline scripts/styles by 90% (CU-j77a9g)
* combine vendor modules to a common chunk for both TCF and non-TCF
* introduce deferred and preloaded scripts for cookie banner (CU-gn4ng5)
* remove TCF CmpApi from non-TCF bundle


### refactor

* create wp-webpack package for WordPress packages and plugins
* introduce bundleAnalyzerOptions in development package
* introduce eslint-config package
* introduce new grunt workspaces package for monolithic usage
* introduce new package to validate composer licenses and generate disclaimer
* introduce new package to validate yarn licenses and generate disclaimer
* introduce new script to run-yarn-children commands
* make content blocker independent of custom post type
* make Vimeo and SoundCloud to Pro presets (CU-gf49yy)
* move build scripts to proper backend and WP package
* move jest scripts to proper backend and WP package
* move PHP Unit bootstrap file to @devowl-wp/utils package
* move PHPUnit and Cypress scripts to @devowl-wp/utils package
* move special blocker PHP classes in own namespace
* move technical doc scripts to proper WP and backend package
* move WP build process to @devowl-wp/utils
* move WP i18n scripts to @devowl-wp/utils
* move WP specific typescript config to @devowl-wp/wp-webpack package
* remove @devowl-wp/development package
* split stubs.php to individual plugins' package


### style

* improve Web Vitals by setting a fixed width / height for the logo (CU-j575je)
* refactor all banner presets (CU-fn68er)


### test

* fix failing smoke test for Real Cookie Banner Lite


### BREAKING CHANGE

* please upgrade your PHP version to >= 7.2





## 1.14.1 (2021-04-27)


### ci

* push plugin artifacts to GitLab Generic Packages registry (CU-hd6ef6)


### fix

* compatibility with Lite Speed Cache; white screen in customizer
* introduce new filter RCB/Blocker/InlineScript/AvoidBlockByLocalizedVariable and fix copmatibility with EmpowerWP/Mesmerize (CU-hb8v51)
* notice array_walk_recursive() expects parameter 1 to be array, integer given
* output buffer callback should be called always and cannot be removed by third parties


### refactor

* use shorter function to get cookie by name (CU-hv8ypq)


### revert

* output buffer callback should be called always and cannot be removed by third parties





# 1.14.0 (2021-04-15)


### chore

* translate new cookie and content blocker presets (CU-h158p2)


### feat

* new cookie and content blocker preset Metricool (CU-gz7ptb)
* new cookie and content blocker preset Popup Maker (CU-gt22gk)
* new cookie and content blocker preset RankMath Google Analytics (CU-gh4gcw)
* new cookie and content blocker preset Thrive Leads (CU-gh4qgh)


### fix

* allow to Add Media in banner description
* allow to extract blocked inline style to own style HTML block (CU-gk0d9a)
* allow to granular block urls in inline CSS (CU-gk0d9a)
* allow to set privacy policy URL per language (WPML, PolyLang, CU-gq33k2)
* avoid catasrophical backtrace when blocking an inline style (CU-gh964b)
* compatibility with LiteSpeed cache buffer
* compatibility with MailerLite content blocker and Thrive Archtiect page builder (CU-gh4hr5)
* compatibility with Ultimate Video (CU-fz6gxc)
* consentSync API returned the wrong found cookie when two cookies use same technical definitions - introduced relevance scoring
* usage with PolyLang with more than two languages and copy automatically to new languages (CU-gt3kam)





## 1.13.1 (2021-03-30)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





# 1.13.0 (2021-03-23)


### chore

* translate and register new presets (CU-fn1j8z, CU-c6vmwh)


### docs

* new compatibilities in wordpress.org description (CU-fk068g)


### feat

* new cookie and content blocker preset Bloom (CU-fn1j8z)
* new cookie and content blocker preset Typeform (CU-c6vmwh)


### fix

* calculate rendered height for banner footer to gain better edge smoothing
* compatibility of content blocker with TranslatePress and Weglot (CU-fz6gxc)
* compatibility with Ultimate Video (CU-fz6gxc)
* export of consents contained notices in some PHP environments (CU-ff0z49)
* show notice for frontend banner if no license is active (CU-fyzukg)
* use the correct permalinks in the banner footer (CU-e8x3em)





# 1.12.0 (2021-03-10)


### build

* plugin tested for WordPress 5.7 (CU-f4ydk2)


### chore

* register and translate new presets (CU-eyzegt, CU-f4yzpm)


### feat

* new cookie and content blocker preset Yandex Metrica (CU-f4yzpm)
* new cookie preset for Bing Ads (Microsoft UET) (CU-eyzegt)
* new cookie preset found.ee (CU-f97ady)


### fix

* more granular translation for TranslatePress for blockers, cookie group, cookies and banner texts





# 1.11.0 (2021-03-10)


### chore

* hide some notices on try.devowl.io (CU-f53trz)


### feat

* added ability to auto play videos if they got unblocked (Divi Page Builder, CU-f51p51)
* added ability to auto play videos if they got unblocked (JetElements for Elementor, CU-f51p51)
* autoplay YoutTube and Vimeo videos after unblocking through content blocker (CU-f558r1)


### fix

* compatibility with Combine JavaScript in WP Rocket (CU-f35k4j)
* compatibility with Divi videos (e.g. YouTube) when using an overlay
* compatibility with JetElements for Elementor Video Player (CU-f51p51)
* compatibility with lazy loaded scripts e.g. WP Rocket when they are present in the configuration list (CU-f35k4j)
* in some cases the blocked content was still display:none after unblocking (e.g. GTranslate, CU-f35k4j)





# 1.10.0 (2021-03-02)


### chore

* update german text for privacy settings history dialog title (CU-ev2070)


### feat

* allow to customize more texts for content blocker (CU-ev2070)
* new cookie preset (CU-ev6jyb)


### fix

* allow HTML formatting in content blocker accept info text (CU-ev2070)
* compatibility with Thrive Architect embeds
* compatibility with Thrive Archtitect Custom HTML block
* do not allow cookie duration greater than 365 (CU-cpyc46)
* do not override position:relative for content blocker





# 1.9.0 (2021-02-24)


### chore

* drop moment bundle where not needed (CU-e94pnh)
* introduce new JavaScript API window.consentApi.consentSync


### docs

* rename test drive to sanbox (#ef26y8)


### feat

* new cookie banner preset 'Ronny's Dialog'
* new customizer option in Body > Accept all Button > Align side by side (CU-cv0d8g)


### fix

* compatibility with X Theme and Cornerstone
* content blocker containers may also have an empty style
* content blocker for JetPack Site Stats too aggressive when using together with wordpress.com
* content blocking for Quform in some cases to aggressive (#ejxq3b)
* do not annonymously server when SCRIPT_DEBUG is active
* do not apply style to parent containers if no style was previously present
* do not show cookie banner when editing in Divi and Beaver Builder page builder
* illegal mix of collations (CU-ef1dtp)
* in some cases the original iframe was blocked, but not completely hidden
* when a profile deactivate syntax highlighting, the cookie form did not work (CU-en3mxa)





# 1.8.0 (2021-02-16)


### chore

* register and translate new cookie and content blocker presets
* show notice for Quform cause content blocker is not necessery (CU-cawja6)


### feat

* allow to apply content blockers to JSON output of e.g. REST services
* improve English translation (#devznm)
* new cookie and content blocker preset Issuu (CU-e14yht)
* new cookie and content blocker preset Pinterest Tag (CU-eb3wu9)
* new cookie and content blocker preset Quform (CU-cawja6)
* new cookie preset Klarna Checkout for WooCommerce (CU-e2z7u7)
* new cookie preset TranslatePress (CU-e14nf6)


### fix

* compatibility Instagram blocker with WoodMart theme
* compatibility with Elementor inline styles
* compatibility with TranslatePress (CU-cew7v9)
* do not block links without class and external URLs
* do not output calculated time for blocker when not requested; compatibility with Themebeez Toolkit
* show correct tooltip when Google / Matomo Tag Manager template can not be created (CU-e6xyc5)





## 1.7.3 (2021-02-05)


### docs

* update README to be compatible with Requires at least (CU-df2wb4)


### fix

* in some edge cases the wordpress autoupdater does not fire the wp action and dynamic javascript assets are not generated





## 1.7.2 (2021-02-05)


### chore

* show notice after one week when setup not yet completed (CU-djx8ga)


### fix

* deliver anonymous assets like JavaScripts files correctly (CU-dgz2p9)
* remove anonymous javascript files on uninstall (CU-dgz2p9)





## 1.7.1 (2021-02-02)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





# 1.7.0 (2021-02-02)


### chore

* allow to edit custom post types and taxnomies to be edited via native UI for debug purposes
* remove limit for cookies and content blockers (CU-d6z2u6)


### docs

* improved product description for wordpress.org (#d6z2u6)


### feat

* new cookie and content blocker preset MailerLite (CU-d10rw9)
* new cookie preset CleanTalk Spam Protection (CU-d93t70)
* new cookie preset WordFence (CU-dcyv72)


### fix

* allow to block inline styles by URL (CU-d10rw9)
* compatibility with Custom Facebook Feed Pro v3.18 (CU-cwx3bn)
* compatibility with FooBox lightbox (CU-dczh1k)
* compatibility with TranslatePress to avoid flickering (CU-dd4a3q)
* compatibility with Uncode Google Maps block (CU-d12m5q)
* content blocker should also execute window 'load' event after unblock (CU-d12m5q)
* do correctly find duplicate content blockers and avoid them (CU-d10rw9)
* do not block twice for custom element blockers (CU-d10rw9)
* translated page in footer is not shown in PolyLang correctly (CU-d6wumw)





# 1.6.0 (2021-01-24)


### chore

* register new cookie and content blockers and update README (CU-cwx3bn)


### feat

* allow to make customizer fields resettable with a button (CU-crwyqn)
* new banner preset in customizer 'Clean Dialog'
* new content blocker preset CleverReach with Google Recaptcha (CU-cryuv0)
* new cookie and content blocker preset Custom Twitter Feeds (Tweets Widget) (CU-cwx3bn)
* new cookie and content blocker preset Feeds for YouTube (CU-cwx3bn)
* new cookie and content blocker preset FontAwesome (CU-cx067u)
* new cookie and content blocker preset Smash Balloon Social Post Feed (CU-cwx3bn)
* preset extends middleware now supports extendsStart and extendsEnd for array properties (CU-cwx3bn)


### fix

* allow all URLs for affiliates in PRO version (CU-cyyh2z)
* compatibility with CloudFlare caches; nonce is no longer needed as we have rate limit in public APIs (CU-cwvke2)
* compatibility with Impreza lazy loading grid (CU-94w719)
* improve UX when creating Content Blocker and open the Add-Cookie form in a modal instead of new tab (CU-cz12vj)
* wrong character encoding for VG Wort preset


### refactor

* remove unused classes and methods


### revert

* always show recommened cookies in content blocker select (CU-cwx3bn)


### style

* do not break line in cookie preset selector description
* use flexbox instead of usual containers for banner buttons (CU-cv0ff2)





# 1.5.0 (2021-01-18)


### chore

* introduce new developer filters RCB/Blocker/KeepAttributes and RCB/Blocker/VisualParent (CU-cn0wvd)
* new Consent API function consentApi.consent() and consentApi.consentAll() to wait for consent
* presets can no be extended by a parent class definition
* register new cookie and content blockers and update README (CU-cewwda)
* translate new presets, update README


### feat

* new content blocker preset Google Analytics (CU-cewwda)
* new cookie and content blocker preset Analytify (CU-cewwda)
* new cookie and content blocker preset ExactMetrics (CU-cewwda)
* new cookie and content blocker preset Facebook For WooCommerce (CU-cewwda)
* new cookie and content blocker preset GA Google Analytics (CU-cewwda)
* new cookie and content blocker preset Mailchimp for WooCommerce (CU-cn234z)
* new cookie and content blocker preset Matomo WordPress plugin (CU-ch3etd)
* new cookie and content blocker preset MonsterInsights (CU-cewwda)
* new cookie and content blocker preset WooCommerce Google Analytics Integration (CU-cewwda)
* new cookie preset Lucky Orange (CU-ccwj8v)
* new cookie preset WooCommerce Stripe (CU-cn232u)
* recommend MonsterInsights content blocker in Google Analytics cookie preset (CU-cewwda)


### fix

* automatically invalidate preset cache after any plugin activated / deactivated
* compatibility with FloThemes embed codes and blocks (CU-cn0wvd)
* do not show footer links when label is empty (CU-cjwyqw)
* do not show hidden or disabled content blocker presets in cookie form
* extended presets can disable technical handling through compatible plugin (CU-cewwda)
* footer not shown when imprint empty in PRO version
* include description in preset search index
* overcompressed logo


### refactor

* presets gets more and more complex, let's simplify with a middleware system


### style

* gray out disabled cookie and content blocker presets
* gray out plugin-specific cookie and content blocker presets
* show a tooltip when a preset is currently disabled





## 1.4.2 (2021-01-11)


### fix

* in some edge cases WP Rocket does blockage twice (CU-ccvvdn)





## 1.4.1 (2021-01-11)


### fix

* hotfix to make presets available again





# 1.4.0 (2021-01-11)


### build

* reduce javascript bundle size by using babel runtime correctly with webpack / babel-loader


### chore

* translate new cookie and blocker presets and register


### ci

* automatically activate PRO version in review application (CU-hatpe6)


### docs

* update README (CU-bevae9)


### feat

* new cookie and content blocker preset ActiveCampaign forms and site tracking (CU-bh04kz)
* new cookie and content blocker preset Discord (CU-c6vmgg)
* new cookie and content blocker preset MyFonts.net (CU-cawhga)
* new cookie and content blocker preset Proven Expert (Widget) (CU-cawhfp)
* new cookie preset Elementor (CU-cawhdk)
* new cookie preset Mouseflow (CU-cawj3n)
* new cookie preset Userlike (CU-cawhr3)


### fix

* apply gzip compression on the fly to the anti-ad-block system (CU-bx0am1)
* compatibility with All In One WP Security & Firewall (CU-bh08zp)
* compatibility with Facebook for WooCommerce plugin (CU-bwwwrt)
* compatibility with Meks Easy Photo Feed Widget Instagram feed (CU-bx0wd7)
* compatibility with Oxygen page builder
* compatibility with video and audio shortcode (CU-bt21kd)
* compatibility with youtu.be domain in YouTube content blocker preset (CU-bt21hp)
* compatiblity with WP Rocket lazy loading inline scripts (CU-bwwwrt)
* compatiblity with WP Rocket lazy loading YouTube videos (CU-byw6ua)
* content blocker for video and audio tags in some edge cases
* cookie preset selector busy indicator (CU-a8x3j0)
* generate dependency map for translations
* jquery issue when not in use (jQuery is now optional for RCB)
* use correct stubs for PolyLang


### perf

* preset PHP classes are only loaded when needed (CU-a8x3j0)
* speed up caching of presets (CU-a8x3j0)


### style

* input text fields in config page (CU-a8x3j0)





# 1.3.0 (2020-12-15)


### chore

* introduce custom powered-by link in PRO version (CU-b8wzqu)


### feat

* introduce rcb-consent-print-uuid shortcode (CU-bateay)
* new cookie and content blocker preset AddThis (CU-beva7q)
* new cookie and content blocker preset AddToAny (CU-beva7q)
* new cookie and content blocker preset Anchor.fm (CU-beva7q)
* new cookie and content blocker preset Apple Music (CU-beva7q)
* new cookie and content blocker preset Bing Maps (CU-beva7q)
* new cookie and content blocker preset reddit (CU-beva7q)
* new cookie and content blocker preset Spotify (CU-beva7q)
* new cookie and content blocker preset TikTok (CU-beva7q)
* new cookie and content blocker preset WordPress Emojis (CU-beva7q)


### fix

* block sandbox attribute for iframes (CU-beva7q)
* compatibility with WP External Links icon in banner and blocker footer (CU-bew81p)
* dashboard in lite version scrolls automatically to bottom (CU-bez8qn)
* list of consents does not expand if not initially saved settings once before
* memory error while reading the consent list (CU-9yzhrr)
* show ePrivacy and age notice even without description in visual content blocker (CU-beurgy)


### refactor

* introduce code splitting to reduce config page JavaScript assets (CU-b10ahe)





## 1.2.4 (2020-12-10)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.3 (2020-12-09)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.2.2 (2020-12-09)


### build

* use correct pro folders in build folder (CU-5ymbqn)


### chore

* update to cypress v6 (CU-7gmaxc)
* update to webpack v5 (CU-4akvz6)
* updates typings and min. Node.js and Yarn version (CU-9rq9c7)


### fix

* anonymous localized script settings to avoid incompatibility with WP Rocket lazy execution (CU-b4rp51)
* automatically deactivate lite version when installing pro version (CU-5ymbqn)
* compatibility with WP External Links (CU-b8w6yv)
* validate cookie host according to RFC 1123 instead of RFC 952 (CU-b31nf0)


### test

* smoke tests for Real Cookie Banner PRO





## 1.2.1 (2020-12-05)


### fix

* sometimes the privacy and imprint link are not correctly redirected (CU-b2x8wp)





# 1.2.0 (2020-12-01)


### chore

* translate new presets
* update dependencies (CU-3cj43t)
* update major dependencies (CU-3cj43t)
* update to composer v2 (CU-4akvjg)
* update to core-js@3 (CU-3cj43t)
* update to TypeScript 4.1 (CU-3cj43t)


### feat

* new cookie preset Zoho Forms and Zoho Bookings (CU-awy9wa)


### refactor

* enforce explicit-member-accessibility (CU-a6w5bv)





## 1.1.3 (2020-11-26)


### fix

* compatibility with WebFontLoader for Google Fonts and Adobe Typekit (CU-aq01tu)
* never block codeOnPageLoad scripts of cookies (introduce consent-skip-blocker HTML attribute, CU-aq01tu)





## 1.1.2 (2020-11-25)


### fix

* code on page load should be execute inside head-tag (CU-aq01tu)
* consent does not get saved in development websites (CU-aq0tbk)
* wrong link to consent forwarding in german WordPress installation





## 1.1.1 (2020-11-24)


### fix

* compatibility with RankMath SEO
* do not block content in beaver builder edit mode (CU-agzcrp)
* do not output rcb calc time in json content type responses (Beaver Builder compatibility, CU-agzcrp)





# 1.1.0 (2020-11-24)


### docs

* add MS Clarity in README


### feat

* new cookie preset Google Trends (CU-ajrchu)
* new cookie preset Microsoft Clarity (#a8rv4x)


### fix

* allow document.write for unblocked scripts (#ajrchu)
* compatibility with upcoming WordPress 5.6 (CU-amzjdz)
* decode HTML entities in content blocker scripts, e.g. old Google Trends embed (#ajrchu)
* ensure banner overlay is always a children of document.body (CU-agz6u3)
* ensure banner overlay is always a children of document.body (CU-agz6u3)
* modify Google Trends to work with older embed codes (CU-ajrchu)
* modify max index length for MySQL 5.6 databases so all database tables get created (CU-agzcrp)
* multiple content blockers should be inside a blocking wrapper (CU-ajrchu)
* order with multiple content blocker scripts (#ajrchu)
* typo in german translation (CU-agzcrp)
* update Jetpack Site Stats and Comments content blocker (CU-amr3f1)
* use no-store caching for WP REST API calls to avoid issues with browsers and CloudFlare (CU-agzcrp)
* using multiple ads with Google Adsense (CU-ajrcn2)
* wrong cookie count for first time usage in dashboard (CU-agzcrp)





## 1.0.4 (2020-11-19)

**Note:** This package (@devowl-wp/real-cookie-banner) has been updated because a dependency, which is also shipped with this package, has changed.





## 1.0.3 (2020-11-18)


### fix

* add Divi maps block to Google Maps content blocker
* banner not shown up in Happy Wedding Day theme
* compatibility with Divi Maps block





## 1.0.2 (2020-11-17)


### fix

* do not show licensing tab in free test drive (#acypm6)





## 1.0.1 (2020-11-17)


### ci

* wrong license.devowl.io package.json


### docs

* wordpress.org README


### fix

* remove unnecessary dependency (composer) package (#acwy1g)





# 1.0.0 (2020-11-17)


### chore

* initial release (#4rruvq)


### test

* fix lite version smoke tests
* fix smoke test
* fix smoke tests for lite version
* fix typo in lite smoke test


* chore!: remove early access notice for newer updates (#4rruvq)
* feat!: use new license server (#4rruvq)
* ci!: release free version to wordpress.org automatically (#4rruvq)


### BREAKING CHANGE

* we are live!
* if you were a early access user, please upgrade to the initial version
* you need to enter your license key again to get automatic updates
* download initial version now here: https://wordpress.org/plugins/real-cookie-banner
