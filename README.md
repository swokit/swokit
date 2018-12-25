# Swokit

Swokit - Swoole kit library. 

Some useful components for use swoole.

Components:

- `conn-pool`
- `server`
- `rpc-server`
- `http-server`
- `websocket-server`
- `task`
- `utils`
- `process`

## Install

```bash
composer require swokit/swokit
```

## Git Subtree

```bash
# add git repo
./subtree-add.sh NAME

# pull git repo:
./subtree-pull.sh NAME|all

# push to repos:
./subtree-push.sh NAME|all
```

## Composer

```json
{
  "replace": {
    "swokit/conn-pool": "self.version",
    "swokit/task": "self.version",
    "swokit/utils": "self.version",
    "swokit/process": "self.version",
    "swokit/server": "self.version",
    "swokit/http-server": "self.version",
    "swokit/rpc-server": "self.version",
    "swokit/websocket-server": "self.version"
  }
}
```

## License

[MIT](LICENSE)
