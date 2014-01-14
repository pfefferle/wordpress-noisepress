*Artikel von [notizBlog.org](http://notizblog.org/2010/11/05/noisepress-nie-wieder-irrelevante-inhalte/)*

Die Informationsflut im Internet nimmt immer mehr zu und FeedReader bieten bisher keine wirkliche Möglichkeit
diese Informationen sinnvoll zu filtern und da man nicht wirklich (zeitnah) Einfluss auf die Weiterentwicklung
von NetNewsWire, Google Reader & Co. hat, bleibt nur noch eins: **Erst filtern, dann abonnieren!**

**NoisePress** erlaubt Seitenbesucher, einen RSS/ATOM-Feed mit Hilfe von [APML](http://notizblog.org/2007/11/23/apml-attention-profiling-mark-up-language/) vorzufiltern.

(Zum ausprobieren braucht man ein APML-Profil. Wer keines hat, sollte sich entweder das [WordPress Plugin](http://wordpress.org/plugins/apml/)
installieren oder heimlich [Carstens Profil](http://notsorelevant.com/apml) benutzen ;) )

## Warum mit APML filtern?

Man könnte natürlich auch mit <em>WordPress</em>-Bordmitteln eine Menge Rauschen ausfiltern, und wirklich nur das abonnieren was gerade wichtig ist:

* Tags Feed:  <http://notizblog.org/tag/openid,oauth/feed/>
* Category Feed: <http://notizblog.org/category/openweb-notizen/feed/>

Das Problem: Ändert sich dieses Interesse, müssen alle Feeds mühsam aussortiert (und neue gesammelt) werden. Außerdem besteht die Gefahr, dass einige *spannende* Themen, die nicht genau die abonnierte Kategorie/den abonnierten Tag besitzen, durch das Raster fallen können.

Das Prinzip von NoisePress: APML ist eine Art semantische Tag-Clound die das Interesse einer Person widerspiegelt. Das *Interessens-Profil* wird in der Regel automatisch generiert und sollte sich somit auch den diversen Interessensveränderungen anpassen.

Am Beispiel *WordPress Plugin*: Das Plugin erstellt ein APML-File anhand der Häufigkeit der verwendeten Tags und Kategorien. Schreibt
jemand viel über OpenID, kann man davon ausgehen, dass er das Thema für wichtig hält. Ändert sich der Fokus des Blogs, wird OpenID auch im
APML-Feed immer irrelevanter.

## Hört sich nach Geek-Zeugs an?

Richtig! :) ...aber NoisePress ist auch erst einmal nur ein Test ob meine Idee überhaupt funktioniert! Im besten Fall soll der User von all
der Technik gar nichts mitbekommen. Ich hoffe dass sich Firefox' [Account Manager](http://www.mozilla.com/en-US/firefox/accountmanager/) oder
[XAuth](http://notizblog.org/tag/xauth/) schnell weiter entwickeln und ich eine dieser Techniken für NoisePress <em>missbrauchen</em> könnte.

Ich würde mich übrigens sehr über ein bisschen Feedback freuen!