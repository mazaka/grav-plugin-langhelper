# Grav LangHelper Plugin

`LangHelper` is a [Grav](http://github.com/getgrav/grav) plugin that provides native language text links to switch between [Multiple Languages](http://learn.getgrav.org/content/multi-language) in Grav **0.9.30** or greater.

# Installation

Installing the LangSwitcher plugin can be done in one of two ways. Our GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `langhelper`. You can find these files either on [GitHub](https://github.com/mazaka/grav-plugin-langhelper).

You should now have all the plugin files under

    /your/site/grav/user/plugins/langhelper

# Usage

The `langhelper` plugin doesn't require any configuration. You do however need to add the included Twig partials template into your own theme somewhere you want the available languages to be displayed.

```
{% include 'partials/langswitcher.html.twig' %}
{% include 'partials/langswitcherDropdown.html.twig' %}
```

Get the active language code
```
{{ langhelper_current() }}
```

Get the native name of a language
```
{{ langhelper_native_name('en') }}
```

Get the page-url for a language
```
{{ langhelper_pageurl(page, 'fr') }}
```

Get the image-url for a language
```
{{ langhelper_flagimg('fr') }}
```

Something you might want to do is to override the look and feel of the langswitcher, and with Grav it is super easy.

Copy the template file [langswitcher.html.twig](templates/partials/langswitcher.html.twig) into the `templates` folder of your custom theme:

```
/your/site/grav/user/themes/custom-theme/templates/partials/langswitcher.html.twig
```

You can now edit the override and tweak it however you prefer.

## Configuration

Simply copy the `user/plugins/langhelper/langswitcher.yaml` into `user/config/plugins/langswitcher.yaml` and make your modifications.

```
enabled: true
```

Options are pretty self explanatory.

