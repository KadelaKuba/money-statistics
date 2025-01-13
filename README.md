## Personal parser for Airbank transactions

### Local setup

1. Start containers by running:
```
make run
```
2. Build app
```
make build-dev
```
3. Run parser command script
```
make parse-airbank-transactions
```

### Development

#### Standards (ECS, PHPStan,..)
Use script for checking all app standards
```
make check-all
```

#### Makefile
For effective work with host system. The config and more info in `Makefile` in project root directory.