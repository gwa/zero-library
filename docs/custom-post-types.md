# Custom post types

### How to rewrite a custom post type url slug

~~~php
public function getOptions()
{
    return [
        'rewrite' => [
            'slug' => 'rezept'
        ]
    ];
}
~~~
