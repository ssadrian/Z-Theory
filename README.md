# GAMIFI-K

## Z-Theory

### Setup
```shell
git clone https://github.com/ssadrian/Z-Theory
cd Z-Theory

# Deberia ejecutarse solo la primera vez cuando se clona el repositorio
./setup.ps1

# Con este comando se lanzara el servidor php y angular
./run.ps1
```

En caso de que los scripts `ps1` fallan, se tendra que executar este comando con privilegios de administrador
```shell
Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy Unrestricted
```

Para anular los cambios hecho por el comando anterior
```shell
Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy Default
```
