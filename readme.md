# Nova List Card

[![Latest Version on Github](https://img.shields.io/github/release/dillingham/nova-list-card.svg?style=flat-square)](https://packagist.org/packages/dillingham/nova-list-card)
[![Total Downloads](https://img.shields.io/packagist/dt/dillingham/nova-list-card.svg?style=flat-square)](https://packagist.org/packages/dillingham/nova-list-card) [![Twitter Follow](https://img.shields.io/twitter/follow/im_brian_d?color=%231da1f1&label=Twitter&logo=%231da1f1&logoColor=%231da1f1&style=flat-square)](https://twitter.com/im_brian_d)

Add a variety of lists to your dashboard

![nova-list-card](https://user-images.githubusercontent.com/29180903/56833461-88905e80-683c-11e9-8a04-e3a7ce8dc582.png)

### Install
```bash
composer require dillingham/nova-list-card
```

### Basic Usage

```bash
php artisan nova:list-card RecentUsers
```

```php
<?php

namespace App\Nova\Metrics;

use App\Nova\User;
use NovaListCard\ListCard;

class RecentUsers extends ListCard
{
    /**
     * Setup the card options
     */
    public function __construct()
    {
        $this->resource(User::class)
            ->heading('Recent Users')
            ->orderBy('created_at', 'desc')
            ->timestamp()
            ->viewAll();
    }
```

[View more examples](https://github.com/dillingham/nova-list-card#examples)

### Possible Scenarios
- Latest resource / oldest resource
- Upcoming / past due resources
- Top resource by relationship count
- Top resource by relationship's column sum


**Card Width**

Set the card's width, default 1/3
```php
->width('3/5')
```

**Card Heading**

```php
->heading('Top Bloggers')
```

**Resource Subtitle**

Display resource [subtitle](https://nova.laravel.com/docs/2.0/search/global-search.html#subtitles) beneath the title
```php
->subtitle(),
```
or display resource proporties beneath the title
```php
->subtitle('city'),
```

**Timestamps**

Adds timestamp beneath resource title

Optionally can add as a side value, see below.

Defaults: created_at & moment.js format: MM/DD/YYYY:
```php
->timestamp(),
->timestamp('due_at'),
->timestamp('completed_at', 'MM/DD'),
```
Relative timestamps: `5 days ago` | `in 5 days`
```php
->timestamp('completed_at', 'relative'),
```

**Side Values**

Display resource values on the right side
```php
->value('city'),
```

**Aggregated Count**

Add counts of relationships:
```php
->resource(Category::class)
->withCount('posts')
->value('category_posts'),
```

**Aggregated Sum**

Add sum of relationship's column:
```php
->resource(User::class)
->withSum('orders', 'total')
->value('orders_sum'),
```

**Value formatting**

You can change the value output using [numeral.js](http://numeraljs.com/#format)

```php
-value('orders_sum') // 55200
-value('orders_sum', '0.0a') // 55.2k
-value('orders_sum', '($ 0.00 a)') // $55.2k
```
Value Timestamp: add third parameter for [moment.js formats](https://momentjs.com/docs/#/displaying/format/)
```php
->value('created_at') // 2019-04-27 00:00:00
->value('created_at', 'MM/DD', 'timestamp') // 04/27
->value('created_at', 'relative', 'timestamp') // 5 days ago
```

**Limit**

Set the number of items to display, default: 5:
```php
->limit(3)
```

**OrderBy**

Set the order of the resources:
```php
->orderBy('scheduled_at', 'desc')
```

**Show View All Link**

You can link to the resource's index
```php
->viewAll()
```
Or to a lens attached to the resource
```php
->viewAllLens('most-popular-users')
```

**Footer Link**

You can link to a urL instead of using viewAll:
```php
->footerLink('Google', 'https://google.com')
```

**Scoped Resource**

Check the card's uri key within [IndexQuery](https://nova.laravel.com/docs/2.0/resources/authorization.html#index-filtering):

```php
public static function indexQuery($request, $query)
{
    if($request->input('nova-list-card') == 'upcoming-tasks') {
        $query->whereNull('completed_at');
    }

    return $query;
}
```

**CSS Classes**

Customize styles easily if you have your own theme.css
```css
.nova-list-card {}
.nova-list-card-heading {}
.nova-list-card-body {}
.nova-list-card-item {}
.nova-list-card-title {}
.nova-list-card-subtitle {}
.nova-list-card-timestamp {}
.nova-list-card-value {}
.nova-list-card-footer-link {}
```
Also includes resource specific classes etc
```css
.nova-list-card.users.most-tasks
```
Also can target specific rows
```css
.nova-list-card-item-1 {}
.nova-list-card-item-2 {}
.nova-list-card-item-3 {}
```
The uri key is added to the card
```css
#upcoming-tasks {}
```
You can also add classes manually
```php
->classes('font-bold text-red some-custom-class')
```
You can also add alternate row formatting
```php
->zebra()
```

# Examples

![nova-list-card](https://user-images.githubusercontent.com/29180903/56833461-88905e80-683c-11e9-8a04-e3a7ce8dc582.png)

```php
->resource(Contact::class)
->heading('Recent Contacts')
->subtitle('email')
->timestamp()
->limit(3)
->viewAll(),
```
```php
->resource(Contact::class)
->heading('Contacts: Most tasks', 'Tasks')
->orderBy('tasks_count', 'desc')
->subtitle('email')
->value('tasks_count')
->withCount('tasks')
->zebra()
->viewAll(),
```
```php
->resource(Contact::class)
->heading('Top Opportunities', 'Estimates')
->withSum('opportunities', 'estimate')
->value('opportunities_sum', '0.0a')
->viewAllLens('top-opportunities')
->orderBy('opportunities_sum', 'desc'),
```

### Methods

| Method | Description |
| - | - |
| resource() | declare the resource |
| heading() | add a title to card |
| subtitle() | display subtitle value |
| timestamp() | display & format timestamp |
| value() | display right side value |
| withCount() | aggregate count value |
| withSum() | aggregate sum value |
| orderBy() | set the resource order |
| limit() | set number of resources |
| viewAll() | enable view all link |
| viewAllLens() | enable lens view all |
| footerLink()| add a static footer link |
| zebra() | add alternate row color |
| id() | unique id for card's requests |
| classes() | add css classes to card |
