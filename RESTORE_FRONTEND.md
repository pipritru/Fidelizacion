Restauración de frontend desde backup

Este proyecto incluye una copia de seguridad del frontend en `backups/frontend-backup`.

Para restaurar la copia original (sustituir la carpeta `frontend` con la copia de seguridad):

1. Asegúrate de no tener cambios locales que quieras conservar.
2. En el directorio raíz del proyecto ejecuta:

```bash
rm -rf frontend && cp -a backups/frontend-backup frontend
```

3. Si usas control de versiones (git) puedes comparar antes de sobreescribir:

```bash
# comparar cambios
rsync -av --dry-run backups/frontend-backup/ frontend/
```

Notas:
- El backup fue hecho con `cp -a` (preserva permisos y estructura).
- Si prefieres un archivo zip podemos crear uno instalando `zip` y generándolo.
