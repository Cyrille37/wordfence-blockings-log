# wordpress-wordfence-blockings-scan

Le scenario `wordpress-wordfence-blockings-scan` déclenche des décisions `Crowdsec`
à parti du parser `wordpress-wordfence-blockings-log`
qui lit le log généré par le plugin Wordpress `wordfence-blockings-log`
qui inscrit les actions déclenchées par le plugin Wordpress `Wordfence`.

Ce scénarion retranscrit les alertes Wordfence qui commence par "Exceeded the maximum ..."
comme "Exceeded the maximum global requests per minute for crawlers or humans.".
