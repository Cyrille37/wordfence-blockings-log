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

```
# cscli -c ../dev.yaml hubtest run wordfence-blockings-log --report-success
Running test 'wordfence-blockings-log'
─────────────────────────────────────────────
 Test                     Result  Assertions 
─────────────────────────────────────────────
 wordfence-blockings-log  ✅      145        
─────────────────────────────────────────────

# cscli -c ../dev.yaml hubtest run wordfence-blockings-log --trace
...
len(results) == 2
len(results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"]) == 13
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Success == true
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Parsed["event_type"] == "block"
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Parsed["message"] == "Accessed a banned URL"
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Parsed["source_ip"] == "45.154.138.247"
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Parsed["status_code"] == "3600"
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Parsed["timestamp"] == "02/Jan/2026:23:54:55 +0000"
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Meta["event_type"] == "block"
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Meta["log_type"] == "wordfence-blockings-log"
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Meta["program"] == "wordfence-blockings-log"
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Meta["source_ip"] == "45.154.138.247"
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][0].Evt.Whitelisted == false
results["s01-parse"]["cyrille37/wordpress-wordfence-blockings-log"][1].Success == true
...
```
