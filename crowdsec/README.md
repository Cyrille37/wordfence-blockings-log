# Crowdsec test

Build Crowdsec test env Docker image `crowdsec_test_env`, then ...

```bash
DEST_HUB=/crowdsec-v1.7.8/tests/hub
docker run --rm -ti \
 -v ./tests-log:$DEST_HUB/.tests/wordfence-blockings-log \
 -v ./hub/wordpress-wordfence-blockings-log.md:$DEST_HUB/parsers/s01-parse/cyrille37/wordpress-wordfence-blockings-log.md \
 -v ./hub/wordpress-wordfence-blockings-log.yaml:$DEST_HUB/parsers/s01-parse/cyrille37/wordpress-wordfence-blockings-log.yaml \
 crowdsec_test_env bash

# se placer dans l'environnement de test
cd /crowdsec-v1.7.8/tests/hub

# tester le parser
# Options
# --clean : supprime automatiquement les dossiers `results` et `runtime` en cas d'erreur
# --no-clean : désactive la suppression dossiers `results` et `runtime` quand pas d'erreur
# --trace : pour avoir le debug des opérations, 📢 indispensable pour le dev

cscli -c ../dev.yaml hubtest run wordfence-blockings-log --trace

```

## acquisition

Dans l'acquisition (test: `config.yaml`, prod: `/etc/crowdsec/acquis.d/xyz.yaml`):
  - `log_type: wordpress-wordfence-blockings`

Et dans le parser ;
  - `filter: evt.Line.Labels.type == 'wordpress-wordfence-blockings'`

## parser

On utilise par les parser "s00-raw" il faut donc dire à `grok` d'appliquer le pattern sur `Line.Raw` au lieu de `message`.

📢 L'option `--trace` est essentiel pour le dév !
