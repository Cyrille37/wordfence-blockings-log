# wordpress-wordfence-blockings-wsn

Le scenario `wordpress-wordfence-blockings-scan` déclenche des décisions `Crowdsec`
à parti du parser `wordpress-wordfence-blockings-log`
qui lit le log généré par le plugin Wordpress `wordfence-blockings-log`
qui inscrit les actions déclenchées par le plugin Wordpress `Wordfence`.

Ce scénario retranscrit les IPs à bannir diffusées par le "Wordfence Security Network".
