# Pilo'Press

## Requirements

This plugin requires [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/) and [Advanced Custom Fields: Extended](https://wordpress.org/plugins/acf-extended/) plugins in order to work correctly.

## Plugin installation

- Activate **Advanced Custom Fields Pro** plugin.
- Activate **ACF Extended** plugin.
- Activate **Pilo'Press** plugin.

## Theme installation

### Instructions
- In your theme, create a `pilopress` folder
- Within the `pilopress` folder, create a `layouts` subfolder and a `tailwind` subfolder as you can see in [Theme structure](https://github.com/Pilotin/PiloPress#theme-structure) part.
- In the `index.php` file, add the following code:
```php
<?php 

// WordPress Header
get_header(); 

// Pilo'Press: Header
get_pip_header();

?>

<?php if( have_posts() ): ?>
    <?php while( have_posts() ): the_post(); ?>
        
        <?php 
        
        // Pilo'Press: Content
        the_pip_content();
        
        ?>
    
    <?php endwhile; ?>
<?php endif; ?>
    
<?php 

// Pilo'Press: Footer
get_pip_footer();

// WordPress: Footer
get_footer();

?>
```
- In the `functions.php` file, add the following code:

```php
// Pilo'Press: Front-end
add_action( 'wp_enqueue_scripts', 'enqueue_pilopress_styles' );
function enqueue_pilopress_styles() {

    wp_enqueue_style( 'style-pilopress', get_stylesheet_directory_uri() . '/pilopress/tailwind/tailwind.min.css', false );
    
}
 
// Pilo'Press: Back-end
add_action( 'admin_enqueue_scripts', 'admin_enqueue_pilopress_styles' );
function admin_enqueue_pilopress_styles() {

    wp_enqueue_style( 'style-pilopress-admin', get_stylesheet_directory_uri() . '/pilopress/tailwind/tailwind-admin.min.css', false );
    
}
```

### Theme structure
```
your-theme/
└── pilopress/
    ├── layouts/
    |   ├── layout-1/
    |   |      ├── layout-1.js
    |   |      ├── layout-1.php
    |   |      ├── layout-1.css
    |   |      ├── layout-1.css.map
    |   |      └── group_123abcde.json
    |   └── layout-2/
    |          ├── layout-2.js
    |          ├── layout-2.php
    |          ├── layout-2.css
    |          ├── layout-2.css.map
    |          └── group_123abcde.json
    └── tailwind/
        ├── tailwind.config.js
        ├── tailwind.css
        ├── tailwind.min.css
        └── tailwind-admin.min.css
```

### Tailwind CSS files

All files under the `tailwind` folder are generated automatically.  
When you will save `Pilo'Press > Styles > Tailwind` options in back-office, two files will be generated: 
- `tailwind.css` file will take the content of the "Tailwind CSS" option.  
- `tailwing.config.js` file will take the content of the "Tailwind Configuration" option.

If you click on "Update & Compile" and compile remotely thank to [TailwindAPI](https://www.tailwindapi.com/), `tailwind.min.css` and `tailwind-admin.min.css` files will be generated.

For more details, see [Tailwind CSS Documentation](https://tailwindcss.com/docs/installation/).

### Customizing style

#### General
To customize default Tailwind styles, go to `Pilo'Press > Styles` from left navigation menu or top bar menu.  
You can add fonts, customize image sizes and add custom styles for TinyMCE editor.

#### Add fonts
First step, we will go to `Pilo'Press > Styles > Fonts` and add a font.  
You have 2 choices : Google Font or Custom font.  

##### Example: Google Font
Let's say we want to add Google's Roboto Font.  
We have to fill the fields as following:  
```
Name:            Roboto
URL:             https://fonts.googleapis.com/css2?family=Roboto&display=swap
Auto-enqueue:    true
```
**NB:** The `Auto-enqueue` option will add or not the `<link>` tag.  

##### Example: Custom font
Let's say we want to add [Homework Font](https://www.dafont.com/fr/homework-3.font).  
_Be careful with your font formats, because of [browser compatibility](https://www.w3schools.com/css/css3_fonts.asp)._  

We have to fill the fields as following:  
```
Name:      Homework
Files:     <Your files>
Weight:    normal         // Depends on your font
Style:     normal         // Depends on your font
```
When you will save, the `@font-face` code will be added automatically.  


Then, to use those fonts, we have 2 different ways.

###### #1 - Custom class
We can add a custom class in `Pilo'Press > Styles > Tailwind`, in CSS field.  
Something like that:
```css
.font-roboto {
    font-family: "Roboto", sans-serif;
}

.font-homework {
    font-family: "Homework", sans-serif;
}
```
We will be able to use those classes everywhere, after we have re-build the styles files.

###### #2 - Tailwind configuration file
As explain in [Tailwind Documentation](https://tailwindcss.com/docs/font-family/#font-families), you can define custom fonts and modify the default ones.  
Let's say we want to add our custom fonts without removing default ones, so we can write something like that:
```js
module.exports = {
    theme: {
        extend: {
            fontFamily: {
                roboto: ['Roboto', 'sans-serif'],
                homework: ['Homework', 'sans-serif'],
            },
        },
    },
};
```
Tailwind will generate the following classes: `font-roboto` and `font-homework`.

#### Customize image sizes

You can customize default WordPress image sizes and add new ones in `Pilo'Press > Styles > Images`.

#### TinyMCE custom styles

In `Pilo'Press > Styles > TinyMCE`, you will be able to add font style, font family, font color and buttons styles which will be available in TinyMCE Editor.

### Add new layout

- In the admin menu `Pilo'Press > Layouts`, add a new layout
- Configure the layouts fields
- Configure the layouts settings to match your theme `/your-theme/pilopress/layouts/` folder structure
- You have to name the files the same way you did in back-office settings

### Sync layout

- Add folder `pilopress/layouts/your-layout/` with your layout files in it (PHP, JS, CSS, JSON).
- Go to `Pilo'Press > Layouts > Sync available` and sync your layout field group.


### Templating

To display the content of your post, you have to use the following function : `the_pip_content()` or `echo get_pip_content()` .

### Styles settings Import/Export

Go to `Custom Fields > Tools`, you have two new tools to import and export your styles settings.

## Available hooks

- Locations where main flexible is visible  
`add_filter( 'pip/flexible/locations', array() );`  
_Default value_  
```php
array(
    array(
        array(
            'param'    => 'post_type',
            'operator' => '==',
            'value'    => 'all',
        ),
    ),
    array(
        array(
            'param'    => 'taxonomy',
            'operator' => '==',
            'value'    => 'all',
        ),
    ),
);
```

- Capability for Pilo'Press options pages  
`add_filter( 'pip/options/capability', 'your_capability' );`  
_Default value_  
`acf_get_setting( 'capability' );`

## Timber compatibility

:link: [Timber documentation](https://timber.github.io/docs/)

We will use the Timber [Starter Theme](https://github.com/timber/starter-theme) in this example. You will need [Timber plugin](https://fr.wordpress.org/plugins/timber-library/) to be activated.  
To make the starter theme Pilo'Press ready, you have to create a `pilopress` folder in your theme (as described in [Theme Structure](https://github.com/Pilotin/PiloPress#theme-structure) part).  
You can enqueue Pilo'Press styles as described in [Instructions](https://github.com/Pilotin/PiloPress#instructions) part.  
Finally, you have to add `'pilopress/layouts'` in the `Timber::$dirname` array in `functions.php` file.  

Regarding layouts files, you can use the PHP/Twig files duo perfectly.  

**Example**  
Let's say we have a layout named "Title" with a single ACF field (type text) named _title_.

- The PHP file will look like that:
```php
<?php

// Get Timber context
$context = Timber::context();

// If you need the post object, you will have to re-add it to the context
// $timber_post      = new Timber\Post();
// $context['post']  = $timber_post;

// Get the ACF field
$context['title'] = get_sub_field( 'title' );

// Render
Timber::render( 'title.twig', $context );
?>
```
- The Twig file will look like that:
```twig
<h3>{{ title }}</h3>
```

So the theme structure will be almost the same, but with a `title.twig` file added:  
```
starter-theme/
└── pilopress/
    └── layouts/
        └── title/
            ├── title.js
            ├── title.php
            ├── title.twig
            ├── title.css
            ├── title.css.map
            └── group_123abcde.json
```

## Enhancements

- Icônes de localisation dans le menu Flexible : depuis ACFE
- Changement de menu parent pour l'édition des layouts : enlever le JS
