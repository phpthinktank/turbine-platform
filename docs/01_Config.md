# Configuration

## Nodes

Nodes are necessary to provide environment control and environment based configuration. Within each node you could define a specific config location.

### Defaults

The default node is defined within `/app/res/config/nodes.json` and is defined as follows.

```json
{
  "default": {
    "http": {
      "type": "url",
      "pattern": ".+",
      "priority": "last",
      "description": "Default node HTTP-Application",
      "config": "/config/{{environment}}/{{node_id}}.json"
    },
    "cli": {
      "type": "cli",
      "pattern": ".+",
      "priority": "last",
      "description": "Default node for CLI-Application",
      "config": "/config/{{environment}}/{{node_id}}.json"
    }
  }
}
```

### Options

#### `environment`

Target environment received via getenv and defined in .env in projectroot. Turbine CMS is using [phpdotenv by vlucas](https://github.com/vlucas/phpdotenv). If no environment defined, the environment is defined as `default`. Defined environment inherit `default` environment.

#### `node_id`

Unique node identifier. Nodes inherit values from defined node with same `node_id` of `default`environment.

#### `type`

The type is taking am url or url part and matches against match option

- `url_path`: valid url path http:// example.com __/my/path__ ?param=value
- `url_host`: valid url host __http:// example.com__ /my/path ?param=value
- `url_query`: valid url query http:// example.com /my/path ? __param=value__
- `url`: valid url __http:// example.com /my/path ?param=value__
- `cli`: valid cli environment

#### `pattern`

Valid regex pattern matched against value received from type. Defualt is `.+`

#### `priority`

Set order for node stack. 

- `first` is set node on top of stack
- `last` at the end of stack
- `0 ... n` declare postion to valid unsigned number.

#### `description` *(optional)*

Short description for node

#### `config`

Defines path to documentation. You could also access following parameters with `{{param}}`

##### Available params

- `node_id` of current node
- `environment` of current node

### Synopsis

```json
{
  "<environment>": {
    "nodes": {
      "<node_id>": {
        "type": "<url_path, url_host, url_query, url, cli>",
        "pattern": "<regex, not for cli>",
        "priority": "<first, 0 ... n, last>",
        "description": "<some text>",
        "config": "path/to/config/file.php"
      }
    }
  }
}
```

### Inject a custom nodes.json



## Configuration

The default configuration is stored in `/res/config/default/default.json`. This configuration is default configuration for
all other configuration files within this environment.

A specific configuration file for a node is named as `<node_id>.<json, php>`. We deliver a default configuration for CLI and 
HTTP applications.

A config file could be a valid `php` or `json`.

### JSON

The JSON config needs to be a valid Object `{}`.

Example:

```json
{
    "key": "value",
    "assoc": {
        "foo": "value",
        "bar": "value"
    }
}
```
        
### PHP

The PHP config needs to be a valid associative Array `[]`.

Example:

```php
<?php
[
    "key": "value",
    "assoc": [
        "foo" => "value",
        "bar" => "value"
    ]
]
```