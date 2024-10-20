FROM php:8.2-apache

# Abilita mod_rewrite
RUN a2enmod rewrite

# Riavvia Apache per applicare le modifiche
RUN service apache2 restart