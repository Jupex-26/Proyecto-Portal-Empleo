# Imagen base oficial de MySQL
FROM mysql:8.0

# Establecer variables de entorno
ENV MYSQL_ROOT_PASSWORD=A5eFVJMs
ENV MYSQL_DATABASE=empleo

# Definir el directorio de trabajo (opcional)
# Copiar el script de inicialización dentro de la imagen
COPY init.sql /docker-entrypoint-initdb.d/



# (Opcional) establecer el hostname dentro del contenedor
# Nota: Docker no permite cambiar el hostname desde el Dockerfile directamente.
# El hostname se define al crear el contenedor (docker run o docker compose).
# Pero puedes documentarlo aquí:
# HOSTNAME bd